@extends('layouts.agent')

@section('title', 'Proposals')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold text-brand-dark">Admin Proposals</h1>
        <p class="text-sm text-gray-500 mt-0.5">Proposals sent by admin based on your safari requests.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    @forelse($responses as $req)
    @php $response = $req->response; $agent = auth()->user()->agent; @endphp
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        {{-- Header --}}
        <div class="flex items-start gap-4 px-6 py-5 border-b border-gray-100">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3 class="font-bold text-brand-dark text-base">{{ $response->safari_title }}</h3>
                    <span class="inline-block text-xs px-2.5 py-1 rounded-full font-semibold
                        {{ $response->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                           ($response->status === 'accepted' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600') }}">
                        {{ ucfirst($response->status) }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Request for {{ $req->client_name }} &bull; Travel: {{ $req->travel_date->format('d M Y') }} &bull; {{ $req->people }} {{ Str::plural('person', $req->people) }}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-2xl font-bold text-brand-dark">${{ number_format($response->price, 0) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Total price</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 space-y-4">
            @if($response->description)
            <p class="text-sm text-gray-700 leading-relaxed">{{ $response->description }}</p>
            @endif

            {{-- Commission preview --}}
            @php $commission = (float) $response->price * ($agent->commission_rate / 100); @endphp
            <div class="grid grid-cols-3 gap-4 bg-brand-light rounded-xl p-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-1">Package Price</p>
                    <p class="font-bold text-brand-dark">${{ number_format($response->price, 0) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Your Commission ({{ $agent->commission_rate }}%)</p>
                    <p class="font-bold text-emerald-600">${{ number_format($commission, 0) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Net to Operator</p>
                    <p class="font-bold text-gray-700">${{ number_format($response->price - $commission, 0) }}</p>
                </div>
            </div>

            @if($response->notes)
            <div class="bg-blue-50 rounded-xl p-4 text-sm text-blue-700">
                <span class="font-semibold">Admin note:</span> {{ $response->notes }}
            </div>
            @endif
        </div>

        {{-- Actions --}}
        @if($response->status === 'pending')
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center gap-3">
            <form method="POST" action="{{ route('agent.responses.accept', $req) }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Accept this proposal and create a booking for {{ addslashes($req->client_name) }}?')"
                        class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Accept & Book
                </button>
            </form>
            <form method="POST" action="{{ route('agent.responses.decline', $req) }}">
                @csrf
                <button type="submit"
                        onclick="return confirm('Decline this proposal?')"
                        class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-600 font-medium px-5 py-2.5 rounded-xl text-sm hover:border-red-300 hover:text-red-500 transition">
                    Decline
                </button>
            </form>
            <p class="ml-auto text-xs text-gray-400">Received {{ $response->created_at->diffForHumans() }}</p>
        </div>
        @elseif($response->status === 'accepted')
        <div class="px-6 py-4 bg-green-50 border-t border-green-100 flex items-center gap-2 text-sm text-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            You accepted this proposal — booking has been created.
            <a href="{{ route('agent.bookings.index') }}" class="ml-auto underline underline-offset-2 font-medium">View Bookings →</a>
        </div>
        @else
        <div class="px-6 py-4 bg-red-50 border-t border-red-100 text-sm text-red-500">
            You declined this proposal.
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-black/5 px-6 py-12 text-center">
        <p class="text-gray-400 text-sm">No proposals from admin yet.</p>
        <p class="text-xs text-gray-300 mt-1">Submit a safari request and admin will respond with a custom proposal.</p>
        <a href="{{ route('agent.requests.create') }}"
           class="inline-flex items-center gap-2 mt-4 bg-brand-gold text-brand-dark font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-brand-gold/90 transition">
            + Request a Safari
        </a>
    </div>
    @endforelse

    @if($responses->hasPages())
    <div>{{ $responses->links() }}</div>
    @endif

</div>
@endsection
