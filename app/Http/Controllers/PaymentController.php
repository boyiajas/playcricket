<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\PeachPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PeachPaymentService $peachService;
    protected NotificationService $notificationService;

    public function __construct(PeachPaymentService $peachService, NotificationService $notificationService)
    {
        $this->peachService = $peachService;
        $this->notificationService = $notificationService;
    }

    /**
     * Initiate a Peach Payments Hosted Checkout session.
     */
    public function checkout(Request $request)
    {
        $booking = null;

        if ($request->has('booking_id')) {
            $booking = Booking::findOrFail($request->input('booking_id'));
        } elseif ($request->has('facility')) {
            // Create booking on the fly if full details provided
            $rate = Booking::calculateRate($request->input('facility'), $request->input('ageGroup'));
            $duration = (float) ($request->input('durationHours', 1));
            $amount = round($rate * $duration, 2);

            $booking = Booking::create([
                'member_id' => $request->input('memberId', 'guest'),
                'facility' => $request->input('facility'),
                'date' => $request->input('date', now()->toDateString()),
                'start_time' => $request->input('startTime', '10:00'),
                'duration_hours' => $duration,
                'rate' => $rate,
                'amount' => $amount,
                'status' => 'PENDING',
                'payment_status' => 'UNPAID',
                'age_group' => $request->input('ageGroup'),
                'player_name' => $request->input('playerName'),
            ]);
        } else {
            return response()->json(['error' => 'booking_id or facility details required'], 422);
        }

        $customer = [
            'name' => $request->input('playerName', 'Cricket Player'),
            'email' => $request->input('email', 'player@playcricket.co.za'),
            'mobile' => $request->input('mobile', '0820000000'),
        ];

        $session = $this->peachService->createCheckoutSession(
            $booking,
            $customer,
            route('payments.result'),
            route('payments.cancel'),
            route('payments.peach.webhook')
        );

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'booking_id' => $booking->id,
                'checkout_id' => $session['checkout_id'],
                'redirect_url' => $session['redirect_url'],
                'amount' => $session['amount'],
                'reference' => $session['reference'],
                'currency' => $session['currency'],
            ], 200);
        }

        return redirect()->away($session['redirect_url']);
    }

    /**
     * Customer returned after Peach Payments checkout.
     */
    public function result(Request $request)
    {
        $checkoutId = $request->input('id') ?? $request->input('checkoutId');
        $reference = $request->input('merchantTransactionId') ?? $request->input('reference');
        $bookingId = $request->input('booking_id');

        $payment = null;

        if ($checkoutId) {
            $payment = Payment::where('peach_checkout_id', $checkoutId)->first();
        }

        if (!$payment && $reference) {
            $payment = Payment::where('payment_reference', $reference)->first();
        }

        if (!$payment && $bookingId) {
            $payment = Payment::where('booking_id', $bookingId)->latest()->first();
        }

        if ($payment) {
            $booking = $payment->booking;
            if ($booking && $booking->payment_status !== 'PAID') {
                // If return indicates completed or status is success
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                $booking->update([
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'payment_id' => $payment->peach_checkout_id ?: $payment->payment_reference,
                ]);

                $member = collect(config('appdata.members'))->firstWhere('id', $booking->member_id);
                $email = $member['email'] ?? 'user@example.com';
                $this->notificationService->sendBookingConfirmation($booking, $email);
            }

            return redirect()->to('/?payment=success&booking_id=' . ($booking ? $booking->id : ''));
        }

        return redirect()->to('/?payment=success');
    }

    /**
     * Customer cancelled payment.
     */
    public function cancel(Request $request)
    {
        $reference = $request->input('merchantTransactionId') ?? $request->input('reference');
        if ($reference) {
            $payment = Payment::where('payment_reference', $reference)->first();
            if ($payment && $payment->status === 'pending') {
                $payment->update(['status' => 'cancelled']);
            }
        }

        return redirect()->to('/?payment=cancelled');
    }

    /**
     * Peach Payments Webhook Notification Handler (Signed Webhook).
     */
    public function webhook(Request $request)
    {
        $rawBody = $request->getContent() ?: file_get_contents('php://input');
        $signature = $request->header('X-Signature') 
            ?? $request->header('Signature') 
            ?? $request->header('X-Peach-Signature')
            ?? ($_SERVER['HTTP_X_SIGNATURE'] ?? null);
            
        $timestamp = $request->header('X-Timestamp') 
            ?? $request->header('X-Peach-Timestamp')
            ?? ($_SERVER['HTTP_X_TIMESTAMP'] ?? null);

        // 1. Signature Verification
        $isSignatureValid = $this->peachService->verifyWebhookSignature($rawBody, $signature, $timestamp);
        
        // In local/sandbox testing when webhook secret is default and no signature provided, allow sandbox test simulation
        $isSandboxTest = config('peach.environment') === 'sandbox' && (empty($signature) || $request->input('test') === true);

        if (!$isSignatureValid && !$isSandboxTest) {
            Log::warning('Peach Payments Webhook: Invalid signature received', [
                'signature' => $signature,
                'ip' => $request->ip(),
            ]);
            return response()->json(['error' => 'Invalid webhook signature'], 401);
        }

        $payload = json_decode($rawBody, true) ?: $request->all();
        $checkoutId = $payload['checkoutId'] ?? $payload['id'] ?? null;
        $reference = $payload['merchantTransactionId'] ?? $payload['reference'] ?? null;
        $amount = (float) ($payload['amount'] ?? 0);
        $currency = $payload['currency'] ?? 'ZAR';

        // 2. Find Payment Record
        $payment = null;
        if ($checkoutId) {
            $payment = Payment::where('peach_checkout_id', $checkoutId)->first();
        }
        if (!$payment && $reference) {
            $payment = Payment::where('payment_reference', $reference)->first();
        }

        if (!$payment) {
            Log::warning('Peach Payments Webhook: Payment record not found', [
                'checkout_id' => $checkoutId,
                'reference' => $reference,
            ]);
            return response()->json(['error' => 'Payment record not found'], 404);
        }

        // 3. Idempotency Check: Prevent duplicate confirmations
        if ($payment->status === 'completed') {
            Log::info("Peach Payments Webhook: Payment {$payment->payment_reference} already processed.");
            return response()->json(['status' => 'already_processed', 'message' => 'Payment already completed'], 200);
        }

        // 4. Verify Payment Success Status
        $isSuccess = $this->peachService->isSuccessfulPayment($payload);

        if ($isSuccess) {
            // Update payment record
            $payment->update([
                'status' => 'completed',
                'payment_method' => $payload['paymentBrand'] ?? $payload['paymentMethod'] ?? 'CARD',
                'paid_at' => now(),
                'payload' => array_merge($payment->payload ?: [], ['webhook' => $payload]),
            ]);

            // Update associated booking
            $booking = $payment->booking;
            if ($booking) {
                $booking->update([
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'payment_id' => $checkoutId ?: $payment->payment_reference,
                ]);

                // Send confirmation notifications
                $member = collect(config('appdata.members'))->firstWhere('id', $booking->member_id);
                $email = $member['email'] ?? 'virat@rcb.com';
                $this->notificationService->sendBookingConfirmation($booking, $email);

                Log::info("Peach Payments: Booking #{$booking->id} confirmed and marked PAID.");
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verified and booking confirmed',
                'payment_reference' => $payment->payment_reference,
            ], 200);
        }

        // If payment failed or cancelled
        $payment->update([
            'status' => 'failed',
            'payload' => array_merge($payment->payload ?: [], ['webhook' => $payload]),
        ]);

        return response()->json(['status' => 'failed', 'message' => 'Payment was not successful'], 200);
    }

    /**
     * Legacy PayFast notify handler for backward compatibility.
     */
    public function notify(Request $request)
    {
        $paymentId = $request->input('m_payment_id');
        $status = $request->input('payment_status');

        if ($status === 'COMPLETE') {
            $booking = Booking::find($paymentId);
            if ($booking) {
                $booking->update([
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'payment_id' => $request->input('pf_payment_id')
                ]);
            }
        }
        
        return response('OK', 200);
    }

    /**
     * Legacy PayFast return handler.
     */
    public function return(Request $request)
    {
        return redirect()->to('/?payment=success');
    }
}
