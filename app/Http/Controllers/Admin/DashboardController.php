<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Inquiry;
use App\Models\SafariPackage;
use App\Models\Testimonial;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'safaris'              => SafariPackage::count(),
            'published_safaris'    => SafariPackage::where('status', 'published')->count(),
            'destinations'         => Destination::count(),
            'countries'            => Country::count(),
            'testimonials'         => Testimonial::count(),
            'pending_testimonials' => Testimonial::where('approved', false)->count(),
            'users'                => User::count(),
            'bookings'             => Booking::count(),
            'total_revenue'        => Booking::whereIn('status', ['pending', 'confirmed'])->sum('total_price'),
            'agents'               => Agent::count(),
            'active_agents'        => Agent::where('status', 'active')->count(),
            'inquiries'            => Inquiry::count(),
            'new_inquiries'        => Inquiry::where('status', 'new')->count(),
        ];

        $recentSafaris = SafariPackage::with('destinations')
            ->latest()
            ->limit(5)
            ->get();

        $recentBookings = Booking::with(['agent.user', 'safari'])
            ->latest()
            ->limit(5)
            ->get();

        $recentInquiries = Inquiry::with('safariPackage')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSafaris', 'recentBookings', 'recentInquiries'));
    }
}
