@extends('layouts.agent')
@section('page-title', 'Booking Details')

@section('content')
<div class="max-w-2xl">

    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('agent.bookings.index') }}" class="text-gray-400 hover:text-brand-dark transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-xl font-bold text-brand-dark">Booking #{{ $booking->id }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">Created {{ $booking->created_at->format('d M Y') }}</p>
        </div>
        <span class="ml-auto inline-block text-sm px-3 py-1 rounded-full font-semibold
            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
               ($booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <div class="space-y-5">

        {{-- Safari --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Safari Package</p>
            <p class="text-base font-semibold text-brand-dark">{{ $booking->safari->title ?? '—' }}</p>
            @if($booking->safari?->duration)
                <p class="text-sm text-gray-500 mt-0.5">Duration: {{ $booking->safari->duration }}</p>
            @endif
        </div>

        {{-- Client --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Client Details</p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-400 mb-0.5">Name</p><p class="font-medium text-brand-dark">{{ $booking->client_name }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Email</p><p class="font-medium text-brand-dark">{{ $booking->client_email }}</p></div>
                @if($booking->client_phone)
                <div><p class="text-gray-400 mb-0.5">Phone</p><p class="font-medium text-brand-dark">{{ $booking->client_phone }}</p></div>
                @endif
            </div>
        </div>

        {{-- Travel --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Travel Details</p>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-400 mb-0.5">Travel Date</p><p class="font-medium text-brand-dark">{{ $booking->travel_date->format('d M Y') }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Number of People</p><p class="font-medium text-brand-dark">{{ $booking->num_people }}</p></div>
            </div>
        </div>

        {{-- Financials --}}
        <div class="bg-brand-light rounded-2xl border border-black/5 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Financials</p>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Price</span>
                    <span class="font-bold text-brand-dark text-base">${{ number_format($booking->total_price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Your Commission</span>
                    <span class="font-semibold text-green-600">${{ number_format($booking->commission_amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if($booking->notes)
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Notes</p>
            <p class="text-sm text-gray-700">{{ $booking->notes }}</p>
        </div>
        @endif

    </div>
</div>
@endsection
