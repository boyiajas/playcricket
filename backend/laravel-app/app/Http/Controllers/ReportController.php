<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Daily Stats (Today)
        $daily = Booking::whereDate('date', Carbon::today())
            ->selectRaw('count(*) as total_bookings, sum(duration_hours * 100) as revenue') // Assuming 100 per hour
            ->first();

        // Weekly Stats (This Week)
        $weekly = Booking::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->selectRaw('count(*) as total_bookings, sum(duration_hours * 100) as revenue')
            ->first();

        // Monthly Stats (This Month)
        $monthly = Booking::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->selectRaw('count(*) as total_bookings, sum(duration_hours * 100) as revenue')
            ->first();

        return response()->json([
            'daily' => [
                'bookings' => $daily->total_bookings ?? 0,
                'revenue' => $daily->revenue ?? 0
            ],
            'weekly' => [
                'bookings' => $weekly->total_bookings ?? 0,
                'revenue' => $weekly->revenue ?? 0
            ],
            'monthly' => [
                'bookings' => $monthly->total_bookings ?? 0,
                'revenue' => $monthly->revenue ?? 0
            ]
        ]);
    }
}
