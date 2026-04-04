@extends('layouts.agent')
@section('page-title', 'My Bookings')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-brand-dark">My Bookings</h2>
            <p class="text-sm text-gray-500 mt-0.5">Total commissions earned: <span class="font-semibold text-brand-dark">${{ number_format($totalEarnings, 2) }}</span></p>
        </div>
        <a href="{{ route('agent.bookings.create') }}"
           class="bg-brand-gold text-brand-dark text-sm font-bold px-5 py-2 rounded-xl hover:bg-brand-gold/90 transition">
            + New Booking
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        @if($bookings->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">No bookings yet. <a href="{{ route('agent.bookings.create') }}" class="text-brand-gold font-medium">Create your first one!</a></p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Safari</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Travel Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">People</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Commission</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5">
                                <p class="font-medium text-brand-dark">{{ $booking->client_name }}</p>
                                <p class="text-xs text-gray-400">{{ $booking->client_email }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-gray-700 max-w-[180px] truncate">{{ $booking->safari->title ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $booking->travel_date->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $booking->num_people }}</td>
                            <td class="px-4 py-3.5 font-semibold text-brand-dark">${{ number_format($booking->total_price, 0) }}</td>
                            <td class="px-4 py-3.5 font-semibold text-green-600">${{ number_format($booking->commission_amount, 0) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                       ($booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('agent.bookings.show', $booking) }}" class="text-gray-400 hover:text-brand-dark transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
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
