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
        if (!\Illuminate\Support\Facades\Schema::hasTable('bookings')) {
            return response()->json([
                'daily' => ['bookings' => 0, 'revenue' => 0],
                'weekly' => ['bookings' => 0, 'revenue' => 0],
                'monthly' => ['bookings' => 0, 'revenue' => 0],
                'total_bookings' => 0,
                'active_pitches' => 0,
            ]);
        }

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Daily Stats (Today, excluding cancelled)
        $daily = Booking::whereDate('date', $today)
            ->where('status', '!=', 'CANCELLED')
            ->selectRaw('count(*) as total_bookings, COALESCE(sum(COALESCE(amount, duration_hours * 350)), 0) as revenue')
            ->first();

        // Weekly Stats (This Week, excluding cancelled)
        $weekly = Booking::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where('status', '!=', 'CANCELLED')
            ->selectRaw('count(*) as total_bookings, COALESCE(sum(COALESCE(amount, duration_hours * 350)), 0) as revenue')
            ->first();

        // Monthly Stats (This Month, excluding cancelled)
        $monthly = Booking::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', '!=', 'CANCELLED')
            ->selectRaw('count(*) as total_bookings, COALESCE(sum(COALESCE(amount, duration_hours * 350)), 0) as revenue')
            ->first();

        // Active Pitches / Facilities Booked Today
        $activePitches = Booking::whereDate('date', $today)
            ->where('status', '!=', 'CANCELLED')
            ->distinct('facility')
            ->count('facility');

        $totalBookings = Booking::where('status', '!=', 'CANCELLED')->count();
        $confirmedBookings = Booking::where('status', 'CONFIRMED')->count();
        $pendingBookings = Booking::where('status', 'PENDING')->count();

        return response()->json([
            'daily' => [
                'bookings' => (int)($daily->total_bookings ?? 0),
                'revenue' => (float)($daily->revenue ?? 0),
            ],
            'weekly' => [
                'bookings' => (int)($weekly->total_bookings ?? 0),
                'revenue' => (float)($weekly->revenue ?? 0),
            ],
            'monthly' => [
                'bookings' => (int)($monthly->total_bookings ?? 0),
                'revenue' => (float)($monthly->revenue ?? 0),
            ],
            'total_bookings' => $totalBookings,
            'confirmed_bookings' => $confirmedBookings,
            'pending_bookings' => $pendingBookings,
            'active_pitches' => $activePitches,
        ]);
    }
}
