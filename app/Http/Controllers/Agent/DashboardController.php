<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;

        $totalBookings    = $agent->bookings()->count();
        $totalEarnings    = $agent->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('commission_amount');
        $confirmedBookings = $agent->bookings()->where('status', 'confirmed')->count();
        $pendingRequests  = $agent->safariRequests()->where('status', 'new')->count();
        $pendingResponses = $agent->safariRequests()
            ->whereHas('response', fn($q) => $q->where('status', 'pending'))
            ->count();

        $recentBookings = $agent->bookings()
            ->with('safari')
            ->latest()
            ->take(5)
            ->get();

        return view('agent.dashboard', compact(
            'agent',
            'totalBookings',
            'totalEarnings',
            'confirmedBookings',
            'pendingRequests',
            'pendingResponses',
            'recentBookings',
        ));
    }
}
