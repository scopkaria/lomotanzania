@extends('layouts.agent')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome --}}
    <div>
        <h2 class="text-2xl font-bold text-brand-dark">Welcome back, {{ Auth::user()->name }} 👋</h2>
        <p class="text-gray-500 text-sm mt-1">
            {{ $agent->company_name ?? 'Your Agent Portal' }} &mdash;
            Commission rate: <span class="font-semibold text-brand-dark">{{ $agent->commission_rate }}%</span>
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Total Bookings</p>
            <p class="text-2xl font-bold text-brand-dark">{{ $totalBookings }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Confirmed</p>
            <p class="text-2xl font-bold text-green-600">{{ $confirmedBookings }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Total Earnings</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalEarnings, 0) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5 {{ $pendingRequests > 0 ? 'border-amber-200 bg-amber-50' : '' }}">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Pending Requests</p>
            <p class="text-2xl font-bold text-amber-600">{{ $pendingRequests }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5 {{ $pendingResponses > 0 ? 'border-brand-gold bg-brand-gold/5' : '' }}">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">New Proposals</p>
            <p class="text-2xl font-bold text-brand-dark">{{ $pendingResponses }}</p>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="bg-brand-dark rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-white font-semibold text-lg">Submit a Custom Safari Request</h3>
            <p class="text-white/50 text-sm mt-0.5">Submit client preferences and admin will build a custom proposal with pricing.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @if($pendingResponses > 0)
            <a href="{{ route('agent.responses.index') }}"
               class="bg-brand-gold text-brand-dark font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-brand-gold/90 transition relative">
                View Proposals
                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-xs font-bold flex items-center justify-center rounded-full">{{ $pendingResponses }}</span>
            </a>
            @endif
            <a href="{{ route('agent.requests.create') }}"
               class="bg-white text-brand-dark font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-white/90 transition">
                + Request Safari
            </a>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-white rounded-2xl border border-black/5">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h3 class="font-semibold text-brand-dark">Recent Bookings</h3>
            <a href="{{ route('agent.bookings.index') }}" class="text-xs text-brand-gold font-semibold hover:underline">View all →</a>
        </div>

        @if($recentBookings->isEmpty())
            <div class="px-6 py-12 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">No bookings yet. <a href="{{ route('agent.bookings.create') }}" class="text-brand-gold font-medium">Create your first one!</a></p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentBookings as $booking)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition group">
                    <div class="w-9 h-9 rounded-full bg-brand-light flex items-center justify-center shrink-0">
                        <span class="text-brand-dark font-bold text-xs">{{ strtoupper(substr($booking->client_name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-brand-dark truncate">{{ $booking->client_name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $booking->safari->title ?? '—' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-semibold text-brand-dark">${{ number_format($booking->total_price, 0) }}</p>
                        <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                               ($booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <a href="{{ route('agent.bookings.show', $booking) }}" class="text-gray-300 group-hover:text-gray-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
