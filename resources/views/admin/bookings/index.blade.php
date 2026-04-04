<x-app-layout>
<div x-data="adminTable({ ids: [{{ $bookings->pluck('id')->join(',') }}], key: 'bookings', columns: { agent: true, safari: true, travel: true, people: true, total: true, commission: true } })">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">All Bookings</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                Revenue: <span class="font-semibold text-gray-900">${{ number_format($totalRevenue, 0) }}</span>
                &bull;
                Commission: <span class="font-semibold text-green-600">${{ number_format($totalCommission, 0) }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" class="flex items-center">
                @foreach(request()->except(['search', 'page']) as $k => $v)
                    @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                @endforeach
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name or email..."
                           class="pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg w-64 focus:ring-2 focus:ring-[#083321]/20 focus:border-[#083321]">
                </div>
            </form>
            <form method="GET">
                @foreach(request()->except(['status', 'page']) as $k => $v)
                    @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                @endforeach
                <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'confirmed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </form>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.bookings.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Clear</a>
            @endif
        </div>
        <form method="GET">
            @foreach(request()->except(['per_page', 'page']) as $k => $v)
                @if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
            @endforeach
            <select name="per_page" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#083321]/20">
                @foreach([10, 25, 50, 100] as $pp)
                    <option value="{{ $pp }}" {{ (int)request('per_page', 15) === $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 admin-table">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="w-10 px-4 py-3"><input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th x-show="isVisible('agent')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                        <th x-show="isVisible('safari')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Safari</th>
                        <th x-show="isVisible('travel')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Travel Date</th>
                        <th x-show="isVisible('people')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">People</th>
                        <th x-show="isVisible('total')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th x-show="isVisible('commission')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-[#F9F7F3]/60 transition-colors" :class="isSelected({{ $booking->id }}) && 'bg-[#083321]/5'">
                        <td class="w-10 px-4 py-3"><input type="checkbox" @click="toggleRow({{ $booking->id }})" :checked="isSelected({{ $booking->id }})" class="rounded border-gray-300 text-[#083321] focus:ring-[#083321]"></td>
                        <td class="px-4 py-3 text-sm text-gray-400">#{{ $booking->id }}</td>
                        <td x-show="isVisible('agent')" class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $booking->agent->user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->agent->company_name ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $booking->client_name }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->client_email }}</p>
                        </td>
                        <td x-show="isVisible('safari')" class="px-4 py-3 text-sm text-gray-600 max-w-[160px] truncate">{{ $booking->safari->title ?? '—' }}</td>
                        <td x-show="isVisible('travel')" class="px-4 py-3 text-sm text-gray-600">{{ $booking->travel_date->format('d M Y') }}</td>
                        <td x-show="isVisible('people')" class="px-4 py-3 text-sm text-gray-600">{{ $booking->num_people }}</td>
                        <td x-show="isVisible('total')" class="px-4 py-3 text-sm font-semibold text-gray-900">${{ number_format($booking->total_price, 0) }}</td>
                        <td x-show="isVisible('commission')" class="px-4 py-3 text-sm font-semibold text-green-600">${{ number_format($booking->commission_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-gray-400 hover:text-[#083321] transition" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="11" class="px-4 py-12 text-center text-gray-400 text-sm">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($bookings->hasPages())<div class="mt-4">{{ $bookings->links() }}</div>@endif

    @include('admin.partials.bulk-bar', ['bulkRoute' => route('admin.bookings.bulk-action'), 'actions' => ['confirm' => 'Confirm', 'cancel' => 'Cancel', 'delete' => 'Delete']])
</div>
</x-app-layout>
