<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Booking;

class BookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_payment_flow()
    {
        // 1. Create Booking with PayNow
        $response = $this->postJson('/api/bookings', [
            'facility' => 'Test Pitch',
            'date' => '2023-11-01',
            'startTime' => '10:00',
            'durationHours' => 1,
            'payNow' => true,
            'memberId' => 'test_member'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'booking' => ['id', 'facility', 'status', 'payment_status'],
                     'payment_data' => ['url', 'data']
                 ]);

        $bookingId = $response->json('booking.id');
        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'payment_status' => 'UNPAID'
        ]);

        // 2. Simulate PayFast ITN
        $notifyResponse = $this->post('/api/payment/notify', [
            'm_payment_id' => $bookingId,
            'payment_status' => 'COMPLETE',
            'pf_payment_id' => 'pf_123456',
        ]);

        $notifyResponse->assertStatus(200);

        $this->assertDatabaseHas('bookings', [
            'id' => $bookingId,
            'status' => 'CONFIRMED',
            'payment_status' => 'PAID',
            'payment_id' => 'pf_123456'
        ]);
    }
}
