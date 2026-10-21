<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\PayFastService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BookingController extends Controller
{
    protected $payFastService;
    protected $peachPaymentService;
    protected $notificationService;

    public function __construct(
        PayFastService $payFastService, 
        \App\Services\PeachPaymentService $peachPaymentService,
        \App\Services\NotificationService $notificationService
    ) {
        $this->payFastService = $payFastService;
        $this->peachPaymentService = $peachPaymentService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        if (!$this->ensureBookingsTable()) {
            Log::warning('Bookings table missing, falling back to config data');
            return response()->json(config('appdata.bookings'));
        }

        if (Booking::count() === 0) {
            $this->seedInitialBookings();
        }

        return response()->json(Booking::orderBy('date', 'desc')->orderBy('start_time', 'asc')->get());
    }

    public function byMember(string $memberId)
    {
        if (!$this->ensureBookingsTable()) {
            $filtered = collect(config('appdata.bookings'))->filter(function ($b) use ($memberId) {
                return ($b['memberId'] ?? $b['member_id'] ?? null) === $memberId;
            })->values();
            return response()->json($filtered);
        }

        return response()->json(Booking::where('member_id', $memberId)->orderBy('date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'memberId' => 'nullable|string',
            'facility' => 'required|string',
            'date' => 'required|date',
            'startTime' => 'required|string',
            'durationHours' => 'required|numeric',
            'status' => 'nullable|string',
            'payNow' => 'boolean',
            'ageGroup' => 'nullable|string', // 'Under 13', 'Above 13'
            'playerName' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
        ]);

        // Logic: Age/Lane Assignment
        $lane = null;
        if (isset($data['ageGroup'])) {
            if ($data['ageGroup'] === 'Under 13') {
                $lane = 'Lane 1';
            } elseif ($data['ageGroup'] === 'Above 13') {
                $lane = 'Lane 2';
            }
        }

        // Official Pricing (Lane Hire R350, Junior Coaching R450, Senior Coaching R600)
        $rate = isset($data['rate']) ? (float)$data['rate'] : Booking::calculateRate($data['facility'], $data['ageGroup'] ?? null);
        $durationHours = (float) $data['durationHours'];
        $amount = isset($data['amount']) ? (float)$data['amount'] : round($rate * $durationHours, 2);

        $bookingPayload = [
            'member_id' => $data['memberId'] ?? 'guest',
            'facility' => $data['facility'],
            'date' => $data['date'],
            'start_time' => $data['startTime'],
            'duration_hours' => $durationHours,
            'rate' => $rate,
            'amount' => $amount,
            'status' => 'PENDING', // Will be confirmed after payment if payNow is true
            'payment_status' => 'UNPAID',
            'age_group' => $data['ageGroup'] ?? null,
            'player_name' => $data['playerName'] ?? null,
            'lane' => $lane
        ];

        // If not paying now, we can auto-confirm for reservation
        if (!$request->boolean('payNow')) {
             $bookingPayload['status'] = 'CONFIRMED';
        }

        if (!$this->ensureBookingsTable()) {
            return response()->json([
                'booking' => $this->buildFallbackBooking($bookingPayload),
                'message' => 'Booking created successfully (non-persistent fallback)'
            ], 201);
        }

        $booking = Booking::create($bookingPayload);

        // Send Notification if confirmed
        if ($booking->status === 'CONFIRMED') {
            $member = collect(config('appdata.members'))->firstWhere('id', $booking->member_id);
            $email = $member['email'] ?? 'user@example.com';
            $this->notificationService->sendBookingConfirmation($booking, $email);
        }

        $response = [
            'booking' => $booking,
            'message' => 'Booking created successfully'
        ];

        if ($request->boolean('payNow')) {
            $peachSession = $this->peachPaymentService->createCheckoutSession(
                $booking,
                [
                    'name' => $data['playerName'] ?? 'Cricket Player',
                    'email' => $request->input('email', 'player@playcricket.co.za'),
                ],
                route('payments.result'),
                route('payments.cancel'),
                route('payments.peach.webhook')
            );

            $paymentData = $this->payFastService->generatePaymentForm(
                $booking,
                route('payment.return'),
                route('payment.cancel'),
                route('payment.notify')
            );

            $response['payment_data'] = array_merge($paymentData, [
                'gateway' => 'peach_payments',
                'checkout_id' => $peachSession['checkout_id'],
                'redirect_url' => $peachSession['redirect_url'],
                'reference' => $peachSession['reference'],
                'amount' => $peachSession['amount'],
            ]);
            $response['peach_checkout'] = $peachSession;
        }

        return response()->json($response, 201);
    }

    public function update(Request $request, string $id)
    {
        if (!$this->ensureBookingsTable()) {
            return response()->json(['message' => 'No persistent database'], 200);
        }

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $data = $request->validate([
            'facility' => 'nullable|string',
            'date' => 'nullable|date',
            'startTime' => 'nullable|string',
            'durationHours' => 'nullable|numeric',
            'status' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'ageGroup' => 'nullable|string',
            'playerName' => 'nullable|string',
            'lane' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
        ]);

        if (isset($data['facility'])) $booking->facility = $data['facility'];
        if (isset($data['date'])) $booking->date = $data['date'];
        if (isset($data['startTime'])) $booking->start_time = $data['startTime'];
        if (isset($data['durationHours'])) $booking->duration_hours = $data['durationHours'];
        if (isset($data['status'])) $booking->status = $data['status'];
        if (isset($data['payment_status'])) $booking->payment_status = $data['payment_status'];
        if (isset($data['ageGroup'])) {
            $booking->age_group = $data['ageGroup'];
            $booking->lane = $data['ageGroup'] === 'Under 13' ? 'Lane 1' : 'Lane 2';
        }
        if (isset($data['playerName'])) $booking->player_name = $data['playerName'];
        if (isset($data['lane'])) $booking->lane = $data['lane'];

        // Recalculate rate and amount if changed or provided
        $booking->rate = isset($data['rate']) ? (float)$data['rate'] : Booking::calculateRate($booking->facility, $booking->age_group);
        $duration = (float)($booking->duration_hours ?: 1);
        $booking->amount = isset($data['amount']) ? (float)$data['amount'] : round($booking->rate * $duration, 2);

        $booking->save();

        return response()->json([
            'booking' => $booking,
            'message' => 'Booking updated successfully'
        ]);
    }

    public function destroy(string $id)
    {
        if (!$this->ensureBookingsTable()) {
            // No persistence available; pretend success so UI state stays in sync
            return response()->json(['message' => 'Deleted (no persistence)'], 200);
        }

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    private function seedInitialBookings(): void
    {
        try {
            $today = now()->toDateString();
            $tomorrow = now()->addDay()->toDateString();
            $inTwoDays = now()->addDays(2)->toDateString();
            $yesterday = now()->subDay()->toDateString();

            $samples = [
                [
                    'facility' => 'Pitch 1',
                    'date' => $today,
                    'start_time' => '09:00',
                    'duration_hours' => 3,
                    'member_id' => 'm1',
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'lane' => 'Lane 1',
                    'age_group' => 'Under 13',
                    'player_name' => 'Aarav K.',
                ],
                [
                    'facility' => 'Indoor Nets',
                    'date' => $today,
                    'start_time' => '14:00',
                    'duration_hours' => 2,
                    'member_id' => 'm2',
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'lane' => 'Lane 2',
                    'age_group' => 'Above 13',
                    'player_name' => 'Jasprit B.',
                ],
                [
                    'facility' => 'Bowling Machine',
                    'date' => $today,
                    'start_time' => '16:30',
                    'duration_hours' => 1,
                    'member_id' => 'm1',
                    'status' => 'PENDING',
                    'payment_status' => 'UNPAID',
                    'lane' => 'Lane 1',
                    'age_group' => 'Under 13',
                    'player_name' => 'Rohan S.',
                ],
                [
                    'facility' => 'Pitch 2',
                    'date' => $tomorrow,
                    'start_time' => '10:00',
                    'duration_hours' => 4,
                    'member_id' => 'm3',
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'lane' => 'Lane 2',
                    'age_group' => 'Above 13',
                    'player_name' => 'Local CC XI',
                ],
                [
                    'facility' => 'Indoor Nets',
                    'date' => $inTwoDays,
                    'start_time' => '11:00',
                    'duration_hours' => 2,
                    'member_id' => 'm1',
                    'status' => 'CONFIRMED',
                    'payment_status' => 'PAID',
                    'lane' => 'Lane 1',
                    'age_group' => 'Under 13',
                    'player_name' => 'Virat K.',
                ],
                [
                    'facility' => 'Pitch 1',
                    'date' => $yesterday,
                    'start_time' => '15:00',
                    'duration_hours' => 2,
                    'member_id' => 'm2',
                    'status' => 'COMPLETED',
                    'payment_status' => 'PAID',
                    'lane' => 'Lane 2',
                    'age_group' => 'Above 13',
                    'player_name' => 'Jasprit B.',
                ],
            ];

            foreach ($samples as $sample) {
                Booking::create($sample);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to seed initial sample bookings', ['error' => $e->getMessage()]);
        }
    }

    private function ensureBookingsTable(): bool
    {
        if (Schema::hasTable('bookings')) {
            return true;
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Throwable $e) {
            Log::error('Failed to auto-run migrations for bookings', ['error' => $e->getMessage()]);
            return false;
        }

        return Schema::hasTable('bookings');
    }

    private function buildFallbackBooking(array $payload): array
    {
        $memberId = $payload['member_id'] ?? 'guest';

        return [
            'id' => uniqid('b'),
            'memberId' => $memberId,
            'member_id' => $memberId,
            'facility' => $payload['facility'] ?? '',
            'date' => $payload['date'] ?? now()->toDateString(),
            'startTime' => $payload['start_time'] ?? '',
            'start_time' => $payload['start_time'] ?? '',
            'durationHours' => (int) ($payload['duration_hours'] ?? 1),
            'duration_hours' => (int) ($payload['duration_hours'] ?? 1),
            'status' => 'PENDING',
            'payment_status' => 'UNPAID',
            'lane' => $payload['lane'] ?? null,
            'age_group' => $payload['age_group'] ?? null,
            'player_name' => $payload['player_name'] ?? null,
            'rate' => (float) ($payload['rate'] ?? Booking::calculateRate($payload['facility'] ?? '', $payload['age_group'] ?? null)),
            'amount' => (float) ($payload['amount'] ?? ((float)($payload['duration_hours'] ?? 1) * 350.00)),
        ];
    }
}
