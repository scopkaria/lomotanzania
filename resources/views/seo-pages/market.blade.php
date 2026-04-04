@extends('layouts.app')

@section('seo_title', $market->meta_title ?: $market->translated('title'))

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "{{ $market->translated('title') }}",
    "description": "{{ $market->meta_description ?: Str::limit(strip_tags($market->translated('intro_content')), 160) }}",
    "url": "{{ url()->current() }}",
    "about": {
        "@@type": "TouristDestination",
        "name": "{{ $market->target_country }}"
    },
    "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Safaris", "item": "{{ route('safaris.index') }}" },
            { "@@type": "ListItem", "position": 3, "name": "{{ $market->translated('title') }}" }
        ]
    }
}
</script>
@endpush

@section('content')
{{-- Hero --}}
<section class="relative bg-[#083321] py-20 sm:py-28">
    <div class="absolute inset-0 opacity-20"
         style="background-image: url('{{ $market->featured_image ? asset('storage/' . $market->featured_image) : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1400&q=80' }}'); background-size: cover; background-position: center;">
    </div>
    <div class="relative mx-auto max-w-5xl px-4 text-center">
        <nav class="mb-6 flex items-center justify-center gap-2 text-sm text-white/60">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <a href="{{ route('safaris.index') }}" class="hover:text-white transition">Safaris</a>
            <span>/</span>
            <span class="text-white/80">{{ $market->translated('title') }}</span>
        </nav>

        <h1 class="text-3xl font-bold text-white sm:text-4xl lg:text-5xl">{{ $market->translated('title') }}</h1>

        @if($market->translated('intro_content'))
            <p class="mx-auto mt-6 max-w-2xl text-lg text-white/75 leading-relaxed">
                {{ $market->translated('intro_content') }}
            </p>
        @endif

        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            <span class="rounded-full bg-white/10 px-4 py-2 text-sm text-white/80 backdrop-blur-sm">
                🌍 {{ $market->target_country }}
            </span>
            <span class="rounded-full bg-white/10 px-4 py-2 text-sm text-white/80 backdrop-blur-sm">
                ✈️ From {{ $market->source_market }}
            </span>
            <span class="rounded-full bg-white/10 px-4 py-2 text-sm text-white/80 backdrop-blur-sm">
                📦 {{ $safaris->count() }} {{ Str::plural('Package', $safaris->count()) }}
            </span>
        </div>
    </div>
</section>

{{-- Travel Info Cards --}}
<section class="mx-auto max-w-6xl px-4 py-12">
    <h2 class="mb-8 text-2xl font-bold text-gray-900 text-center">
        Everything You Need to Know About Safari from {{ $market->source_market }}
    </h2>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @if($market->flights_info)
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">✈️ Flights</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $market->flights_info }}</p>
        </div>
        @endif

        @if($market->visa_info)
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-green-50">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">🛂 Visa Info</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $market->visa_info }}</p>
        </div>
        @endif

        @if($market->travel_tips)
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-50">
                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">💡 Travel Tips</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $market->travel_tips }}</p>
        </div>
        @endif

        @if($market->best_routes)
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50">
                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m0 0l3-3m-3 3l-3-3m12-1.5V15m0 0l3-3m-3 3l-3-3"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">🗺️ Best Routes</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $market->best_routes }}</p>
        </div>
        @endif

        @if($market->pricing_info)
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50">
                <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">💰 Pricing</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $market->pricing_info }}</p>
        </div>
        @endif
    </div>
</section>

{{-- Safari Packages --}}
<section class="bg-gray-50 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h2 class="mb-8 text-2xl font-bold text-gray-900">
            {{ $market->target_country }} Safari Packages
        </h2>

        @if($safaris->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($safaris as $safari)
                    <a href="{{ route('safaris.show', $safari->slug) }}" class="group overflow-hidden rounded-xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        <div class="aspect-[4/3] overflow-hidden">
                            @if($safari->featured_image)
                                <img src="{{ asset('storage/' . $safari->featured_image) }}"
                                     alt="{{ $safari->translated('title') }}"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gray-100">
                                    <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25c0 .828.672 1.5 1.5 1.5z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-[#083321] transition">
                                {{ $safari->translated('title') }}
                            </h3>
                            @if($safari->duration)
                                <p class="mt-1 text-sm text-gray-500">{{ $safari->duration }}</p>
                            @endif
                            @if($safari->translated('short_description'))
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $safari->translated('short_description') }}</p>
                            @endif
                            <div class="mt-4 flex items-center justify-between">
                                @if($safari->price)
                                    <span class="text-lg font-bold text-[#083321]">${{ number_format($safari->price) }}</span>
                                @endif
                                <span class="text-sm font-medium text-[#FEBC11] group-hover:underline">View Details →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border-2 border-dashed border-gray-200 bg-white p-12 text-center">
                <p class="text-gray-500">Safari packages coming soon for {{ $market->target_country }}.</p>
                <a href="{{ route('safaris.index') }}" class="mt-4 inline-block text-[#083321] font-semibold hover:underline">Browse all safaris →</a>
            </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="bg-[#083321] py-16">
    <div class="mx-auto max-w-3xl px-4 text-center">
        <h2 class="text-2xl font-bold text-white sm:text-3xl">Ready to Plan Your {{ $market->target_country }} Safari?</h2>
        <p class="mt-4 text-white/70">Our local experts will craft the perfect itinerary tailored to travelers from {{ $market->source_market }}.</p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('plan-safari') }}" class="rounded-lg bg-[#FEBC11] px-8 py-3 text-sm font-bold text-[#083321] shadow-lg transition hover:bg-[#e5a90f]">
                Plan My Safari
            </a>
            <a href="{{ route('custom-tour') }}" class="rounded-lg border border-white/30 bg-white/10 px-8 py-3 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20">
                Custom Tour Request
            </a>
        </div>
    </div>
</section>

{{-- Other Markets --}}
@php
    $otherMarkets = \App\Models\SeoMarket::published()
        ->where('id', '!=', $market->id)
        ->where('target_country', $market->target_country)
        ->limit(5)
        ->get();
@endphp
@if($otherMarkets->isNotEmpty())
<section class="py-12">
    <div class="mx-auto max-w-5xl px-4">
        <h2 class="mb-6 text-xl font-bold text-gray-900">{{ $market->target_country }} Safari from Other Countries</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($otherMarkets as $om)
                <a href="{{ route('seo.market', $om->slug) }}" class="rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-[#083321] hover:text-white">
                    From {{ $om->source_market }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
