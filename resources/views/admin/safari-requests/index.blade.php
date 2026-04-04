<x-app-layout>
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-brand-dark">Safari Requests</h2>
            <p class="text-sm text-gray-500 mt-0.5">Custom safari requests submitted by agents.</p>
        </div>
        <div class="flex items-center gap-3">
            @foreach(['new' => 'amber', 'processing' => 'blue', 'completed' => 'green'] as $status => $color)
            <span class="inline-flex items-center gap-1.5 bg-{{ $color }}-50 text-{{ $color }}-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                {{ ucfirst($status) }}: {{ $counts[$status] }}
            </span>
            @endforeach
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Status filter --}}
    <form method="GET" class="flex items-center gap-3">
        <select name="status" onchange="this.form.submit()"
                class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
            <option value="">All Statuses</option>
            @foreach(['new', 'processing', 'completed'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @if(request('status'))
        <a href="{{ route('admin.safari-requests.index') }}" class="text-sm text-gray-400 hover:text-brand-dark underline underline-offset-2">Clear</a>
        @endif
    </form>

    {{-- Requests Table --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Agent</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Travel Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">People</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Submitted</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3.5">
                            <p class="font-medium text-brand-dark">{{ $req->agent->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $req->agent->company_name ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-medium text-brand-dark">{{ $req->client_name }}</p>
                            <p class="text-xs text-gray-400">{{ $req->client_email }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-gray-700">{{ $req->travel_date->format('d M Y') }}</td>
                        <td class="px-4 py-3.5 text-gray-700">{{ $req->people }}</td>
                        <td class="px-4 py-3.5 text-gray-500 text-xs">{{ $req->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-block text-xs px-2.5 py-1 rounded-full font-medium
                                {{ $req->status === 'new' ? 'bg-amber-100 text-amber-700' :
                                   ($req->status === 'processing' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <a href="{{ route('admin.safari-requests.show', $req) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-dark hover:text-brand-green transition bg-brand-gold/10 hover:bg-brand-gold/20 px-3 py-1.5 rounded-lg">
                                {{ $req->status === 'new' ? 'Handle' : 'View' }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">No safari requests yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
        <div class="px-4 py-4 border-t border-gray-100">{{ $requests->links() }}</div>
        @endif
    </div>

</div>
</x-app-layout>
