<x-app-layout>
<div class="max-w-3xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.safari-requests.index') }}" class="text-gray-400 hover:text-brand-dark transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-brand-dark">Request #{{ $safariRequest->id }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">Submitted {{ $safariRequest->created_at->format('d M Y, g:i A') }}</p>
        </div>
        <span class="text-sm px-3 py-1 rounded-full font-semibold
            {{ $safariRequest->status === 'new' ? 'bg-amber-100 text-amber-700' :
               ($safariRequest->status === 'processing' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
            {{ ucfirst($safariRequest->status) }}
        </span>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3.5 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Agent info --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Agent</p>
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center">
                <span class="text-brand-dark font-bold text-sm">{{ strtoupper(substr($safariRequest->agent->user->name, 0, 2)) }}</span>
            </div>
            <div>
                <p class="font-semibold text-brand-dark">{{ $safariRequest->agent->user->name }}</p>
                <p class="text-xs text-gray-400">{{ $safariRequest->agent->user->email }} &bull; {{ $safariRequest->agent->company_name ?? 'No company' }}</p>
            </div>
            <a href="{{ route('admin.agents.show', $safariRequest->agent) }}" class="ml-auto text-xs text-brand-dark hover:underline underline-offset-2">View Agent →</a>
        </div>
    </div>

    {{-- Client info --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Client Details</p>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-400 mb-0.5">Name</p><p class="font-medium text-brand-dark">{{ $safariRequest->client_name }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Email</p><p class="font-medium text-brand-dark">{{ $safariRequest->client_email }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Phone</p><p class="font-medium text-brand-dark">{{ $safariRequest->client_phone ?? '—' }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Country</p><p class="font-medium text-brand-dark">{{ $safariRequest->country ?? '—' }}</p></div>
        </div>
    </div>

    {{-- Travel details --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Travel Details</p>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-gray-400 mb-0.5">Travel Date</p><p class="font-medium text-brand-dark">{{ $safariRequest->travel_date->format('d M Y') }}</p></div>
            <div><p class="text-gray-400 mb-0.5">Number of People</p><p class="font-medium text-brand-dark">{{ $safariRequest->people }}</p></div>
        </div>

        @if($safariRequest->destinations && count($safariRequest->destinations) > 0)
        <div class="mt-4">
            <p class="text-gray-400 text-sm mb-2">Destinations</p>
            <div class="flex flex-wrap gap-2">
                @foreach(\App\Models\Destination::whereIn('id', $safariRequest->destinations)->get() as $dest)
                <span class="bg-brand-green/10 text-brand-green text-xs font-semibold px-3 py-1 rounded-full">{{ $dest->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($safariRequest->activities && count($safariRequest->activities) > 0)
        <div class="mt-4">
            <p class="text-gray-400 text-sm mb-2">Activities / Interests</p>
            <div class="flex flex-wrap gap-2">
                @foreach($safariRequest->activities as $activity)
                <span class="bg-brand-gold/15 text-brand-dark text-xs font-semibold px-3 py-1 rounded-full">{{ $activity }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($safariRequest->notes)
        <div class="mt-4">
            <p class="text-gray-400 text-sm mb-2">Client Preferences / Notes</p>
            <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4 leading-relaxed">{{ $safariRequest->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Response section --}}
    @if($safariRequest->response)
    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 space-y-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="font-semibold text-green-800">Proposal Sent</h3>
            <span class="ml-auto text-xs text-green-600">{{ $safariRequest->response->created_at->format('d M Y, g:i A') }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="col-span-2"><p class="text-gray-500 mb-0.5">Safari Title</p><p class="font-bold text-brand-dark text-base">{{ $safariRequest->response->safari_title }}</p></div>
            <div class="col-span-2"><p class="text-gray-500 mb-0.5">Description</p><p class="text-gray-700">{{ $safariRequest->response->description ?? '—' }}</p></div>
            <div><p class="text-gray-500 mb-0.5">Quoted Price</p><p class="font-bold text-green-700 text-lg">${{ number_format($safariRequest->response->price, 0) }}</p></div>
            <div><p class="text-gray-500 mb-0.5">Agent Response</p>
                <span class="inline-block text-xs px-2.5 py-1 rounded-full font-semibold
                    {{ $safariRequest->response->status === 'accepted' ? 'bg-green-100 text-green-700' :
                       ($safariRequest->response->status === 'declined' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($safariRequest->response->status) }}
                </span>
            </div>
            @if($safariRequest->response->notes)
            <div class="col-span-2"><p class="text-gray-500 mb-0.5">Internal Notes</p><p class="text-gray-700">{{ $safariRequest->response->notes }}</p></div>
            @endif
        </div>
    </div>
    @endif

    {{-- ═══ RESPOND FORM ═══ --}}
    @if(!$safariRequest->response)
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <h3 class="font-semibold text-brand-dark mb-1">Build & Send Proposal</h3>
        <p class="text-sm text-gray-500 mb-5">This price is custom for this request and will NOT be saved to any safari package.</p>

        <form method="POST" action="{{ route('admin.safari-requests.respond', $safariRequest) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Safari / Package Title <span class="text-red-500">*</span></label>
                <input type="text" name="safari_title" value="{{ old('safari_title') }}" required
                       placeholder="e.g. 5-Day Serengeti Migration Safari"
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                @error('safari_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" placeholder="Describe the itinerary, highlights, inclusions..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 resize-none">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Price (USD) — Total for {{ $safariRequest->people }} {{ Str::plural('person', $safariRequest->people) }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-400 text-sm">$</span>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required
                           placeholder="0.00"
                           class="w-full border border-gray-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30">
                </div>
                <p class="text-xs text-gray-400 mt-1">Commission will be calculated automatically when the agent converts this into a booking.</p>
                @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes (optional)</label>
                <textarea name="notes" rows="2" placeholder="Notes for the agent..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-gold/30 resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-brand-gold text-brand-dark font-bold px-6 py-3 rounded-xl text-sm hover:bg-brand-gold/90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send Proposal to Agent
                </button>
            </div>
        </form>
    </div>
    @endif

</div>
</x-app-layout>
