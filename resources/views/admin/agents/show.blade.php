<x-app-layout>
<div x-data="{ tab: 'overview' }" class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.agents.index') }}" class="text-gray-400 hover:text-brand-dark transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-brand-dark">{{ $agent->user->name }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ $agent->user->email }}@if($agent->company_name) &mdash; {{ $agent->company_name }}@endif</p>
        </div>
        <span class="text-sm px-3 py-1 rounded-full font-semibold
            {{ $agent->status === 'active' ? 'bg-green-100 text-green-700' :
               ($agent->status === 'pending' ? 'bg-amber-100 text-amber-700' :
               ($agent->status === 'banned' ? 'bg-gray-200 text-gray-700' : 'bg-red-100 text-red-600')) }}">
            {{ ucfirst($agent->status) }}
        </span>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Quick action bar --}}
    <div class="flex flex-wrap gap-2">
        @if($agent->status === 'pending')
        <form method="POST" action="{{ route('admin.agents.approve', $agent) }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Approve Agent
            </button>
        </form>
        <form method="POST" action="{{ route('admin.agents.reject', $agent) }}" onsubmit="return confirm('Reject this agent?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-white border border-red-300 text-red-600 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-red-50 transition">
                Reject
            </button>
        </form>
        @elseif($agent->status === 'active')
        <form method="POST" action="{{ route('admin.agents.suspend', $agent) }}" onsubmit="return confirm('Suspend {{ addslashes($agent->user->name) }}?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-orange-100 text-orange-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-orange-200 transition">
                Suspend
            </button>
        </form>
        <form method="POST" action="{{ route('admin.agents.ban', $agent) }}" onsubmit="return confirm('Ban {{ addslashes($agent->user->name) }}? This is permanent.')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-red-200 transition">
                Ban Agent
            </button>
        </form>
        @else
        <form method="POST" action="{{ route('admin.agents.restore', $agent) }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Restore Access
            </button>
        </form>
        @endif
        <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" onsubmit="return confirm('Permanently delete this agent?')">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-500 text-sm font-medium px-4 py-2 rounded-lg hover:border-red-300 hover:text-red-500 transition">
                Delete Agent
            </button>
        </form>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-1 -mb-px">
            @foreach([['key'=>'overview','label'=>'Overview'],['key'=>'bookings','label'=>'Bookings ('.$agent->bookings->count().')'],['key'=>'earnings','label'=>'Earnings']] as $t)
            <button @click="tab = '{{ $t['key'] }}'" type="button"
                    :class="tab === '{{ $t['key'] }}' ? 'border-brand-gold text-brand-dark font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-3 text-sm border-b-2 transition whitespace-nowrap">
                {{ $t['label'] }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- ═══ OVERVIEW TAB ═══ --}}
    <div x-show="tab === 'overview'" class="space-y-5">
        {{-- Info Card --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <h3 class="font-semibold text-brand-dark mb-4">Agent Details</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-gray-400 mb-0.5">Full Name</p><p class="font-medium text-brand-dark">{{ $agent->user->name }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Email</p><p class="font-medium text-brand-dark">{{ $agent->user->email }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Company</p><p class="font-medium text-brand-dark">{{ $agent->company_name ?? '—' }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Country</p><p class="font-medium text-brand-dark">{{ $agent->country ?? '—' }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Phone</p><p class="font-medium text-brand-dark">{{ $agent->phone ?? '—' }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Joined</p><p class="font-medium text-brand-dark">{{ $agent->created_at->format('d M Y') }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Total Bookings</p><p class="font-bold text-brand-dark">{{ $agent->bookings->count() }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Total Revenue</p><p class="font-bold text-green-600">${{ number_format($earningStats['revenue'], 0) }}</p></div>
                <div><p class="text-gray-400 mb-0.5">Commission Earned</p><p class="font-bold text-emerald-600">${{ number_format($earningStats['total'], 0) }}</p></div>
            </div>
        </div>

        {{-- Settings Form --}}
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <h3 class="font-semibold text-brand-dark mb-4">Commission & Status</h3>
            <form method="POST" action="{{ route('admin.agents.update', $agent) }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                            @foreach(['pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended', 'banned' => 'Banned'] as $val => $label)
                            <option value="{{ $val }}" {{ $agent->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%)</label>
                        <input type="number" name="commission_rate" value="{{ $agent->commission_rate }}" min="0" max="100" step="0.5"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                    </div>
                </div>
                <div>
                    <button type="submit" class="bg-brand-gold text-brand-dark font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-brand-gold/90 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══ BOOKINGS TAB ═══ --}}
    <div x-show="tab === 'bookings'">
        <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-brand-dark">Bookings</h3>
                <span class="text-sm text-gray-400">{{ $agent->bookings->count() }} total</span>
            </div>
            @if($agent->bookings->isEmpty())
            <p class="px-6 py-8 text-center text-sm text-gray-400">No bookings yet.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Safari</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Travel Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Commission</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($agent->bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5 font-medium text-brand-dark">{{ $booking->client_name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $booking->safari->title ?? '(Custom)' }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $booking->travel_date->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 font-bold text-brand-dark">${{ number_format($booking->total_price, 0) }}</td>
                            <td class="px-4 py-3.5 font-semibold text-green-600">${{ number_format($booking->commission_amount, 0) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                       ($booking->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ═══ EARNINGS TAB ═══ --}}
    <div x-show="tab === 'earnings'" class="space-y-5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Total Revenue',     'value' => '$'.number_format($earningStats['revenue'],0),   'color'=>'text-brand-dark'],
                ['label' => 'Total Commission',   'value' => '$'.number_format($earningStats['total'],0),     'color'=>'text-emerald-600'],
                ['label' => 'Confirmed Earnings', 'value' => '$'.number_format($earningStats['confirmed'],0), 'color'=>'text-green-600'],
                ['label' => 'Pending Earnings',   'value' => '$'.number_format($earningStats['pending'],0),   'color'=>'text-amber-600'],
            ] as $stat)
            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">{{ $stat['label'] }}</p>
                <p class="text-xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-black/5 p-6 text-sm text-gray-500">
            Commission rate: <span class="font-bold text-brand-dark">{{ $agent->commission_rate }}%</span> of total booking value.
            To change the rate, use the Overview tab.
        </div>
    </div>

</div>
</x-app-layout>