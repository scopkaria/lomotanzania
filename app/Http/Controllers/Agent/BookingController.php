<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SafariPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;
        $bookings = $agent->bookings()->with('safari')->latest()->paginate(15);

        $totalEarnings = $agent->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('commission_amount');

        return view('agent.bookings.index', compact('bookings', 'totalEarnings'));
    }

    public function create()
    {
        $safaris = SafariPackage::where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title', 'price', 'duration']);

        return view('agent.bookings.create', compact('safaris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'safari_package_id' => ['required', 'integer', 'exists:safari_packages,id'],
            'client_name'       => ['required', 'string', 'max:255'],
            'client_email'      => ['required', 'email', 'max:255'],
            'client_phone'      => ['nullable', 'string', 'max:50'],
            'travel_date'       => ['required', 'date', 'after:today'],
            'num_people'        => ['required', 'integer', 'min:1', 'max:100'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ]);

        $agent = Auth::user()->agent;
        $safari = SafariPackage::findOrFail($validated['safari_package_id']);

        $totalPrice = (float) $safari->price * (int) $validated['num_people'];
        $commissionAmount = $totalPrice * ($agent->commission_rate / 100);

        $booking = Booking::create([
            'agent_id'          => $agent->id,
            'safari_package_id' => $validated['safari_package_id'],
            'client_name'       => $validated['client_name'],
            'client_email'      => $validated['client_email'],
            'client_phone'      => $validated['client_phone'] ?? null,
            'travel_date'       => $validated['travel_date'],
            'num_people'        => $validated['num_people'],
            'total_price'       => $totalPrice,
            'commission_amount' => $commissionAmount,
            'notes'             => $validated['notes'] ?? null,
            'status'            => 'pending',
        ]);

        return redirect()->route('agent.bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        $agent = Auth::user()->agent;

        abort_unless($booking->agent_id === $agent->id, 403);

        $booking->load('safari');

        return view('agent.bookings.show', compact('booking'));
    }

    public function earnings()
    {
        $agent = Auth::user()->agent;

        $bookings = $agent->bookings()
            ->with('safari')
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->paginate(15);

        $totalEarnings = $agent->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('commission_amount');

        $confirmedEarnings = $agent->bookings()
            ->where('status', 'confirmed')
            ->sum('commission_amount');

        $pendingEarnings = $agent->bookings()
            ->where('status', 'pending')
            ->sum('commission_amount');

        return view('agent.earnings', compact(
            'bookings',
            'totalEarnings',
            'confirmedEarnings',
            'pendingEarnings',
        ));
    }
}
