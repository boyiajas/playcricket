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

    public function __construct(PayFastService $payFastService)
    {
        $this->payFastService = $payFastService;
    }

    public function index()
    {
        if (!$this->ensureBookingsTable()) {
            Log::warning('Bookings table missing, falling back to config data');
            return response()->json(config('appdata.bookings'));
        }

        return response()->json(Booking::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'memberId' => 'nullable|string', // Changed to nullable based on migration
            'facility' => 'required|string',
            'date' => 'required|date',
            'startTime' => 'required|string',
            'durationHours' => 'required|numeric',
            'status' => 'nullable|string',
            'payNow' => 'boolean' // New field
        ]);

        $bookingPayload = [
            'member_id' => $data['memberId'] ?? 'guest',
            'facility' => $data['facility'],
            'date' => $data['date'],
            'start_time' => $data['startTime'],
            'duration_hours' => $data['durationHours'],
            'status' => 'PENDING',
            'payment_status' => 'UNPAID'
        ];

        if (!$this->ensureBookingsTable()) {
            $booking = $this->buildFallbackBooking($bookingPayload);

            return response()->json([
                'booking' => $booking,
                'message' => 'Booking created successfully (non-persistent fallback)'
            ], 201);
        }

        $booking = Booking::create($bookingPayload);

        $response = [
            'booking' => $booking,
            'message' => 'Booking created successfully'
        ];

        if ($request->boolean('payNow')) {
            $paymentData = $this->payFastService->generatePaymentForm(
                $booking,
                route('payment.return'),
                route('payment.cancel'),
                route('payment.notify')
            );
            $response['payment_data'] = $paymentData;
        }

        return response()->json($response, 201);
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
        ];
    }
}
