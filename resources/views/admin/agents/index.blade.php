<x-app-layout>
<div x-data="{ tab: '{{ session('tab', 'dashboard') }}' }" class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-brand-dark">Agent Management</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage agents, commissions, and settings.</p>
        </div>
        @if($stats['pending'] > 0)
        <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1.5 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            {{ $stats['pending'] }} pending approval
        </span>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-1 -mb-px">
            @foreach([
                ['key' => 'dashboard', 'label' => 'Dashboard'],
                ['key' => 'all', 'label' => 'All Agents (' . $activeAgents->total() . ')'],
                ['key' => 'suspended', 'label' => 'Suspended / Banned (' . $suspendedAgents->total() . ')'],
                ['key' => 'pending', 'label' => 'Pending (' . $stats['pending'] . ')'],
                ['key' => 'settings', 'label' => 'Settings'],
            ] as $t)
            <button @click="tab = '{{ $t['key'] }}'" type="button"
                    :class="tab === '{{ $t['key'] }}' ? 'border-brand-gold text-brand-dark font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-3 text-sm border-b-2 transition whitespace-nowrap">
                {{ $t['label'] }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- ═══ DASHBOARD TAB ═══ --}}
    <div x-show="tab === 'dashboard'">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Total Agents',   'value' => $stats['total'],      'color' => 'bg-blue-50 text-blue-700'],
                ['label' => 'Active',          'value' => $stats['active'],     'color' => 'bg-green-50 text-green-700'],
                ['label' => 'Pending',         'value' => $stats['pending'],    'color' => 'bg-amber-50 text-amber-700'],
                ['label' => 'Suspended',       'value' => $stats['suspended'],  'color' => 'bg-red-50 text-red-600'],
                ['label' => 'Banned',          'value' => $stats['banned'],     'color' => 'bg-gray-100 text-gray-600'],
                ['label' => 'Total Bookings',  'value' => $stats['bookings'],   'color' => 'bg-purple-50 text-purple-700'],
                ['label' => 'Commission Paid', 'value' => '$' . number_format($stats['commission'], 0), 'color' => 'bg-emerald-50 text-emerald-700'],
            ] as $stat)
            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-2">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold {{ $stat['color'] }} inline-block px-2 py-0.5 rounded-lg">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Quick pending approvals --}}
        @if($pendingAgents->isNotEmpty())
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-amber-200">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                <h3 class="font-semibold text-amber-900">Pending Approvals ({{ $pendingAgents->count() }})</h3>
            </div>
            <div class="divide-y divide-amber-100">
                @foreach($pendingAgents as $agent)
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-amber-200 flex items-center justify-center shrink-0">
                                <span class="text-amber-800 font-bold text-xs">{{ strtoupper(substr($agent->user->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-brand-dark">{{ $agent->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $agent->user->email }} @if($agent->company_name)· {{ $agent->company_name }}@endif @if($agent->country)· {{ $agent->country }}@endif</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-12 sm:ml-0 shrink-0">
                        <form method="POST" action="{{ route('admin.agents.approve', $agent) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.agents.reject', $agent) }}" onsubmit="return confirm('Reject {{ addslashes($agent->user->name) }}?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-white border border-red-300 text-red-600 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-red-50 transition">
                                Reject
                            </button>
                        </form>
                        <a href="{{ route('admin.agents.show', $agent) }}" class="text-xs text-gray-400 hover:text-brand-dark underline underline-offset-2">Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="mt-6 bg-green-50 border border-green-200 rounded-2xl px-6 py-5 text-sm text-green-700 font-medium">
            ✓ No pending approvals.
        </div>
        @endif
    </div>

    {{-- ═══ ALL AGENTS TAB ═══ --}}
    <div x-show="tab === 'all'">
        <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Agent</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Country</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Bookings</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Earned</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Rate</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($activeAgents as $agent)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5">
                                <p class="font-medium text-brand-dark">{{ $agent->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $agent->user->email }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $agent->company_name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $agent->country ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $agent->bookings_count }}</td>
                            <td class="px-4 py-3.5 font-semibold text-green-600">${{ number_format($agent->bookings_sum_commission_amount ?? 0, 0) }}</td>
                            <td class="px-4 py-3.5 font-semibold text-brand-dark">{{ $agent->commission_rate }}%</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full font-medium bg-green-100 text-green-700">Active</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.agents.show', $agent) }}" class="text-xs text-gray-400 hover:text-brand-dark underline underline-offset-2">Profile</a>
                                    <form method="POST" action="{{ route('admin.agents.suspend', $agent) }}" onsubmit="return confirm('Suspend {{ addslashes($agent->user->name) }}?')">
                                        @csrf
                                        <button type="submit" class="text-xs text-orange-500 hover:text-orange-700 underline underline-offset-2">Suspend</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.agents.ban', $agent) }}" onsubmit="return confirm('Ban {{ addslashes($agent->user->name) }}? This will prevent all access.')">
                                        @csrf
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline underline-offset-2">Ban</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">No active agents yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activeAgents->hasPages())
            <div class="px-4 py-4 border-t border-gray-100">{{ $activeAgents->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ═══ SUSPENDED / BANNED TAB ═══ --}}
    <div x-show="tab === 'suspended'">
        <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Agent</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Company</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Bookings</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Earned</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($suspendedAgents as $agent)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3.5">
                                <p class="font-medium text-brand-dark">{{ $agent->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $agent->user->email }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $agent->company_name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-700">{{ $agent->bookings_count }}</td>
                            <td class="px-4 py-3.5 font-semibold text-green-600">${{ number_format($agent->bookings_sum_commission_amount ?? 0, 0) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $agent->status === 'banned' ? 'bg-gray-200 text-gray-700' : 'bg-red-100 text-red-600' }}">
                                    {{ ucfirst($agent->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.agents.show', $agent) }}" class="text-xs text-gray-400 hover:text-brand-dark underline underline-offset-2">Profile</a>
                                    <form method="POST" action="{{ route('admin.agents.restore', $agent) }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-green-600 hover:text-green-800 underline underline-offset-2">Restore</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($agent->user->name) }} permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline underline-offset-2">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">No suspended or banned agents.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($suspendedAgents->hasPages())
            <div class="px-4 py-4 border-t border-gray-100">{{ $suspendedAgents->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ═══ PENDING TAB ═══ --}}
    <div x-show="tab === 'pending'">
        @if($pendingAgents->isEmpty())
        <div class="bg-green-50 border border-green-200 rounded-2xl px-6 py-8 text-center text-green-700 font-medium">
            ✓ No pending agents awaiting approval.
        </div>
        @else
        <div class="bg-amber-50 border border-amber-200 rounded-2xl overflow-hidden">
            <div class="divide-y divide-amber-100">
                @foreach($pendingAgents as $agent)
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 px-6 py-5">
                    <div class="w-10 h-10 rounded-full bg-amber-200 flex items-center justify-center shrink-0">
                        <span class="text-amber-800 font-bold text-sm">{{ strtoupper(substr($agent->user->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-brand-dark">{{ $agent->user->name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $agent->user->email }}</p>
                        <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
                            @if($agent->company_name)<span>🏢 {{ $agent->company_name }}</span>@endif
                            @if($agent->country)<span>🌍 {{ $agent->country }}</span>@endif
                            @if($agent->phone)<span>📞 {{ $agent->phone }}</span>@endif
                            <span>Registered {{ $agent->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <form method="POST" action="{{ route('admin.agents.approve', $agent) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white text-sm font-semibold px-5 py-2 rounded-lg hover:bg-green-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.agents.reject', $agent) }}" onsubmit="return confirm('Reject {{ addslashes($agent->user->name) }}?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 border border-red-300 text-red-600 bg-white text-sm font-semibold px-5 py-2 rounded-lg hover:bg-red-50 transition">
                                Reject
                            </button>
                        </form>
                        <a href="{{ route('admin.agents.show', $agent) }}" class="text-xs text-gray-400 hover:text-brand-dark underline underline-offset-2">Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ═══ SETTINGS TAB ═══ --}}
    <div x-show="tab === 'settings'">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 max-w-2xl">
            @csrf
            @method('PUT')

            {{-- Commission --}}
            <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-4">
                <div>
                    <h3 class="font-semibold text-brand-dark mb-1">Commission Settings</h3>
                    <p class="text-sm text-gray-500">Default rate applied to new agents. Individual overrides can be set on each agent profile.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Commission Rate (%)</label>
                    <div class="flex items-center gap-3">
                        <input type="number" value="{{ $defaultCommission }}" min="0" max="100" step="0.5"
                               class="w-32 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30"
                               placeholder="10" disabled>
                        <span class="text-sm text-gray-400">To change an agent's rate, visit their profile.</span>
                    </div>
                </div>
            </div>

            {{-- Notification Email --}}
            <div class="bg-white rounded-2xl border border-black/5 p-6 space-y-5">
                <div>
                    <h3 class="font-semibold text-brand-dark mb-1">Notification Email</h3>
                    <p class="text-sm text-gray-500">All site notifications will be delivered to this address. The admin always receives every notification type.</p>
                </div>

                <div>
                    <label for="notification_email" class="block text-sm font-medium text-gray-700 mb-2">Administrator Email Address</label>
                    <input type="email" id="notification_email" name="notification_email"
                           value="{{ old('notification_email', $setting->notification_email) }}"
                           placeholder="admin@example.com"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                    @error('notification_email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-400">Leave blank to use the default system mail address configured in your environment.</p>
                </div>

                {{-- Notification Categories --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Notify me when…</label>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="notify_inquiry" value="1"
                                   {{ old('notify_inquiry', $setting->notify_inquiry ?? true) ? 'checked' : '' }}
                                   class="mt-0.5 w-4 h-4 rounded border-gray-300 text-brand-gold focus:ring-brand-gold/30">
                            <div>
                                <p class="text-sm font-medium text-gray-700 group-hover:text-brand-dark">New Enquiry received</p>
                                <p class="text-xs text-gray-400">Triggered when a visitor submits the contact / enquiry form.</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="notify_safari_request" value="1"
                                   {{ old('notify_safari_request', $setting->notify_safari_request ?? true) ? 'checked' : '' }}
                                   class="mt-0.5 w-4 h-4 rounded border-gray-300 text-brand-gold focus:ring-brand-gold/30">
                            <div>
                                <p class="text-sm font-medium text-gray-700 group-hover:text-brand-dark">New Safari Request from agent</p>
                                <p class="text-xs text-gray-400">Triggered when an agent submits a custom safari request awaiting your proposal.</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="notify_safari_plan" value="1"
                                   {{ old('notify_safari_plan', $setting->notify_safari_plan ?? true) ? 'checked' : '' }}
                                   class="mt-0.5 w-4 h-4 rounded border-gray-300 text-brand-gold focus:ring-brand-gold/30">
                            <div>
                                <p class="text-sm font-medium text-gray-700 group-hover:text-brand-dark">New Safari Plan submission</p>
                                <p class="text-xs text-gray-400">Triggered when a visitor submits the "Plan Your Safari" multi-step form.</p>
                            </div>
                        </label>
                    </div>

                    <p class="mt-4 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-2.5">
                        <strong>Note:</strong> The admin email above always receives <em>all</em> notification types regardless of the checkboxes above. These toggles only control additional per-category delivery if you extend the notification system in the future.
                    </p>
                </div>
            </div>

            {{-- Hidden required fields from main settings form --}}
            <input type="hidden" name="site_name" value="{{ $setting->site_name ?? 'Lomo Tanzania Safari' }}">

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-brand-dark text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-brand-dark/90 transition">
                    Save Notification Settings
                </button>
                @if(session('success') && request()->routeIs('admin.agents.index'))
                <span class="text-sm text-green-600 font-medium">✓ Saved</span>
                @endif
            </div>
        </form>
    </div>

</div>
</x-app-layout>