@extends('layouts.agent')

@section('title', 'My Requests')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-brand-dark">My Safari Requests</h1>
            <p class="text-sm text-gray-500 mt-0.5">Custom requests you've submitted to admin.</p>
        </div>
        <a href="{{ route('agent.requests.create') }}"
           class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark font-semibold px-4 py-2.5 rounded-xl text-sm hover:bg-brand-gold/90 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Request
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Travel Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">People</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Submitted</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Proposal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3.5">
                            <p class="font-medium text-brand-dark">{{ $req->client_name }}</p>
                            <p class="text-xs text-gray-400">{{ $req->client_email }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-gray-700">{{ $req->travel_date->format('d M Y') }}</td>
                        <td class="px-4 py-3.5 text-gray-700">{{ $req->people }}</td>
                        <td class="px-4 py-3.5 text-gray-500 text-xs">{{ $req->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-block text-xs px-2.5 py-1.5 rounded-full font-semibold
                                {{ $req->status === 'new' ? 'bg-amber-100 text-amber-700' :
                                   ($req->status === 'processing' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($req->response)
                                @if($req->response->status === 'pending')
                                <a href="{{ route('agent.responses.index') }}" class="text-xs font-semibold text-brand-dark bg-brand-gold/20 hover:bg-brand-gold/30 px-3 py-1.5 rounded-lg transition">
                                    View Proposal →
                                </a>
                                @elseif($req->response->status === 'accepted')
                                <span class="text-xs font-semibold text-green-600 bg-green-100 px-3 py-1.5 rounded-lg">Accepted</span>
                                @else
                                <span class="text-xs font-semibold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg">Declined</span>
                                @endif
                            @else
                            <span class="text-xs text-gray-400">Awaiting admin</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                            No requests yet. <a href="{{ route('agent.requests.create') }}" class="text-brand-dark underline underline-offset-2">Submit your first request</a>.
                        </td>
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
@endsection
