@extends('layouts.agent')
@section('page-title', 'My Earnings')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-xl font-bold text-brand-dark">My Earnings</h2>
        <p class="text-sm text-gray-500 mt-0.5">Track your commission income across all bookings.</p>
    </div>

    {{-- Earnings Overview --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-3">Total Earnings</p>
            <p class="text-3xl font-bold text-brand-dark">${{ number_format($totalEarnings, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">All active bookings</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-3">Confirmed Earnings</p>
            <p class="text-3xl font-bold text-green-600">${{ number_format($confirmedEarnings, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">From confirmed bookings</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-3">Pending Earnings</p>
            <p class="text-3xl font-bold text-amber-500">${{ number_format($pendingEarnings, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Awaiting confirmation</p>
        </div>
    </div>

    {{-- Earnings Table --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="font-semibold text-brand-dark">Commission Breakdown</h3>
        </div>
        @if($bookings->isEmpty())
            <div class="py-12 text-center text-gray-400 text-sm">No earnings yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Safari</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Travel Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Price</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Commission</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5 font-medium text-brand-dark">{{ $booking->client_name }}</td>
                            <td class="px-4 py-3.5 text-gray-600 max-w-[160px] truncate">{{ $booking->safari->title ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $booking->travel_date->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 font-semibold text-brand-dark">${{ number_format($booking->total_price, 0) }}</td>
                            <td class="px-4 py-3.5 font-semibold text-green-600">${{ number_format($booking->commission_amount, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                       ($booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $bookings->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
