<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(config('appdata.bookings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'memberId' => 'required|string',
            'facility' => 'required|string',
            'date' => 'required|date',
            'startTime' => 'required|string',
            'durationHours' => 'required|numeric',
            'status' => 'nullable|string',
        ]);

        $booking = array_merge([
            'id' => uniqid('b'),
            'syncedToGoogle' => false,
        ], $data);

        // In-memory response only. Replace with persistence when DB is connected.
        return response()->json($booking, 201);
    }

    public function byMember(string $memberId)
    {
        $bookings = collect(config('appdata.bookings'))->where('memberId', $memberId)->values();
        return response()->json($bookings);
    }
}
