<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome + Quick Actions --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Here's what's happening with your safari business today.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.safaris.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#083321] text-white text-sm font-semibold rounded-lg hover:bg-[#0a4a30] transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Safari
            </a>
            <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Destination
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        {{-- Total Safaris --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-[#FEBC11]/10 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['safaris'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Safari Packages</p>
            <p class="text-[11px] text-green-600 mt-0.5">{{ $stats['published_safaris'] }} published</p>
        </div>

        {{-- Bookings --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['bookings'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Bookings</p>
        </div>

        {{-- Revenue --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_revenue'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
        </div>

        {{-- Destinations --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-violet-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['destinations'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Destinations</p>
            <p class="text-[11px] text-gray-400 mt-0.5">{{ $stats['countries'] }} countries</p>
        </div>

        {{-- Agents --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['agents'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Agents</p>
            <p class="text-[11px] text-green-600 mt-0.5">{{ $stats['active_agents'] }} active</p>
        </div>

        {{-- Inquiries --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5-1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.981l7.5-4.039a2.25 2.25 0 012.134 0l7.5 4.039a2.25 2.25 0 011.183 1.98V15.75z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['inquiries'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Inquiries</p>
            @if($stats['new_inquiries'] > 0)
                <p class="text-[11px] text-red-500 font-medium mt-0.5">{{ $stats['new_inquiries'] }} new</p>
            @endif
        </div>
    </div>

    {{-- Two column layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Recent Bookings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">Recent Bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-medium text-[#FEBC11] hover:underline">View all &rarr;</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentBookings as $booking)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $booking->client_name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $booking->safari?->title ?? '—' }}</p>
                    </div>
                    <div class="text-right ml-4 shrink-0">
                        <p class="text-sm font-semibold text-gray-900">${{ number_format($booking->total_price, 0) }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                            {{ $booking->status === 'confirmed' ? 'bg-green-50 text-green-700' : ($booking->status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-sm text-gray-400">No bookings yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Inquiries --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-800">Recent Inquiries</h2>
                <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-medium text-[#FEBC11] hover:underline">View all &rarr;</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentInquiries as $inquiry)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $inquiry->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $inquiry->safariPackage?->title ?? 'General' }}</p>
                    </div>
                    <div class="text-right ml-4 shrink-0">
                        <p class="text-xs text-gray-500">{{ $inquiry->created_at->diffForHumans() }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                            {{ $inquiry->status === 'new' ? 'bg-blue-50 text-blue-700' : ($inquiry->status === 'booked' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($inquiry->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-sm text-gray-400">No inquiries yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Safari Packages --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800">Recent Safari Packages</h2>
            <a href="{{ route('admin.safaris.index') }}" class="text-xs font-medium text-[#FEBC11] hover:underline">View all &rarr;</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinations</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentSafaris as $safari)
                    @php
                        $seasonalValues = collect($safari->seasonal_pricing ?? [])
                            ->flatMap(fn ($season) => collect($season)->filter(fn ($value) => filled($value)));
                        $startingRate = $seasonalValues->isNotEmpty() ? (float) $seasonalValues->min() : null;
                    @endphp
                    <tr class="hover:bg-[#F9F7F3]/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($safari->featured_image)
                                    <img src="{{ asset('storage/' . $safari->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $safari->title }}</p>
                                    <p class="text-xs text-gray-400">{{ $safari->duration ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-500">{{ $safari->destinations->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-500">
                            @if($startingRate !== null)
                                {{ $safari->currency ?? 'USD' }} {{ number_format($startingRate, 0) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($safari->status === 'published')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.safaris.edit', $safari) }}" class="text-xs font-medium text-[#FEBC11] hover:text-yellow-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                            No safari packages yet.
                            <a href="{{ route('admin.safaris.create') }}" class="text-[#FEBC11] hover:underline ml-1">Create your first one</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-app-layout>
