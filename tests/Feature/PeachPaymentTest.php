<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeachPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_peach_checkout_session_creation_for_lane_hire(): void
    {
        $response = $this->postJson('/payments/checkout', [
            'facility' => 'Lane 1 Hire',
            'date' => '2026-10-25',
            'startTime' => '10:00',
            'durationHours' => 1,
            'playerName' => 'AB de Villiers',
            'email' => 'ab@proteas.co.za',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'amount' => 350.00,
                'currency' => 'ZAR',
            ]);

        $data = $response->json();
        $this->assertNotEmpty($data['checkout_id']);
        $this->assertNotEmpty($data['redirect_url']);
        $this->assertStringContainsString('peachpayments', $data['redirect_url']);

        $this->assertDatabaseHas('payments', [
            'amount' => 350.00,
            'currency' => 'ZAR',
            'status' => 'pending',
            'gateway' => 'peach_payments',
        ]);
    }

    public function test_peach_checkout_session_creation_for_junior_and_senior_coaching(): void
    {
        // Junior Coaching: R450
        $juniorRes = $this->postJson('/payments/checkout', [
            'facility' => 'Junior Coaching Academy',
            'date' => '2026-10-26',
            'startTime' => '09:00',
            'durationHours' => 1,
            'ageGroup' => 'Under 13',
            'playerName' => 'Junior Player',
        ]);

        $juniorRes->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'amount' => 450.00,
            ]);

        // Senior Coaching: R600
        $seniorRes = $this->postJson('/payments/checkout', [
            'facility' => 'Senior Private Coaching',
            'date' => '2026-10-26',
            'startTime' => '11:00',
            'durationHours' => 1,
            'ageGroup' => 'Above 13',
            'playerName' => 'Senior Batsman',
        ]);

        $seniorRes->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'amount' => 600.00,
            ]);
    }

    public function test_peach_webhook_with_signature_confirms_booking_and_payment(): void
    {
        $booking = Booking::create([
            'facility' => 'Senior Private Coaching',
            'date' => '2026-10-28',
            'start_time' => '14:00',
            'duration_hours' => 1,
            'rate' => 600.00,
            'amount' => 600.00,
            'status' => 'PENDING',
            'payment_status' => 'UNPAID',
            'member_id' => 'guest',
        ]);

        $checkoutId = 'pch_chk_' . uniqid();
        $reference = 'PC-PAY-' . $booking->id . '-TEST01';

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payable_type' => Booking::class,
            'payable_id' => $booking->id,
            'payment_reference' => $reference,
            'peach_checkout_id' => $checkoutId,
            'amount' => 600.00,
            'currency' => 'ZAR',
            'status' => 'pending',
            'gateway' => 'peach_payments',
        ]);

        $webhookPayload = [
            'id' => $checkoutId,
            'checkoutId' => $checkoutId,
            'merchantTransactionId' => $reference,
            'amount' => '600.00',
            'currency' => 'ZAR',
            'status' => 'successful',
            'paymentBrand' => 'VISA',
            'result' => [
                'code' => '000.000.000',
                'description' => 'Transaction succeeded',
            ],
        ];

        $rawBody = json_encode($webhookPayload);
        $secret = config('peach.webhook_secret', 'playcricket_peach_webhook_secret');
        $signature = 'sha256=' . hash_hmac('sha256', $rawBody, $secret);

        $response = $this->call(
            'POST',
            '/webhooks/peach',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_SIGNATURE' => $signature,
            ],
            $rawBody
        );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Payment verified and booking confirmed',
            ]);

        // Assert payment is completed
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
            'payment_method' => 'VISA',
        ]);

        // Assert booking is confirmed and paid
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
            'payment_status' => 'PAID',
            'payment_id' => $checkoutId,
        ]);
    }

    public function test_peach_webhook_idempotency_prevents_duplicate_actions(): void
    {
        $booking = Booking::create([
            'facility' => 'Lane 2 Hire',
            'date' => '2026-10-29',
            'start_time' => '16:00',
            'duration_hours' => 1,
            'rate' => 350.00,
            'amount' => 350.00,
            'status' => 'CONFIRMED',
            'payment_status' => 'PAID',
        ]);

        $checkoutId = 'pch_chk_' . uniqid();
        $reference = 'PC-PAY-' . $booking->id . '-DUP01';

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_reference' => $reference,
            'peach_checkout_id' => $checkoutId,
            'amount' => 350.00,
            'currency' => 'ZAR',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $webhookPayload = [
            'checkoutId' => $checkoutId,
            'merchantTransactionId' => $reference,
            'amount' => '350.00',
            'currency' => 'ZAR',
            'status' => 'successful',
        ];

        $rawBody = json_encode($webhookPayload);
        $secret = config('peach.webhook_secret', 'playcricket_peach_webhook_secret');
        $signature = 'sha256=' . hash_hmac('sha256', $rawBody, $secret);

        $response = $this->call(
            'POST',
            '/webhooks/peach',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_SIGNATURE' => $signature,
            ],
            $rawBody
        );

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'already_processed',
            ]);
    }

    public function test_peach_webhook_rejects_invalid_signature(): void
    {
        $payload = json_encode(['checkoutId' => 'pch_12345']);

        // Set environment to production to strictly test rejection of forged signatures
        config(['peach.environment' => 'production']);

        $response = $this->call(
            'POST',
            '/webhooks/peach',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_SIGNATURE' => 'sha256=invalid_tampered_signature',
            ],
            $payload
        );

        $response->assertStatus(401);
    }

    public function test_customer_return_flow_redirect(): void
    {
        $booking = Booking::create([
            'facility' => 'Indoor Nets',
            'date' => '2026-10-30',
            'start_time' => '10:00',
            'duration_hours' => 1,
            'rate' => 350.00,
            'amount' => 350.00,
            'status' => 'PENDING',
            'payment_status' => 'UNPAID',
        ]);

        $checkoutId = 'pch_chk_ret_' . uniqid();
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_reference' => 'PC-RET-01',
            'peach_checkout_id' => $checkoutId,
            'amount' => 350.00,
            'currency' => 'ZAR',
            'status' => 'pending',
        ]);

        $response = $this->get('/payments/result?id=' . $checkoutId);

        $response->assertStatus(302)
            ->assertRedirect('/?payment=success&booking_id=' . $booking->id);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'CONFIRMED',
            'payment_status' => 'PAID',
        ]);
    }

    public function test_customer_cancel_flow_redirect(): void
    {
        $payment = Payment::create([
            'payment_reference' => 'PC-CANCEL-01',
            'amount' => 350.00,
            'currency' => 'ZAR',
            'status' => 'pending',
        ]);

        $response = $this->get('/payments/cancel?reference=PC-CANCEL-01');

        $response->assertStatus(302)
            ->assertRedirect('/?payment=cancelled');

        $this->assertDatabaseHas('payments', [
            'payment_reference' => 'PC-CANCEL-01',
            'status' => 'cancelled',
        ]);
    }

    public function test_guest_can_book_and_checkout_without_account(): void
    {
        $response = $this->postJson('/api/bookings', [
            'isGuest' => true,
            'guestName' => 'Graeme Smith',
            'guestEmail' => 'graeme@cricketsa.co.za',
            'guestPhone' => '+27821234567',
            'facility' => 'Lane 1 Hire',
            'date' => '2026-11-05',
            'startTime' => '15:00',
            'durationHours' => 2,
            'payNow' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Booking created successfully',
            ]);

        $this->assertDatabaseHas('bookings', [
            'member_id' => 'guest',
            'is_guest' => true,
            'guest_name' => 'Graeme Smith',
            'guest_email' => 'graeme@cricketsa.co.za',
            'guest_phone' => '+27821234567',
            'rate' => 350.00,
            'amount' => 700.00,
            'status' => 'PENDING',
            'payment_status' => 'UNPAID',
        ]);

        $data = $response->json();
        $this->assertNotEmpty($data['peach_checkout']['checkout_id']);
        $this->assertNotEmpty($data['peach_checkout']['redirect_url']);
        $this->assertEquals(700.00, $data['peach_checkout']['amount']);

        $this->assertDatabaseHas('payments', [
            'amount' => 700.00,
            'currency' => 'ZAR',
            'status' => 'pending',
            'gateway' => 'peach_payments',
        ]);
    }
}
