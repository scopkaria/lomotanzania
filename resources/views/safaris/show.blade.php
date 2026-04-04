@extends('layouts.app')

@push('jsonld')
{{-- TouristTrip + Product Schema --}}
@php
    $siteName = $siteName ?? optional(\App\Models\Setting::first())->site_name ?? 'Lomo Tanzania Safari';
    $safariTitle = $safari->translated('title');
    $safariDesc = Str::limit(strip_tags($safari->translated('short_description') ?: $safari->translated('description')), 200);
    $safariUrl = url()->current();
    $safariImage = $safari->featured_image ? asset('storage/' . $safari->featured_image) : null;
    $approvedReviews = $safari->testimonials->where('approved', true);
    $avgRating = $approvedReviews->avg('rating');
    $reviewCount = $approvedReviews->count();
@endphp
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": ["TouristTrip", "Product"],
    "name": "{{ $safariTitle }}",
    "description": "{{ $safariDesc }}",
    "url": "{{ $safariUrl }}",
    @if($safariImage)
    "image": "{{ $safariImage }}",
    @endif
    "touristType": "Safari",
    "brand": {
        "@@type": "Brand",
        "name": "{{ $siteName }}"
    },
    @if($safari->duration)
    "itinerary": {
        "@@type": "ItemList",
        "numberOfItems": {{ $safari->itineraries->count() ?: 1 }},
        "description": "{{ $safari->duration }}",
        "itemListElement": [
            @foreach($safari->itineraries as $itin)
            {
                "@@type": "ListItem",
                "position": {{ $loop->iteration }},
                "name": "Day {{ $loop->iteration }}: {{ $itin->translated('title') }}",
                "description": "{{ Str::limit(strip_tags($itin->translated('description')), 150) }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    },
    @endif
    @if($safari->seasonal_pricing)
    @php
        $prices = collect($safari->seasonal_pricing)->flatMap(fn($s) => collect($s)->values())->filter()->values();
        $minPrice = $prices->min();
        $maxPrice = $prices->max();
        $currency = $safari->currency ?? 'USD';
    @endphp
    @if($minPrice)
    "offers": {
        "@@type": "AggregateOffer",
        "lowPrice": "{{ $minPrice }}",
        "highPrice": "{{ $maxPrice }}",
        "priceCurrency": "{{ $currency }}",
        "availability": "https://schema.org/InStock",
        "seller": {
            "@@type": "TravelAgency",
            "name": "{{ $siteName }}"
        }
    },
    @endif
    @endif
    @if($reviewCount > 0)
    "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ number_format($avgRating, 1) }}",
        "reviewCount": {{ $reviewCount }},
        "bestRating": "5",
        "worstRating": "1"
    },
    "review": [
        @foreach($approvedReviews->take(5) as $review)
        {
            "@@type": "Review",
            "author": { "@@type": "Person", "name": "{{ $review->name }}" },
            "reviewRating": { "@@type": "Rating", "ratingValue": "{{ $review->rating }}", "bestRating": "5" },
            "reviewBody": "{{ Str::limit($review->message, 200) }}"
        }@if(!$loop->last),@endif
        @endforeach
    ],
    @endif
    "provider": {
        "@@type": "TravelAgency",
        "name": "{{ $siteName }}",
        "url": "{{ url('/') }}"
    }
}
</script>

{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Safaris", "item": "{{ route('safaris.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "{{ $safariTitle }}" }
    ]
}
</script>

{{-- FAQ Schema --}}
@php
    $faqs = [];
    // Dynamic FAQs based on safari data
    $destNames = $safari->destinations->pluck('name')->join(', ');
    $countryNames = $safari->countries->pluck('name')->join(' & ');
    $countryName = $safari->countries->first()?->name ?? 'Tanzania';

    if ($destNames) {
        $faqs[] = ['q' => "What is the best time to visit {$destNames}?", 'a' => "The best time to visit {$destNames} depends on your safari goals. The dry season (June–October) offers excellent wildlife viewing, while the wet season (November–May) brings lush scenery, fewer crowds, and lower prices."];
    }
    if ($safari->seasonal_pricing && isset($minPrice)) {
        $faqs[] = ['q' => "How much does the {$safariTitle} cost?", 'a' => "Prices for this safari start from \${$minPrice} {$currency} per person and go up to \${$maxPrice} {$currency} depending on the season and group size. Contact us for customized quotes."];
    }
    $faqs[] = ['q' => "What animals will I see on this safari?", 'a' => "On this {$countryName} safari you can expect to see the Big Five (lion, leopard, elephant, buffalo, rhino) along with cheetahs, zebras, wildebeest, giraffes, hippos, and hundreds of bird species."];
    if ($safari->duration) {
        $faqs[] = ['q' => "How long is this safari?", 'a' => "This safari is {$safari->duration} and covers {$destNames}. Each day features unique destinations and activities carefully planned for the best experience."];
    }
    $faqs[] = ['q' => "Is this safari suitable for families?", 'a' => "Yes, this safari can be customized for families with children. We offer family-friendly accommodations and flexible schedules. Contact our team to discuss your specific needs."];
    $faqs[] = ['q' => "What is included in this safari package?", 'a' => "The package typically includes accommodation, meals, park fees, professional guide, and transportation in a 4x4 safari vehicle. Specific inclusions are listed in the itinerary section above."];
@endphp
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs as $faq)
        {
            "@@type": "Question",
            "name": "{{ $faq['q'] }}",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "{{ $faq['a'] }}"
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endpush

@push('styles')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet">
<style>
    .safari-editorial-copy {
        color: #374151;
    }

    .safari-editorial-copy > * + * {
        margin-top: 1.25rem;
    }

    .safari-editorial-copy a {
        color: #083321;
        text-decoration: underline;
        text-decoration-color: rgba(254, 188, 17, 0.9);
        text-underline-offset: 0.2em;
        transition: color 180ms ease, text-decoration-color 180ms ease;
    }

    .safari-editorial-copy a:hover {
        color: #131414;
        text-decoration-color: #083321;
    }

    .safari-editorial-copy ul,
    .safari-editorial-copy ol {
        padding-left: 1.25rem;
    }

    .safari-editorial-copy li + li {
        margin-top: 0.5rem;
    }

    .safari-editorial-copy.is-centered ul,
    .safari-editorial-copy.is-centered ol {
        display: inline-block;
        margin-left: auto;
        margin-right: auto;
        text-align: left;
    }

    .one-line-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .editorial-reveal {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity 700ms cubic-bezier(0.16, 1, 0.3, 1), transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    .editorial-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .safari-timeline-line {
        background-color: rgb(229 231 235);
    }

    .safari-timeline-progress {
        transform: scaleY(var(--timeline-progress, 0));
        transform-origin: top;
        will-change: transform;
    }

    .safari-timeline-card.is-active .safari-timeline-dot {
        background-color: #febc11;
        box-shadow: 0 0 0 8px rgba(254, 188, 17, 0.12);
    }

    .safari-timeline-card.is-active .safari-day-badge {
        box-shadow: 0 0 0 6px rgba(8, 51, 33, 0.08);
    }

    .safari-accommodation-trigger:hover .safari-accommodation-arrow {
        transform: translateX(4px);
    }

    .safari-slider::-webkit-scrollbar {
        display: none;
    }

    .safari-slider {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .safari-slider-card {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .safari-map-shell .mapboxgl-map,
    .safari-map-shell .mapboxgl-canvas {
        width: 100%;
        height: 100%;
    }

    .safari-map-shell .mapboxgl-ctrl-top-left {
        top: 0.75rem;
        left: 0.75rem;
    }

    .safari-map-shell .mapboxgl-ctrl-group {
        border: 1px solid rgba(19, 20, 20, 0.08);
        border-radius: 0.375rem;
        box-shadow: 0 12px 28px -22px rgba(19, 20, 20, 0.28);
        overflow: hidden;
    }

    .safari-map-shell .mapboxgl-ctrl-group button {
        width: 1.95rem;
        height: 1.95rem;
    }

    .safari-map-shell .mapboxgl-popup {
        display: none;
    }

    .safari-route-map {
        height: 28rem;
        min-height: 28rem;
        width: 100%;
    }

    .safari-route-map-empty {
        height: 28rem;
        min-height: 28rem;
    }

    /* Outer element: Mapbox applies transform on this — keep it clean */
    .safari-map-marker {
        width: 10px;
        height: 10px;
        pointer-events: none;
    }

    /* Inner wrapper: safe to animate because Mapbox never touches it */
    .safari-map-marker-inner {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: visible;
        animation: safari-marker-pop 360ms ease-out both;
    }

    .safari-map-marker-badge {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        border-radius: 0.125rem;
        background: rgba(255, 252, 245, 0.98);
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.2;
        color: #131414;
        white-space: nowrap;
        box-shadow: 0 10px 24px -20px rgba(19, 20, 20, 0.3);
    }

    .safari-map-marker-connector {
        position: absolute;
        bottom: 100%;
        left: 50%;
        width: 1px;
        height: 8px;
        background: rgba(19, 20, 20, 0.2);
        transform: translateX(-50%);
    }

    .safari-map-marker-dot {
        position: absolute;
        inset: 0;
        border-radius: 9999px;
        background: #131414;
        border: 1px solid rgba(255, 252, 245, 0.92);
        box-shadow: 0 0 0 4px rgba(255, 252, 245, 0.55);
    }

    .safari-map-marker.is-important .safari-map-marker-dot {
        background: #FEBC11;
        border-color: rgba(19, 20, 20, 0.88);
        box-shadow: 0 0 0 4px rgba(254, 188, 17, 0.18);
    }

    .safari-map-marker.is-important .safari-map-marker-badge {
        font-weight: 700;
    }

    @keyframes safari-marker-pop {
        from {
            opacity: 0;
            transform: translateY(6px) scale(0.96);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .safari-float-row {
        transition: transform 260ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    .safari-float-row[data-expert-visible='true'] {
        transform: translateX(-0.4rem);
    }

    .safari-cta-silhouette {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1400 420'%3E%3Cg fill='%23ffffff'%3E%3Cpath d='M0 338h1400v82H0z' opacity='.55'/%3E%3Cpath d='M154 294c14 0 26 9 31 22h19c14 0 25 11 25 25v7h-13v-6c0-7-5-12-12-12h-22l-3 18h-12l-3-18h-14l-5 18h-12l6-22c4-18 19-32 35-32z' opacity='.9'/%3E%3Cpath d='M256 266h15l10 23h15l4 12h-14l-2 12h-11l2-12h-17l-4 12h-12l6-19-8-28h10l6 20h8z' opacity='.85'/%3E%3Cpath d='M430 196c18 0 33 12 38 29l18 63h-14l-6-21h-24l-7 21h-13l20-59h-10v-33h-10v-15h8z' opacity='.78'/%3E%3Cpath d='M438 212h7v17h14v12h-35v-12h14z'/%3E%3Cpath d='M685 302c19 0 34 13 38 31h22c16 0 29 13 29 29v4h-14v-3c0-9-7-16-16-16h-24l-3 19h-13l-4-19h-28l-7 19h-13l9-25c6-22 23-39 44-39z' opacity='.92'/%3E%3Cpath d='M846 222h17l7 28h22l20 78h-14l-7-26h-32l-6 26h-14l20-106h-13v-14z' opacity='.78'/%3E%3Cpath d='M860 266l-8 22h26l-6-22z'/%3E%3Cpath d='M1096 198c17 0 31 11 37 27l16 42h18v13h-13l5 14h-12l-6-14h-40l-6 14h-12l6-14h-13v-13h19l15-42c6-16 20-27 36-27z' opacity='.82'/%3E%3Cpath d='M1112 215c-10 0-18 6-22 16l-13 36h68l-14-36c-4-10-11-16-19-16z'/%3E%3Cpath d='M1212 169h16l11 26h17l10 24h-11l-6-13h-21v30h-12v-67z' opacity='.76'/%3E%3C/g%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center bottom;
        background-size: cover;
    }

    @media (min-width: 640px) {
        .safari-slider-card {
            flex-basis: calc(50% - 0.75rem);
            max-width: calc(50% - 0.75rem);
        }
    }

    @media (min-width: 1024px) {
        .safari-slider-card {
            flex-basis: calc(33.333% - 1rem);
            max-width: calc(33.333% - 1rem);
        }

        .safari-route-map {
            height: 42rem;
            min-height: 42rem;
        }

        .safari-route-map-empty {
            height: 42rem;
            min-height: 42rem;
        }
    }

    @media (max-width: 1023px) {
        .editorial-reveal {
            opacity: 1;
            transform: none;
            transition: none;
        }
    }
</style>
@endpush

@section('content')
    @php
        $highlights = collect(is_array($safari->highlights) ? $safari->highlights : (json_decode($safari->highlights ?? '[]', true) ?: []))
            ->filter(fn ($item) => filled($item))
            ->values();
        $included = collect(is_array($safari->included) ? $safari->included : (json_decode($safari->included ?? '[]', true) ?: []))
            ->filter(fn ($item) => filled($item))
            ->values();
        $excluded = collect(is_array($safari->excluded) ? $safari->excluded : (json_decode($safari->excluded ?? '[]', true) ?: []))
            ->filter(fn ($item) => filled($item))
            ->values();
        $seasonalPricing = is_array($safari->seasonal_pricing)
            ? $safari->seasonal_pricing
            : (json_decode($safari->seasonal_pricing ?? '[]', true) ?: []);

        $overviewTitle = $safari->translated('overview_title') ?: __('messages.experience_overview');
        $highlightsTitle = $safari->translated('highlights_title') ?: __('messages.highlights');
        $highlightsIntro = $safari->translated('highlights_intro') ?: __('messages.highlights_intro');
        $inclusionsTitle = $safari->translated('inclusions_title') ?: __('messages.pricing_inclusions');
        $inclusionsIntro = $safari->translated('inclusions_intro') ?: __('messages.pricing_inclusions_desc');
        $itineraryDays = $safari->itineraries->sortBy('day_number')->values();
        $accommodationCards = $itineraryDays
            ->map(fn ($day) => $day->accommodationRelation)
            ->filter()
            ->unique('id')
            ->values();
        $accommodationModalData = $accommodationCards->map(function ($accommodation) {
            return [
                'id' => $accommodation->id,
                'name' => $accommodation->translated('name'),
                'description' => $accommodation->translated('description'),
                'images' => $accommodation->images
                    ->map(fn ($image) => asset('storage/' . $image->image_path))
                    ->values()
                    ->all(),
            ];
        })->values();
        $downloadItineraryData = $itineraryDays->map(function ($day) {
            return [
                'day_number' => $day->day_number,
                'title' => $day->translated('title'),
                'description' => trim(strip_tags((string) ($day->translated('description') ?? ''))),
                'destination' => $day->destination ? $day->destination->translated('name') : null,
                'accommodation' => $day->accommodationRelation ? $day->accommodationRelation->translated('name') : null,
            ];
        })->values();
        $hasSeasonalPricing = collect($seasonalPricing)
            ->flatMap(fn ($season) => collect($season)->filter(fn ($value) => filled($value)))
            ->isNotEmpty();
        $currency = $safari->currency ?: 'USD';
        $formatAmount = fn ($amount) => $amount !== null && $amount !== '' ? number_format((float) $amount, 0) : null;
        $seasonalValues = collect($seasonalPricing)
            ->flatMap(fn ($season) => collect($season)->filter(fn ($value) => filled($value)))
            ->map(fn ($value) => (float) $value);
        $startingPriceValue = $seasonalValues->isNotEmpty() ? $seasonalValues->min() : null;
        $startingPriceLabel = $startingPriceValue !== null ? $currency . ' ' . number_format($startingPriceValue, 0) : null;
        $durationLabel = $safari->duration ?: ($itineraryDays->count() ? $itineraryDays->count() . ' Days' : 'Safari');
        $mapStopCount = $itineraryDays->count();
        $uniqueDestinationNames = $itineraryDays
            ->map(fn ($day) => $day->destination ? $day->destination->translated('name') : null)
            ->filter(fn ($name) => filled($name))
            ->unique()
            ->values();
        $destinationCount = $uniqueDestinationNames->count();
        $groupedMapStops = [];
        foreach ($itineraryDays as $day) {
            $destination = $day->destination;

            if (! filled($destination?->latitude) || ! filled($destination?->longitude)) {
                continue;
            }

            $lastIndex = count($groupedMapStops) - 1;
            if ($lastIndex >= 0 && ($groupedMapStops[$lastIndex]['destination_id'] ?? null) === $destination->id) {
                $groupedMapStops[$lastIndex]['day_end'] = $day->day_number;
                $groupedMapStops[$lastIndex]['label'] = $groupedMapStops[$lastIndex]['day_start'] === $day->day_number
                    ? 'Day ' . $day->day_number . ' — ' . $destination->translated('name')
                    : 'Day ' . $groupedMapStops[$lastIndex]['day_start'] . '–' . $day->day_number . ' — ' . $destination->translated('name');
                continue;
            }

            $groupedMapStops[] = [
                'destination_id' => $destination->id,
                'day_start' => $day->day_number,
                'day_end' => $day->day_number,
                'name' => $destination->translated('name'),
                'latitude' => (float) $destination->latitude,
                'longitude' => (float) $destination->longitude,
                'label' => 'Day ' . $day->day_number . ' — ' . $destination->translated('name'),
            ];
        }
        $hasMapStops = count($groupedMapStops) > 0;
    @endphp

    <section class="relative flex min-h-screen items-center justify-center overflow-hidden bg-brand-dark px-6 py-16 text-white sm:py-20">
        @if($safari->featured_image)
            <img
                src="{{ asset('storage/' . $safari->featured_image) }}"
                alt="{{ $safari->translated('title') }}"
                class="absolute inset-0 h-full w-full object-cover"
            >
        @endif
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.06),transparent_35%),linear-gradient(180deg,rgba(19,20,20,0.42)_0%,rgba(19,20,20,0.58)_32%,rgba(19,20,20,0.84)_100%)]"></div>

        <nav class="absolute bottom-6 left-6 z-10 flex items-center gap-2 text-sm font-medium text-white/80 sm:bottom-8 sm:left-8">
            <a href="{{ route('home') }}" class="inline-flex items-center text-white/70 transition hover:text-white" aria-label="Home">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 11.204 3.045a1.125 1.125 0 0 1 1.592 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg>
                <span class="sr-only">Home</span>
            </a>
            <span>/</span>
            <a href="{{ route('safaris.index') }}" class="text-white/70 transition hover:text-white">Safaris</a>
            <span>/</span>
            <span class="max-w-[15rem] truncate text-white/90 sm:max-w-none">{{ $safari->translated('title') }}</span>
        </nav>

        <div class="relative z-10 mx-auto flex w-full max-w-5xl flex-col items-center justify-center text-center">
            @if($safari->duration || $safari->tour_type || $safari->countries->isNotEmpty())
                <div class="mb-8 flex flex-wrap items-center justify-center gap-3 text-[11px] uppercase tracking-[0.24em] text-white/75 sm:text-xs">
                    @if($safari->duration)
                        <span>{{ $safari->duration }}</span>
                    @endif
                    @if($safari->tour_type)
                        <span class="hidden h-1 w-1 rounded-full bg-brand-gold sm:inline-block"></span>
                        <span>{{ $safari->tour_type }}</span>
                    @endif
                    @if($safari->countries->isNotEmpty())
                        <span class="hidden h-1 w-1 rounded-full bg-brand-gold sm:inline-block"></span>
                        <span>{{ $safari->countries->pluck('name')->join(' / ') }}</span>
                    @endif
                </div>
            @endif

            <h1 class="max-w-4xl font-heading text-3xl font-bold leading-tight text-white md:text-4xl lg:text-5xl">
                {{ $safari->translated('title') }}
            </h1>
        </div>
    </section>

    <div
        x-data="safariDetailPage({
            accommodations: @js($accommodationModalData),
            itinerary: @js($downloadItineraryData),
            pricingMatrix: @js($seasonalPricing),
            safariId: {{ $safari->id }},
            safariTitle: @js($safari->translated('title')),
            safariDuration: @js($durationLabel),
            safariPrice: @js($startingPriceLabel),
            startingPriceValue: @js($startingPriceValue),
            baseCurrency: @js($currency),
            exchangeRateApi: @js(config('services.exchange_rates.url')),
            mapboxToken: @js(config('services.mapbox.token')),
            mapStops: @js($groupedMapStops),
            safariImage: @js($safari->featured_image ? asset('storage/' . $safari->featured_image) : null),
            inquiryRoute: @js(route('inquiries.store')),
            csrfToken: @js(csrf_token())
        })"
        class="bg-brand-light text-brand-dark"
    >
        <section class="editorial-reveal px-6 py-16 sm:py-20" data-reveal>
            <div class="mx-auto w-full max-w-4xl text-center">
                <span class="rounded-full border border-brand-gold/30 bg-white px-4 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em] text-brand-green shadow-sm">
                    {{ __('messages.editorial_overview') }}
                </span>
                <h2 class="mt-6 font-heading text-3xl font-bold text-brand-dark sm:text-4xl md:text-5xl">
                    {{ $overviewTitle }}
                </h2>
                @if($safari->full_description)
                    <div class="safari-editorial-copy is-centered mx-auto mt-8 max-w-4xl text-center text-base leading-8 sm:text-lg">
                        {!! $safari->full_description !!}
                    </div>
                @endif
            </div>
        </section>

        <section class="editorial-reveal px-6 pb-16 sm:pb-20" data-reveal>
            <div class="mx-auto max-w-6xl">
                <div class="mx-auto max-w-3xl text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-brand-green">{{ __('messages.journey_map') }}</p>
                    <h3 class="mt-3 font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ __('messages.route_overview') }}</h3>
                    <p class="mt-5 text-sm leading-7 text-gray-500 sm:text-base">{{ __('messages.route_overview_desc') }}</p>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-5">
                    <div class="lg:col-span-3">
                        <div class="safari-map-shell overflow-hidden rounded-md border border-gray-200 bg-white shadow-sm">
                            <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <span class="rounded-full bg-brand-green/5 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-green">
                                    {{ $mapStopCount }} {{ __('messages.stops') }}
                                </span>
                                <p class="text-sm leading-6 text-gray-500 sm:text-right">
                                    {{ $destinationCount }} {{ trans_choice('messages.destination_word', $destinationCount) }}@if($destinationCount): {{ $uniqueDestinationNames->join(', ') }}@endif
                                </p>
                            </div>
                            <div class="relative overflow-hidden rounded-b-md">
                                <div x-show="canRenderMap" x-ref="overviewMap" class="safari-route-map"></div>
                                <div x-show="!canRenderMap" class="safari-route-map-empty flex w-full flex-col items-center justify-center gap-3 bg-[linear-gradient(180deg,#f7faf9_0%,#eef4f1_100%)] px-6 text-center text-gray-500">
                                    <svg class="h-10 w-10 text-brand-green/45" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20.25h6M12 3v17.25m0 0c4.142 0 7.5-3.19 7.5-7.125S16.142 6 12 6s-7.5 3.19-7.5 7.125S7.858 20.25 12 20.25Z"/></svg>
                                    <p class="max-w-sm text-sm leading-7" x-text="mapMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-2">
                        <div class="lg:sticky lg:top-28">
                            <div class="text-left">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-green">{{ __('messages.route_summary') }}</p>
                                <h4 class="mt-3 font-heading text-2xl font-bold text-brand-dark">{{ __('messages.itinerary_stops') }}</h4>
                                <p class="mt-4 text-sm leading-6 text-gray-500">{{ __('messages.route_summary_desc') }}</p>
                            </div>

                            <div class="mt-6 space-y-2 text-left text-sm text-brand-dark">
                                @if($hasMapStops)
                                    @foreach($groupedMapStops as $stop)
                                        <div class="border-b border-gray-100 pb-2 last:border-b-0 last:pb-0">
                                            <p class="font-medium leading-6">{{ $stop['label'] }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="leading-6 text-gray-500">{{ __('messages.itinerary_map_note') }}</p>
                                @endif
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        @if($highlights->isNotEmpty())
            <section class="editorial-reveal px-6 pb-16 sm:pb-20" data-reveal>
                <div class="mx-auto max-w-6xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <h2 class="font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ $highlightsTitle }}</h2>
                        <div class="mx-auto mt-4 h-px w-16 bg-brand-gold"></div>
                        <p class="mt-5 text-sm leading-7 text-gray-500 sm:text-base">{{ $highlightsIntro }}</p>
                    </div>
                    <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($highlights as $highlight)
                            <article class="p-2 sm:p-3">
                                <div class="flex items-start gap-2">
                                    <span class="text-xl leading-none text-brand-gold">&#9733;</span>
                                    <p class="text-base leading-8 text-brand-dark">{{ $highlight }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($itineraryDays->isNotEmpty())
            <section class="px-6 pb-16 sm:pb-20">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="mx-auto max-w-3xl text-center">
                        <h2 class="font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ __('messages.itinerary') }}</h2>
                        <div class="mx-auto mt-4 h-px w-16 bg-brand-gold"></div>
                        <p class="mt-5 text-sm leading-7 text-gray-500 sm:text-base">{{ __('messages.itinerary_section_desc') }}</p>
                    </div>

                    <div class="relative mt-12 space-y-12 md:space-y-14" data-timeline>
                        <div class="safari-timeline-line absolute left-1/2 top-0 hidden h-full w-[2px] -translate-x-1/2 md:block"></div>
                        <div class="safari-timeline-progress absolute left-1/2 top-0 hidden h-full w-[2px] -translate-x-1/2 bg-brand-gold md:block"></div>

                        @foreach($itineraryDays as $day)
                            @php
                                $isEven = $loop->index % 2 === 0;
                                $dayImage = $day->image_path ? asset('storage/' . $day->image_path) : ($safari->featured_image ? asset('storage/' . $safari->featured_image) : null);
                            @endphp
                            <article class="safari-timeline-card editorial-reveal relative grid grid-cols-1 items-center gap-8 md:grid-cols-2 md:gap-12" data-reveal data-reveal-delay="{{ $loop->index % 4 }}" data-timeline-item>
                                <div class="safari-timeline-dot absolute left-1/2 top-8 z-10 hidden h-4 w-4 -translate-x-1/2 rounded-full bg-brand-green shadow-[0_0_0_6px_rgba(8,51,33,0.08)] transition-all duration-300 md:block"></div>
                                <div class="safari-day-badge absolute left-1/2 top-[1.6rem] z-10 hidden h-10 w-10 -translate-x-1/2 items-center justify-center rounded-full bg-[#083321] text-[10px] font-semibold uppercase tracking-[0.08em] text-white transition-shadow duration-300 md:flex">
                                    {{ __('messages.day_prefix') }} {{ $day->day_number }}
                                </div>

                                <div class="min-w-0 order-1 {{ $isEven ? 'md:order-1 md:pr-16 md:text-right' : 'md:order-2 md:pl-16 md:text-left' }}">
                                    <div class="safari-day-badge inline-flex items-center rounded-full bg-[#083321] px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-white md:hidden">
                                        {{ __('messages.day_prefix') }} {{ $day->day_number }}
                                    </div>
                                    <div class="mt-4 flex flex-wrap items-center gap-3 text-[11px] uppercase tracking-[0.22em] text-gray-400 {{ $isEven ? 'md:justify-end' : 'md:justify-start' }}">
                                        @if($day->destination)
                                            <span>{{ $day->destination->translated('name') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="mt-4 font-heading text-2xl font-bold text-brand-dark sm:text-3xl">{{ $day->translated('title') }}</h3>
                                    @if($day->translated('description'))
                                        <p class="mt-4 text-base leading-8 text-gray-600">{{ $day->translated('description') }}</p>
                                    @endif

                                    <div class="mt-5 flex flex-wrap gap-3 {{ $isEven ? 'md:justify-end' : 'md:justify-start' }}">
                                        @if($day->destination)
                                            <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm text-brand-dark">
                                                <svg class="h-4 w-4 text-brand-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                                <span>{{ $day->destination->translated('name') }}</span>
                                            </div>
                                        @endif

                                        @if($day->accommodationRelation)
                                            <button
                                                type="button"
                                                @click="openAccommodation({{ $day->accommodationRelation->id }})"
                                                class="safari-accommodation-trigger inline-flex items-center gap-2 rounded-full border border-brand-green px-4 py-2 text-sm font-medium text-brand-green transition hover:bg-brand-green hover:text-white"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 11.204 3.045a1.125 1.125 0 0 1 1.592 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg>
                                                <span>{{ $day->accommodationRelation->translated('name') }}</span>
                                                <svg class="safari-accommodation-arrow h-4 w-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="order-2 {{ $isEven ? 'md:order-2 md:pl-16' : 'md:order-1 md:pr-16' }}">
                                    <div class="overflow-hidden">
                                        @if($dayImage)
                                            <img src="{{ $dayImage }}" alt="{{ $day->translated('title') }}" loading="lazy" class="h-[240px] w-full object-cover sm:h-[320px] lg:h-[380px]">
                                        @else
                                            <div class="flex h-[240px] items-center justify-center bg-gradient-to-br from-brand-green to-brand-dark text-white/70 sm:h-[320px] lg:h-[380px]">
                                                <span class="text-sm uppercase tracking-[0.3em]">{{ __('messages.safari_day') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($accommodationCards->isNotEmpty())
            <section class="editorial-reveal px-6 pb-16 sm:pb-20" data-reveal>
                <div class="mx-auto max-w-7xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <h2 class="font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ __('messages.where_you_stay') }}</h2>
                        <div class="mx-auto mt-4 h-px w-16 bg-brand-gold"></div>
                        <p class="mt-5 text-sm leading-7 text-gray-500 sm:text-base">{{ __('messages.where_you_stay_desc') }}</p>
                    </div>

                    <div class="relative mt-10">
                        <div class="mb-4 flex items-center justify-end gap-2">
                            <button type="button" @click="scrollAccommodationSlider(-1)" class="inline-flex h-10 w-10 items-center justify-center rounded-sm border border-gray-200 bg-white text-brand-dark transition hover:border-brand-green hover:text-brand-green">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </button>
                            <button type="button" @click="scrollAccommodationSlider(1)" class="inline-flex h-10 w-10 items-center justify-center rounded-sm border border-gray-200 bg-white text-brand-dark transition hover:border-brand-green hover:text-brand-green">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        </div>
                        <div x-ref="accommodationScroller" class="safari-slider flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-2 sm:gap-6">
                            @foreach($accommodationCards as $accommodation)
                                @php
                                    $featuredImage = optional($accommodation->images->first())->image_path;
                                @endphp
                                <button type="button" @click="openAccommodation({{ $accommodation->id }})" class="safari-slider-card shrink-0 snap-start text-left">
                                    <div>
                                        <div class="overflow-hidden">
                                            @if($featuredImage)
                                                <img src="{{ asset('storage/' . $featuredImage) }}" alt="{{ $accommodation->translated('name') }}" loading="lazy" class="h-[360px] w-full object-cover sm:h-[400px] lg:h-[440px]">
                                            @else
                                                <div class="flex h-[360px] items-center justify-center bg-gradient-to-br from-brand-green to-brand-dark text-white/70 sm:h-[400px] lg:h-[440px]">
                                                    <svg class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M4.5 18V9.75m0 8.25h15m-15 0v-3.375c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125V18m-15-8.25 4.816-4.012a1.125 1.125 0 0 1 1.44 0l4.819 4.012m-6.259 0V21m6-11.25V21"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="border-b border-gray-200 px-1 py-4">
                                            <h3 class="font-heading text-2xl font-bold text-brand-dark">{{ $accommodation->translated('name') }}</h3>
                                            <p class="one-line-truncate mt-2 text-sm text-gray-600">{{ $accommodation->translated('description') }}</p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if($included->isNotEmpty() || $excluded->isNotEmpty() || $hasSeasonalPricing)
            <section id="pricing-overview" class="editorial-reveal px-6 pb-16 sm:pb-20" data-reveal>
                <div class="mx-auto max-w-6xl">
                    <div class="text-center">
                        <h2 class="font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ $inclusionsTitle }}</h2>
                        <div class="mx-auto mt-4 h-px w-16 bg-brand-gold"></div>
                        <p class="mx-auto mt-5 max-w-3xl text-sm leading-7 text-gray-500 sm:text-base">{{ $inclusionsIntro }}</p>
                    </div>

                    <div class="mt-10 {{ $hasSeasonalPricing ? 'grid grid-cols-1 gap-12 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]' : 'mx-auto max-w-5xl' }}">
                        <div class="grid grid-cols-1 gap-10 {{ $hasSeasonalPricing ? 'md:grid-cols-2 md:gap-8' : 'md:grid-cols-2 md:gap-12' }}">
                            @if($included->isNotEmpty())
                                <section class="{{ $hasSeasonalPricing ? '' : 'mx-auto w-full max-w-md text-left' }}">
                                    <h3 class="font-heading text-2xl font-bold text-brand-dark">Included</h3>
                                    <div class="mt-5 space-y-3">
                                        @foreach($included as $item)
                                            <div class="flex items-start gap-3 text-sm text-brand-dark sm:text-base">
                                                <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-brand-green text-brand-green">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75 10.5 18l9-13.5"/></svg>
                                                </span>
                                                <span class="leading-7">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            @if($excluded->isNotEmpty())
                                <section class="{{ $hasSeasonalPricing ? '' : 'mx-auto w-full max-w-md text-left' }}">
                                    <h3 class="font-heading text-2xl font-bold text-brand-dark">Excluded</h3>
                                    <div class="mt-5 space-y-3">
                                        @foreach($excluded as $item)
                                            <div class="flex items-start gap-3 text-sm text-gray-600 sm:text-base">
                                                <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-gray-300 text-gray-500">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                                </span>
                                                <span class="leading-7">{{ $item }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @endif
                        </div>

                        @if($hasSeasonalPricing)
                            <section>
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <h3 class="font-heading text-2xl font-bold text-brand-dark">{{ __('messages.pricing') }}</h3>
                                    <label class="inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">
                                        {{ __('messages.currency') }}
                                        <select x-model="selectedCurrency" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium tracking-normal text-brand-dark focus:border-brand-gold focus:outline-none">
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                            <option value="TZS">TZS</option>
                                        </select>
                                    </label>
                                </div>
                                <p x-show="currencyError" class="mt-3 text-sm text-gray-500" x-text="currencyError"></p>
                                <div class="mt-5 overflow-x-auto">
                                    <table class="min-w-full border-collapse text-left text-sm text-brand-dark">
                                        <thead>
                                            <tr class="border-b border-gray-200 text-xs uppercase tracking-[0.18em] text-gray-500">
                                                <th class="py-3 pr-4 font-semibold">{{ __('messages.season') }}</th>
                                                <th class="px-4 py-3 text-center font-semibold">{{ __('messages.pax_label', ['count' => 2]) }}</th>
                                                <th class="px-4 py-3 text-center font-semibold">{{ __('messages.pax_label', ['count' => 4]) }}</th>
                                                <th class="px-4 py-3 text-center font-semibold">{{ __('messages.pax_label', ['count' => 6]) }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(['low' => __('messages.low_season'), 'mid' => __('messages.mid_season'), 'high' => __('messages.high_season')] as $seasonKey => $seasonLabel)
                                                @php $row = $seasonalPricing[$seasonKey] ?? []; @endphp
                                                <tr class="border-b border-gray-100 last:border-b-0">
                                                    <td class="py-4 pr-4 font-medium">{{ $seasonLabel }}</td>
                                                    @foreach(['pax_2', 'pax_4', 'pax_6'] as $band)
                                                        <td class="px-4 py-4 text-center text-gray-600">
                                                            @if(filled($row[$band] ?? null))
                                                                <span x-text="formatMoney({{ (float) $row[$band] }})">{{ $currency }} {{ $formatAmount($row[$band]) }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if($relatedSafaris->isNotEmpty())
            <section class="editorial-reveal px-6 pb-16 sm:pb-20" data-reveal>
                <div class="mx-auto max-w-7xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <h2 class="font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ __('messages.related_tours') }}</h2>
                        <div class="mx-auto mt-4 h-px w-16 bg-brand-gold"></div>
                        <p class="mt-5 text-sm leading-7 text-gray-500 sm:text-base">{{ __('messages.related_tours_desc') }}</p>
                    </div>

                    <div class="relative mt-10">
                        <div class="mb-4 flex items-center justify-end gap-2">
                            <button type="button" @click="scrollRelatedToursSlider(-1)" class="inline-flex h-10 w-10 items-center justify-center rounded-sm border border-gray-200 bg-white text-brand-dark transition hover:border-brand-green hover:text-brand-green">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </button>
                            <button type="button" @click="scrollRelatedToursSlider(1)" class="inline-flex h-10 w-10 items-center justify-center rounded-sm border border-gray-200 bg-white text-brand-dark transition hover:border-brand-green hover:text-brand-green">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        </div>

                        <div x-ref="relatedToursScroller" class="safari-slider flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-2 sm:gap-6">
                            @foreach($relatedSafaris as $relatedSafari)
                                <a href="{{ route('safaris.show', $relatedSafari->slug) }}" class="safari-slider-card group shrink-0 snap-start overflow-hidden rounded-xl bg-white text-left shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md">
                                    <div class="overflow-hidden">
                                        @if($relatedSafari->featured_image)
                                            <img src="{{ asset('storage/' . $relatedSafari->featured_image) }}" alt="{{ $relatedSafari->translated('title') }}" loading="lazy" class="h-56 w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                                        @else
                                            <div class="flex h-56 items-center justify-center bg-gradient-to-br from-brand-green to-brand-dark text-white/70">
                                                <span class="text-xs font-semibold uppercase tracking-[0.24em]">Safari</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <div class="mb-3 flex items-center gap-2">
                                            <svg class="h-4 w-4 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-xs font-semibold uppercase tracking-wider text-brand-dark/40">{{ $relatedSafari->duration ?? __('messages.multi_day') }}</span>
                                        </div>
                                        <h3 class="mb-2 font-heading text-xl font-bold text-brand-dark">{{ $relatedSafari->translated('title') }}</h3>
                                        <p class="text-sm leading-relaxed text-brand-dark/50">{{ \Illuminate\Support\Str::limit($relatedSafari->translated('short_description'), 140) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="editorial-reveal relative overflow-hidden bg-[#083321] px-6 py-20 text-center text-white sm:py-24" data-reveal>
            <div class="safari-cta-silhouette absolute inset-0 opacity-[0.08]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.08),transparent_45%)]"></div>
            <div class="relative mx-auto max-w-4xl">
                <h2 class="font-heading text-3xl font-bold sm:text-4xl">{{ __('messages.ready_to_plan') }}</h2>
                <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="{{ route('safaris.pdf', $safari->slug) }}"
                       target="_blank"
                       rel="noopener"
                       class="inline-flex items-center justify-center rounded-sm bg-brand-gold px-8 py-4 text-sm font-semibold text-brand-dark shadow-[0_18px_40px_-28px_rgba(0,0,0,0.45)] transition hover:-translate-y-0.5 hover:bg-yellow-300">
                        <svg class="mr-2 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8-3-3m3 3 3-3M4 20h16"/></svg>
                        {{ __('messages.download_itinerary') }}
                    </a>
                    <a href="{{ route('plan-safari', ['safari_id' => $safari->id]) }}" class="inline-flex items-center justify-center rounded-sm border border-white/25 bg-white/10 px-8 py-4 text-sm font-semibold text-white shadow-[0_18px_40px_-28px_rgba(0,0,0,0.45)] backdrop-blur-sm transition hover:-translate-y-0.5 hover:bg-white/15">
                        {{ __('messages.plan_this_safari') }}
                    </a>
                </div>
            </div>
        </section>

        @if($safari->testimonials->count())
            <section class="editorial-reveal px-6 py-16 sm:py-20" data-reveal>
                <div class="mx-auto max-w-6xl">
                    <div class="mx-auto max-w-3xl text-center">
                        <h2 class="font-heading text-3xl font-bold text-brand-dark sm:text-4xl">{{ __('messages.guest_reviews') }}</h2>
                        <div class="mx-auto mt-4 h-px w-16 bg-brand-gold"></div>
                    </div>

                    <div class="mt-12 grid gap-6 lg:grid-cols-2">
                        @foreach($safari->testimonials as $testimonial)
                            <article class="border border-gray-200 bg-white p-8">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="font-heading text-2xl font-bold text-brand-dark">{{ $testimonial->name }}</h3>
                                        @if($testimonial->rating)
                                            <div class="mt-3 flex items-center gap-1 text-brand-gold">
                                                @for($star = 1; $star <= 5; $star++)
                                                    <span class="text-sm {{ $star <= $testimonial->rating ? 'opacity-100' : 'opacity-20' }}">&#9733;</span>
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-green/10 text-brand-green">{{ strtoupper(substr($testimonial->name, 0, 1)) }}</span>
                                </div>
                                <p class="mt-6 text-base leading-8 text-gray-600">{{ $testimonial->message }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <div class="fixed bottom-5 left-4 z-40 md:bottom-6 md:left-auto md:right-6">
            <div class="safari-float-row flex items-center gap-2" :data-expert-visible="showExpertCta ? 'true' : 'false'">
            @if($startingPriceLabel)
                <button type="button" @click="scrollToPricing()" class="inline-flex items-center justify-center rounded-sm bg-brand-green px-4 py-3 text-sm font-semibold text-white shadow-[0_18px_40px_-26px_rgba(19,20,20,0.55)] transition duration-300 hover:-translate-y-0.5 hover:bg-brand-dark" :class="showExpertCta ? 'pr-5' : ''">
                    <span x-text="durationPriceLabel()">{{ $durationLabel }} | From {{ $startingPriceLabel }}</span>
                </button>
            @endif
                <button type="button" @click="openInquiry()" x-cloak x-show="showExpertCta" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 translate-x-3" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-180" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-3" class="inline-flex items-center justify-center rounded-sm bg-white px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-brand-dark shadow-[0_18px_40px_-26px_rgba(19,20,20,0.55)] transition hover:-translate-y-0.5 hover:text-brand-green">
                    {{ __('messages.speak_to_expert') }}
                </button>
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="accommodationOpen" x-cloak class="fixed inset-0 z-[220] flex items-center justify-center p-4 sm:p-6" @keydown.escape.window="closeAccommodation()">
                <div class="absolute inset-0 bg-black/60" @click="closeAccommodation()"></div>
                <div x-show="accommodationOpen"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative z-10 w-full max-w-6xl overflow-hidden rounded-2xl bg-white shadow-2xl"
                     @click.stop>
                    <button type="button" @click="closeAccommodation()" class="absolute right-4 top-4 z-20 inline-flex h-10 w-10 items-center justify-center rounded-full bg-black/5 text-gray-500 transition hover:bg-black/10 hover:text-gray-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr]">
                        <div class="relative bg-brand-dark" @touchstart.passive="touchStart($event)" @touchend.passive="touchEnd($event)">
                            <template x-if="currentAccommodation && currentAccommodation.images.length">
                                <div class="relative h-[320px] sm:h-[420px] lg:h-[560px]">
                                    <template x-for="(image, index) in currentAccommodation.images" :key="image + index">
                                        <img x-show="currentIndex === index" :src="image" :alt="currentAccommodation.name + ' image ' + (index + 1)"
                                             class="absolute inset-0 h-full w-full object-cover"
                                             x-transition:enter="transition ease-out duration-250"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100">
                                    </template>
                                </div>
                            </template>
                            <template x-if="currentAccommodation && !currentAccommodation.images.length">
                                <div class="flex h-[320px] items-center justify-center sm:h-[420px] lg:h-[560px]">
                                    <svg class="h-16 w-16 text-white/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M4.5 18V9.75m0 8.25h15m-15 0v-3.375c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125V18m-15-8.25 4.816-4.012a1.125 1.125 0 0 1 1.44 0l4.819 4.012m-6.259 0V21m6-11.25V21"/></svg>
                                </div>
                            </template>

                            <template x-if="currentAccommodation && currentAccommodation.images.length > 1">
                                <div>
                                    <button type="button" @click="prev()" class="absolute left-4 top-1/2 z-10 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-black/35 text-white transition hover:bg-black/50">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                                    </button>
                                    <button type="button" @click="next()" class="absolute right-4 top-1/2 z-10 inline-flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-black/35 text-white transition hover:bg-black/50">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                    </button>
                                    <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2">
                                        <template x-for="(image, index) in currentAccommodation.images" :key="'dot-' + image + index">
                                            <button type="button" @click="currentIndex = index" class="h-2.5 w-2.5 rounded-full transition" :class="currentIndex === index ? 'bg-white' : 'bg-white/45'"></button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10">
                            <template x-if="currentAccommodation">
                                <div>
                                    <h3 class="font-heading text-3xl font-bold text-brand-dark" x-text="currentAccommodation.name"></h3>
                                    <p class="mt-5 text-base leading-8 text-gray-600" x-text="currentAccommodation.description || 'No description available yet.'"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div x-show="inquiryOpen" x-cloak class="fixed inset-0 z-[230] flex items-center justify-center p-4 sm:p-6" @keydown.escape.window="closeInquiry()">
                <div class="absolute inset-0 bg-black/65" @click="closeInquiry()"></div>
                <div x-show="inquiryOpen"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative z-10 max-h-[90vh] w-full max-w-6xl overflow-y-auto overflow-x-hidden bg-white shadow-2xl"
                     @click.stop>
                    <button type="button" @click="closeInquiry()" class="absolute right-4 top-4 z-20 inline-flex h-10 w-10 items-center justify-center rounded-full bg-black/5 text-gray-500 transition hover:bg-black/10 hover:text-gray-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="grid grid-cols-1 lg:grid-cols-[1.08fr_0.92fr]">
                        <div class="order-2 px-4 py-5 sm:px-6 sm:py-6 lg:order-1 lg:px-10 lg:py-10">
                            <div x-show="!inquirySubmitted">
                                <h3 class="font-heading text-3xl font-bold text-brand-dark">{{ __('messages.speak_to_expert') }}</h3>
                                <p class="mt-2 text-sm leading-6 text-gray-500">{{ __('messages.inquiry_subtitle') }}</p>

                                <form @submit.prevent="submitInquiryForm()" class="mt-6 space-y-4 lg:mt-8 lg:space-y-5">
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-[128px_minmax(0,1fr)] sm:gap-4">
                                        <div>
                                            <label class="mb-1 block text-sm font-medium text-gray-700">Title</label>
                                            <select x-model="inquiryForm.title" class="w-full border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none">
                                                <option>Mr</option>
                                                <option>Mrs</option>
                                                <option>Miss</option>
                                                <option>Ms</option>
                                                <option>Dr</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-gray-700">{{ __('messages.first_name') }}</label>
                                                <input type="text" x-model="inquiryForm.first_name" required class="w-full border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-sm font-medium text-gray-700">{{ __('messages.last_name') }}</label>
                                                <input type="text" x-model="inquiryForm.last_name" required class="w-full border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none">
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="inquiryErrors.name">
                                        <p class="text-xs text-red-500" x-text="inquiryErrors.name[0]"></p>
                                    </template>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ __('messages.phone') }}</label>
                                        <div class="flex gap-2 sm:gap-3" x-data="phoneCodePicker($root)">
                                            <div class="relative w-32 shrink-0 sm:w-36">
                                                <button type="button" @click="open = !open" class="flex w-full items-center gap-2 border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none">
                                                    <span x-text="selectedCode"></span>
                                                    <svg class="ml-auto h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                                </button>
                                                <div x-show="open" x-cloak @click.away="open = false" class="absolute left-0 top-full z-20 mt-1 w-60 border border-gray-200 bg-white shadow-xl">
                                                    <div class="p-2">
                                                        <input type="text" x-model="search" placeholder="Search code or country" class="w-full border border-gray-200 px-3 py-2 text-xs focus:border-brand-gold focus:outline-none">
                                                    </div>
                                                    <div class="max-h-64 overflow-y-auto">
                                                        <template x-for="item in filteredCodes" :key="item.code + item.name">
                                                            <button type="button" @click="selectCode(item)" class="flex w-full items-center gap-3 px-3 py-2 text-left text-xs text-gray-700 transition hover:bg-brand-gold/10">
                                                                <span x-text="item.code" class="w-12 shrink-0 font-medium text-brand-dark"></span>
                                                                <span x-text="item.name" class="truncate"></span>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="tel" x-model="inquiryForm.phone" class="min-w-0 flex-1 border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none" placeholder="712 345 678">
                                        </div>
                                        <template x-if="inquiryErrors.phone">
                                            <p class="mt-1 text-xs text-red-500" x-text="inquiryErrors.phone[0]"></p>
                                        </template>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ __('messages.email') }}</label>
                                        <input type="email" x-model="inquiryForm.email" required class="w-full border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none" placeholder="you@example.com">
                                        <template x-if="inquiryErrors.email">
                                            <p class="mt-1 text-xs text-red-500" x-text="inquiryErrors.email[0]"></p>
                                        </template>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">{{ __('messages.message') }}</label>
                                        <textarea x-model="inquiryForm.message" rows="4" class="w-full border border-gray-300 px-3 py-2.5 text-sm text-brand-dark focus:border-brand-gold focus:outline-none" placeholder="{{ __('messages.message_placeholder') }}"></textarea>
                                        <template x-if="inquiryErrors.message">
                                            <p class="mt-1 text-xs text-red-500" x-text="inquiryErrors.message[0]"></p>
                                        </template>
                                    </div>

                                    <div class="space-y-2 text-sm text-gray-600">
                                        <label class="flex items-start gap-3">
                                            <input type="checkbox" x-model="inquiryForm.accept_privacy" class="mt-1 h-4 w-4 rounded border-gray-300 text-brand-green focus:ring-brand-green">
                                            <span>
                                                {{ __('messages.accept_privacy') }} <a href="#expert-privacy-note" class="text-brand-green underline underline-offset-2">{{ __('messages.privacy_policy') }}</a>.
                                            </span>
                                        </label>
                                        <template x-if="inquiryErrors.accept_privacy">
                                            <p class="text-xs text-red-500" x-text="inquiryErrors.accept_privacy[0]"></p>
                                        </template>
                                    </div>

                                    <button type="submit" :disabled="inquiryLoading" class="inline-flex w-full items-center justify-center rounded-sm bg-brand-gold px-6 py-3.5 text-sm font-semibold text-brand-dark transition hover:bg-yellow-300 disabled:cursor-not-allowed disabled:opacity-60">
                                        <span x-show="!inquiryLoading">{{ __('messages.speak_to_expert') }}</span>
                                        <span x-show="inquiryLoading" class="inline-flex items-center gap-2">
                                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                            {{ __('messages.sending') }}
                                        </span>
                                    </button>

                                    <p id="expert-privacy-note" x-ref="privacyNote" class="text-xs leading-6 text-gray-400">{{ __('messages.privacy_note') }}</p>
                                </form>
                            </div>

                            <div x-show="inquirySubmitted" x-cloak class="py-10 text-center lg:py-16">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-green/10 text-brand-green">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                <h3 class="mt-6 font-heading text-3xl font-bold text-brand-dark">{{ __('messages.we_have_your_request') }}</h3>
                                <p class="mx-auto mt-4 max-w-md text-sm leading-7 text-gray-500">{{ __('messages.inquiry_success_msg') }}</p>
                                <button type="button" @click="closeInquiry()" class="mt-8 inline-flex items-center justify-center rounded-full bg-brand-gold px-6 py-3 text-sm font-semibold text-brand-dark transition hover:bg-yellow-300">
                                    {{ __('messages.close') }}
                                </button>
                            </div>
                        </div>

                        <aside class="order-1 bg-brand-green text-white lg:order-2">
                            <div class="relative h-full min-h-[18rem] lg:min-h-full">
                                @if($safari->featured_image)
                                    <img src="{{ asset('storage/' . $safari->featured_image) }}" alt="{{ $safari->translated('title') }}" class="absolute inset-0 h-full w-full object-cover">
                                @endif
                                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(8,51,33,0.2)_0%,rgba(8,51,33,0.72)_55%,rgba(8,51,33,0.9)_100%)]"></div>
                                <div class="relative flex h-full flex-col justify-end px-5 py-5 sm:px-6 sm:py-6 lg:px-10 lg:py-10">
                                    <p class="text-[11px] uppercase tracking-[0.22em] text-white/65">{{ __('messages.safari_details') }}</p>
                                    <h3 class="mt-2 font-heading text-2xl font-bold sm:text-3xl">{{ $safari->translated('title') }}</h3>
                                    <div class="mt-5 grid gap-3 text-sm text-white/85">
                                        <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3">
                                            <span>{{ __('messages.days_label') }}</span>
                                            <span class="font-medium text-white">{{ $durationLabel }}</span>
                                        </div>
                                        @if($startingPriceLabel)
                                            <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3">
                                                <span>{{ __('messages.price_from') }}</span>
                                                <span class="font-medium text-white" x-text="startingPriceText()">{{ $startingPriceLabel }}</span>
                                            </div>
                                        @endif
                                        @if($safari->tour_type)
                                            <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3">
                                                <span>{{ __('messages.tour_type') }}</span>
                                                <span class="font-medium text-white">{{ $safari->tour_type }}</span>
                                            </div>
                                        @endif
                                        @if($safari->countries->isNotEmpty())
                                            <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-3">
                                                <span>{{ __('messages.country') }}</span>
                                                <span class="font-medium text-white">{{ $safari->countries->pluck('name')->join(', ') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.js"></script>
<script>
    const PHONE_CODES = [
        {code: '+93', name: 'Afghanistan'}, {code: '+355', name: 'Albania'}, {code: '+213', name: 'Algeria'},
        {code: '+376', name: 'Andorra'}, {code: '+244', name: 'Angola'}, {code: '+1-268', name: 'Antigua and Barbuda'},
        {code: '+54', name: 'Argentina'}, {code: '+374', name: 'Armenia'}, {code: '+61', name: 'Australia'},
        {code: '+43', name: 'Austria'}, {code: '+994', name: 'Azerbaijan'}, {code: '+1-242', name: 'Bahamas'},
        {code: '+973', name: 'Bahrain'}, {code: '+880', name: 'Bangladesh'}, {code: '+1-246', name: 'Barbados'},
        {code: '+375', name: 'Belarus'}, {code: '+32', name: 'Belgium'}, {code: '+501', name: 'Belize'},
        {code: '+229', name: 'Benin'}, {code: '+975', name: 'Bhutan'}, {code: '+591', name: 'Bolivia'},
        {code: '+387', name: 'Bosnia'}, {code: '+267', name: 'Botswana'}, {code: '+55', name: 'Brazil'},
        {code: '+673', name: 'Brunei'}, {code: '+359', name: 'Bulgaria'}, {code: '+226', name: 'Burkina Faso'},
        {code: '+257', name: 'Burundi'}, {code: '+238', name: 'Cabo Verde'}, {code: '+855', name: 'Cambodia'},
        {code: '+237', name: 'Cameroon'}, {code: '+1', name: 'Canada'}, {code: '+236', name: 'Central African Republic'},
        {code: '+235', name: 'Chad'}, {code: '+56', name: 'Chile'}, {code: '+86', name: 'China'},
        {code: '+57', name: 'Colombia'}, {code: '+269', name: 'Comoros'}, {code: '+243', name: 'Congo (DRC)'},
        {code: '+242', name: 'Congo (Republic)'}, {code: '+506', name: 'Costa Rica'}, {code: '+385', name: 'Croatia'},
        {code: '+53', name: 'Cuba'}, {code: '+357', name: 'Cyprus'}, {code: '+420', name: 'Czech Republic'},
        {code: '+45', name: 'Denmark'}, {code: '+253', name: 'Djibouti'}, {code: '+1-767', name: 'Dominica'},
        {code: '+1-809', name: 'Dominican Republic'}, {code: '+670', name: 'East Timor'}, {code: '+593', name: 'Ecuador'},
        {code: '+20', name: 'Egypt'}, {code: '+503', name: 'El Salvador'}, {code: '+240', name: 'Equatorial Guinea'},
        {code: '+291', name: 'Eritrea'}, {code: '+372', name: 'Estonia'}, {code: '+268', name: 'Eswatini'},
        {code: '+251', name: 'Ethiopia'}, {code: '+679', name: 'Fiji'}, {code: '+358', name: 'Finland'},
        {code: '+33', name: 'France'}, {code: '+241', name: 'Gabon'}, {code: '+220', name: 'Gambia'},
        {code: '+995', name: 'Georgia'}, {code: '+49', name: 'Germany'}, {code: '+233', name: 'Ghana'},
        {code: '+30', name: 'Greece'}, {code: '+1-473', name: 'Grenada'}, {code: '+502', name: 'Guatemala'},
        {code: '+224', name: 'Guinea'}, {code: '+245', name: 'Guinea-Bissau'}, {code: '+592', name: 'Guyana'},
        {code: '+509', name: 'Haiti'}, {code: '+504', name: 'Honduras'}, {code: '+36', name: 'Hungary'},
        {code: '+354', name: 'Iceland'}, {code: '+91', name: 'India'}, {code: '+62', name: 'Indonesia'},
        {code: '+98', name: 'Iran'}, {code: '+964', name: 'Iraq'}, {code: '+353', name: 'Ireland'},
        {code: '+972', name: 'Israel'}, {code: '+39', name: 'Italy'}, {code: '+225', name: 'Ivory Coast'},
        {code: '+1-876', name: 'Jamaica'}, {code: '+81', name: 'Japan'}, {code: '+962', name: 'Jordan'},
        {code: '+7', name: 'Kazakhstan'}, {code: '+254', name: 'Kenya'}, {code: '+686', name: 'Kiribati'},
        {code: '+383', name: 'Kosovo'}, {code: '+965', name: 'Kuwait'}, {code: '+996', name: 'Kyrgyzstan'},
        {code: '+856', name: 'Laos'}, {code: '+371', name: 'Latvia'}, {code: '+961', name: 'Lebanon'},
        {code: '+266', name: 'Lesotho'}, {code: '+231', name: 'Liberia'}, {code: '+218', name: 'Libya'},
        {code: '+423', name: 'Liechtenstein'}, {code: '+370', name: 'Lithuania'}, {code: '+352', name: 'Luxembourg'},
        {code: '+261', name: 'Madagascar'}, {code: '+265', name: 'Malawi'}, {code: '+60', name: 'Malaysia'},
        {code: '+960', name: 'Maldives'}, {code: '+223', name: 'Mali'}, {code: '+356', name: 'Malta'},
        {code: '+692', name: 'Marshall Islands'}, {code: '+222', name: 'Mauritania'}, {code: '+230', name: 'Mauritius'},
        {code: '+52', name: 'Mexico'}, {code: '+691', name: 'Micronesia'}, {code: '+373', name: 'Moldova'},
        {code: '+377', name: 'Monaco'}, {code: '+976', name: 'Mongolia'}, {code: '+382', name: 'Montenegro'},
        {code: '+212', name: 'Morocco'}, {code: '+258', name: 'Mozambique'}, {code: '+95', name: 'Myanmar'},
        {code: '+264', name: 'Namibia'}, {code: '+674', name: 'Nauru'}, {code: '+977', name: 'Nepal'},
        {code: '+31', name: 'Netherlands'}, {code: '+64', name: 'New Zealand'}, {code: '+505', name: 'Nicaragua'},
        {code: '+227', name: 'Niger'}, {code: '+234', name: 'Nigeria'}, {code: '+850', name: 'North Korea'},
        {code: '+389', name: 'North Macedonia'}, {code: '+47', name: 'Norway'}, {code: '+968', name: 'Oman'},
        {code: '+92', name: 'Pakistan'}, {code: '+680', name: 'Palau'}, {code: '+970', name: 'Palestine'},
        {code: '+507', name: 'Panama'}, {code: '+675', name: 'Papua New Guinea'}, {code: '+595', name: 'Paraguay'},
        {code: '+51', name: 'Peru'}, {code: '+63', name: 'Philippines'}, {code: '+48', name: 'Poland'},
        {code: '+351', name: 'Portugal'}, {code: '+974', name: 'Qatar'}, {code: '+40', name: 'Romania'},
        {code: '+7', name: 'Russia'}, {code: '+250', name: 'Rwanda'}, {code: '+1-869', name: 'Saint Kitts and Nevis'},
        {code: '+1-758', name: 'Saint Lucia'}, {code: '+1-784', name: 'St. Vincent'}, {code: '+685', name: 'Samoa'},
        {code: '+378', name: 'San Marino'}, {code: '+239', name: 'Sao Tome'}, {code: '+966', name: 'Saudi Arabia'},
        {code: '+221', name: 'Senegal'}, {code: '+381', name: 'Serbia'}, {code: '+248', name: 'Seychelles'},
        {code: '+232', name: 'Sierra Leone'}, {code: '+65', name: 'Singapore'}, {code: '+421', name: 'Slovakia'},
        {code: '+386', name: 'Slovenia'}, {code: '+677', name: 'Solomon Islands'}, {code: '+252', name: 'Somalia'},
        {code: '+27', name: 'South Africa'}, {code: '+82', name: 'South Korea'}, {code: '+211', name: 'South Sudan'},
        {code: '+34', name: 'Spain'}, {code: '+94', name: 'Sri Lanka'}, {code: '+249', name: 'Sudan'},
        {code: '+597', name: 'Suriname'}, {code: '+46', name: 'Sweden'}, {code: '+41', name: 'Switzerland'},
        {code: '+963', name: 'Syria'}, {code: '+886', name: 'Taiwan'}, {code: '+992', name: 'Tajikistan'},
        {code: '+255', name: 'Tanzania'}, {code: '+66', name: 'Thailand'}, {code: '+228', name: 'Togo'},
        {code: '+676', name: 'Tonga'}, {code: '+1-868', name: 'Trinidad and Tobago'}, {code: '+216', name: 'Tunisia'},
        {code: '+90', name: 'Turkey'}, {code: '+993', name: 'Turkmenistan'}, {code: '+688', name: 'Tuvalu'},
        {code: '+256', name: 'Uganda'}, {code: '+380', name: 'Ukraine'}, {code: '+971', name: 'UAE'},
        {code: '+44', name: 'United Kingdom'}, {code: '+1', name: 'United States'}, {code: '+598', name: 'Uruguay'},
        {code: '+998', name: 'Uzbekistan'}, {code: '+678', name: 'Vanuatu'}, {code: '+39', name: 'Vatican City'},
        {code: '+58', name: 'Venezuela'}, {code: '+84', name: 'Vietnam'}, {code: '+967', name: 'Yemen'},
        {code: '+260', name: 'Zambia'}, {code: '+263', name: 'Zimbabwe'}
    ];

    function phoneCodePicker(root) {
        return {
            open: false,
            search: '',
            selectedCode: '+255',
            filteredCodes: PHONE_CODES,
            init() {
                this.selectedCode = root.inquiryForm.phone_code || '+255';
            },
            selectCode(item) {
                this.selectedCode = item.code;
                root.inquiryForm.phone_code = item.code;
                this.open = false;
                this.search = '';
            },
            get filteredCodes() {
                if (!this.search) {
                    return PHONE_CODES;
                }

                const query = this.search.toLowerCase();
                return PHONE_CODES.filter((item) => item.name.toLowerCase().includes(query) || item.code.includes(query));
            },
        };
    }

    function safariDetailPage(config) {
        return {
            accommodations: config.accommodations || [],
            itinerary: config.itinerary || [],
            pricingMatrix: config.pricingMatrix || {},
            safariId: config.safariId,
            safariTitle: config.safariTitle,
            safariDuration: config.safariDuration,
            safariPrice: config.safariPrice,
            startingPriceValue: typeof config.startingPriceValue === 'number' ? config.startingPriceValue : null,
            baseCurrency: config.baseCurrency || 'USD',
            selectedCurrency: config.baseCurrency || 'USD',
            exchangeRateApi: config.exchangeRateApi,
            exchangeRates: {},
            currencyError: '',
            mapboxToken: config.mapboxToken || '',
            mapStops: config.mapStops || [],
            mapInstance: null,
            canRenderMap: !!config.mapboxToken && Array.isArray(config.mapStops) && config.mapStops.length > 0,
            mapMessage: !config.mapboxToken
                ? 'Mapbox is not configured yet, so the route map is temporarily unavailable.'
                : ((config.mapStops || []).length === 0
                    ? 'Add itinerary destinations with coordinates to display this safari route on the map.'
                    : 'Loading map...'),
            safariImage: config.safariImage,
            inquiryRoute: config.inquiryRoute,
            csrfToken: config.csrfToken,
            showExpertCta: false,
            accommodationOpen: false,
            inquiryOpen: false,
            currentAccommodation: null,
            currentIndex: 0,
            touchStartX: 0,
            inquiryLoading: false,
            inquirySubmitted: false,
            inquiryErrors: {},
            inquiryForm: {
                title: 'Mr',
                first_name: '',
                last_name: '',
                phone_code: '+255',
                phone: '',
                email: '',
                message: '',
                accept_privacy: false,
                receive_updates: false,
            },
            init() {
                const updateFloatingCta = () => {
                    this.showExpertCta = window.scrollY >= 100;
                };

                updateFloatingCta();
                window.addEventListener('scroll', updateFloatingCta, { passive: true });
                window.addEventListener('resize', () => this.resizeOverviewMap(), { passive: true });
                this.loadExchangeRates();
                this.$nextTick(() => this.initializeOverviewMap());
            },
            formatMoney(amount) {
                const numericAmount = Number(amount);
                if (!Number.isFinite(numericAmount)) {
                    return '';
                }

                const rate = this.selectedCurrency === this.baseCurrency
                    ? 1
                    : this.exchangeRates[this.selectedCurrency];
                const convertedAmount = typeof rate === 'number' ? numericAmount * rate : numericAmount;
                const effectiveCurrency = this.selectedCurrency === this.baseCurrency || typeof rate === 'number'
                    ? this.selectedCurrency
                    : this.baseCurrency;

                return `${effectiveCurrency} ${Math.round(convertedAmount).toLocaleString('en-US')}`;
            },
            startingPriceText() {
                return this.startingPriceValue !== null ? this.formatMoney(this.startingPriceValue) : '';
            },
            durationPriceLabel() {
                if (this.startingPriceValue === null) {
                    return this.safariDuration;
                }

                return `${this.safariDuration} | From ${this.startingPriceText()}`;
            },
            async loadExchangeRates() {
                if (!this.exchangeRateApi || !this.baseCurrency || this.baseCurrency === '') {
                    return;
                }

                this.exchangeRates = { [this.baseCurrency]: 1 };
                this.currencyError = '';

                try {
                    const response = await fetch(`${this.exchangeRateApi}/${encodeURIComponent(this.baseCurrency)}`);
                    if (!response.ok) {
                        throw new Error(`Failed with status ${response.status}`);
                    }

                    const data = await response.json();
                    if (data && data.result === 'success' && data.rates) {
                        this.exchangeRates = { ...data.rates, [this.baseCurrency]: 1 };
                        return;
                    }

                    throw new Error('Rates payload missing');
                } catch (error) {
                    console.error('Currency conversion error:', error);
                    this.currencyError = 'Live conversion is temporarily unavailable. Showing base prices instead.';
                    this.exchangeRates = { [this.baseCurrency]: 1 };
                }
            },
            initializeOverviewMap() {
                if (!this.$refs.overviewMap) {
                    return;
                }

                if (!this.mapboxToken) {
                    this.canRenderMap = false;
                    this.mapMessage = 'Mapbox is not configured yet, so the route map is temporarily unavailable.';
                    return;
                }

                if (!Array.isArray(this.mapStops) || this.mapStops.length === 0) {
                    this.canRenderMap = false;
                    this.mapMessage = 'Add itinerary destinations with coordinates to display this safari route on the map.';
                    return;
                }

                if (typeof mapboxgl === 'undefined') {
                    this.canRenderMap = false;
                    this.mapMessage = 'The route map library did not load correctly.';
                    return;
                }

                mapboxgl.accessToken = this.mapboxToken;
                this.canRenderMap = true;
                this.mapInstance = new mapboxgl.Map({
                    container: this.$refs.overviewMap,
                    style: 'mapbox://styles/mapbox/light-v11',
                    center: [this.mapStops[0].longitude, this.mapStops[0].latitude],
                    zoom: this.mapStops.length > 1 ? 5.2 : 7,
                    attributionControl: false,
                });

                this.mapInstance.addControl(new mapboxgl.NavigationControl({ showCompass: false, visualizePitch: false }), 'top-left');

                this.mapInstance.on('load', () => {
                    this.applyMapVisualCleanup();
                    this.resizeOverviewMap();
                    const bounds = new mapboxgl.LngLatBounds();
                    const routeCoordinates = this.mapStops.map((stop) => {
                        const coordinates = [stop.longitude, stop.latitude];
                        bounds.extend(coordinates);
                        return coordinates;
                    });

                    const curvedCoordinates = this.buildCurvedRoute(routeCoordinates);

                    if (routeCoordinates.length > 1) {
                        this.mapInstance.addSource('safari-route', {
                            type: 'geojson',
                            data: {
                                type: 'Feature',
                                geometry: {
                                    type: 'LineString',
                                    coordinates: curvedCoordinates,
                                },
                            },
                        });

                        this.mapInstance.addLayer({
                            id: 'safari-route-accent',
                            type: 'line',
                            source: 'safari-route',
                            layout: {
                                'line-cap': 'round',
                                'line-join': 'round',
                            },
                            paint: {
                                'line-color': '#D6CDC0',
                                'line-width': 5,
                                'line-opacity': 0,
                            },
                        });

                        this.mapInstance.addLayer({
                            id: 'safari-route-line',
                            type: 'line',
                            source: 'safari-route',
                            layout: {
                                'line-cap': 'round',
                                'line-join': 'round',
                            },
                            paint: {
                                'line-color': '#131414',
                                'line-width': 2,
                                'line-opacity': 0,
                            },
                        });

                        requestAnimationFrame(() => {
                            this.mapInstance.setPaintProperty('safari-route-accent', 'line-opacity', 0.16);
                            this.mapInstance.setPaintProperty('safari-route-line', 'line-opacity', 0.7);
                        });
                    }

                    this.mapStops.forEach((stop, index) => {
                        console.debug('Safari map stop', stop.latitude, stop.longitude, stop.name);

                        const markerElement = document.createElement('div');
                        const isImportant = index === 0 || index === this.mapStops.length - 1;
                        markerElement.className = `safari-map-marker${isImportant ? ' is-important' : ''}`;

                        const inner = document.createElement('div');
                        inner.className = 'safari-map-marker-inner';

                        const badge = document.createElement('div');
                        badge.className = 'safari-map-marker-badge';
                        badge.textContent = stop.label || stop.name;

                        const connector = document.createElement('div');
                        connector.className = 'safari-map-marker-connector';

                        const dot = document.createElement('div');
                        dot.className = 'safari-map-marker-dot';

                        inner.appendChild(badge);
                        inner.appendChild(connector);
                        inner.appendChild(dot);
                        markerElement.appendChild(inner);

                        new mapboxgl.Marker({ element: markerElement, anchor: 'center' })
                            .setLngLat([stop.longitude, stop.latitude])
                            .addTo(this.mapInstance);
                    });

                    this.mapInstance.on('moveend', () => {
                        this.syncMapPresentation();
                    });

                    this.mapInstance.on('zoomend', () => {
                        this.syncMapPresentation();
                    });

                    if (routeCoordinates.length === 1) {
                        this.mapInstance.flyTo({ center: routeCoordinates[0], zoom: 7, essential: true });
                        this.syncMapPresentation();
                        return;
                    }

                    this.mapInstance.fitBounds(bounds, { padding: 80, maxZoom: 7 });
                    this.mapInstance.once('idle', () => {
                        this.syncMapPresentation();
                    });
                });
            },
            syncMapPresentation() {
                this.resizeOverviewMap();
                this.updateRouteGeometry();
            },
            applyMapVisualCleanup() {
                if (!this.mapInstance || typeof this.mapInstance.getStyle !== 'function') {
                    return;
                }

                const style = this.mapInstance.getStyle();
                const layers = style?.layers || [];
                const hideMatchers = [
                    /poi/i,
                    /transit/i,
                    /building/i,
                ];

                const warmPaintRules = [
                    { matcher: /^background$/, property: 'background-color', value: '#f5f0e8' },
                    { matcher: /water/i, property: 'fill-color', value: '#d2e0da' },
                    { matcher: /water/i, property: 'line-color', value: '#d2e0da' },
                    { matcher: /landcover|land/i, property: 'fill-color', value: '#ede5d8' },
                    { matcher: /national-park|park|landuse/i, property: 'fill-color', value: '#dae2d0' },
                    { matcher: /national-park|park|landuse/i, property: 'line-color', value: '#c6d0bc' },
                    { matcher: /country-label|state-label|continent/i, property: 'text-color', value: '#5c574f' },
                    { matcher: /country-label|state-label|continent/i, property: 'text-halo-color', value: 'rgba(245, 240, 232, 0.92)' },
                    { matcher: /settlement-major/i, property: 'text-color', value: '#4a453e' },
                    { matcher: /settlement-minor|settlement-subdivision/i, property: 'text-color', value: '#7a756d' },
                    { matcher: /natural-label/i, property: 'text-color', value: '#6e8c6a' },
                    { matcher: /road-label|road-number|street/i, property: 'text-color', value: '#9a958e' },
                    { matcher: /road-label|road-number|street/i, property: 'text-halo-color', value: 'rgba(245, 240, 232, 0.85)' },
                    { matcher: /admin/i, property: 'line-color', value: '#c9c3b8' },
                ];

                layers.forEach((layer) => {
                    if (!layer?.id) {
                        return;
                    }

                    const shouldHide = hideMatchers.some((matcher) => matcher.test(layer.id));
                    if (shouldHide) {
                        this.mapInstance.setLayoutProperty(layer.id, 'visibility', 'none');
                        return;
                    }

                    warmPaintRules.forEach((rule) => {
                        if (!rule.matcher.test(layer.id)) {
                            return;
                        }

                        try {
                            this.mapInstance.setPaintProperty(layer.id, rule.property, rule.value);
                        } catch (error) {
                            // Ignore layers that do not support the targeted paint property.
                        }
                    });
                });
            },
            updateRouteGeometry() {
                if (!this.mapInstance || this.mapStops.length < 2) {
                    return;
                }

                const routeSource = this.mapInstance.getSource('safari-route');
                if (!routeSource) {
                    return;
                }

                const routeCoordinates = this.mapStops.map((stop) => [stop.longitude, stop.latitude]);
                const curvedCoordinates = this.buildCurvedRoute(routeCoordinates);

                routeSource.setData({
                    type: 'Feature',
                    geometry: {
                        type: 'LineString',
                        coordinates: curvedCoordinates,
                    },
                });
            },
            resizeOverviewMap() {
                if (!this.mapInstance) {
                    return;
                }

                requestAnimationFrame(() => {
                    this.mapInstance.resize();
                });

                setTimeout(() => {
                    if (this.mapInstance) {
                        this.mapInstance.resize();
                    }
                }, 180);
            },
            buildCurvedRoute(coordinates) {
                if (!Array.isArray(coordinates) || coordinates.length < 2 || !this.mapInstance) {
                    return coordinates;
                }

                const projectedPoints = coordinates.map(([longitude, latitude]) => this.mapInstance.project([longitude, latitude]));
                const curved = [coordinates[0]];

                for (let index = 0; index < projectedPoints.length - 1; index += 1) {
                    const start = projectedPoints[index];
                    const end = projectedPoints[index + 1];
                    const dx = end.x - start.x;
                    const dy = end.y - start.y;
                    const distance = Math.hypot(dx, dy) || 1;

                    if (distance < 120) {
                        curved.push(coordinates[index + 1]);
                        continue;
                    }

                    const mid = [
                        (start.x + end.x) / 2,
                        (start.y + end.y) / 2,
                    ];
                    const curvature = Math.min(10, Math.max(3, distance * 0.045));
                    const normal = [(-dy / distance) * curvature, (dx / distance) * curvature];
                    const direction = index % 2 === 0 ? 1 : -1;
                    const control = [
                        mid[0] + (normal[0] * direction),
                        mid[1] + (normal[1] * direction),
                    ];

                    for (let step = 1; step <= 10; step += 1) {
                        const t = step / 10;
                        const x = ((1 - t) ** 2 * start.x) + (2 * (1 - t) * t * control[0]) + ((t ** 2) * end.x);
                        const y = ((1 - t) ** 2 * start.y) + (2 * (1 - t) * t * control[1]) + ((t ** 2) * end.y);
                        const lngLat = this.mapInstance.unproject([x, y]);
                        curved.push([lngLat.lng, lngLat.lat]);
                    }
                }

                return curved;
            },
            openAccommodation(id) {
                const match = this.accommodations.find((item) => String(item.id) === String(id));
                if (!match) {
                    return;
                }

                this.currentAccommodation = match;
                this.currentIndex = 0;
                this.accommodationOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeAccommodation() {
                this.accommodationOpen = false;
                this.currentAccommodation = null;
                this.currentIndex = 0;
                if (!this.inquiryOpen) {
                    document.body.classList.remove('overflow-hidden');
                }
            },
            next() {
                if (!this.currentAccommodation || this.currentAccommodation.images.length <= 1) {
                    return;
                }

                this.currentIndex = (this.currentIndex + 1) % this.currentAccommodation.images.length;
            },
            prev() {
                if (!this.currentAccommodation || this.currentAccommodation.images.length <= 1) {
                    return;
                }

                this.currentIndex = (this.currentIndex - 1 + this.currentAccommodation.images.length) % this.currentAccommodation.images.length;
            },
            touchStart(event) {
                this.touchStartX = event.changedTouches[0]?.clientX || 0;
            },
            touchEnd(event) {
                const endX = event.changedTouches[0]?.clientX || 0;
                const delta = endX - this.touchStartX;

                if (Math.abs(delta) < 40) {
                    return;
                }

                if (delta < 0) {
                    this.next();
                    return;
                }

                this.prev();
            },
            scrollAccommodationSlider(direction) {
                if (!this.$refs.accommodationScroller) {
                    return;
                }

                const firstCard = this.$refs.accommodationScroller.querySelector('.safari-slider-card');
                const styles = window.getComputedStyle(this.$refs.accommodationScroller);
                const gap = parseFloat(styles.columnGap || styles.gap || '0');
                const amount = ((firstCard?.getBoundingClientRect().width || this.$refs.accommodationScroller.clientWidth) + gap) * direction;
                this.$refs.accommodationScroller.scrollBy({ left: amount, behavior: 'smooth' });
            },
            scrollRelatedToursSlider(direction) {
                if (!this.$refs.relatedToursScroller) {
                    return;
                }

                const firstCard = this.$refs.relatedToursScroller.querySelector('.safari-slider-card');
                const styles = window.getComputedStyle(this.$refs.relatedToursScroller);
                const gap = parseFloat(styles.columnGap || styles.gap || '0');
                const amount = ((firstCard?.getBoundingClientRect().width || this.$refs.relatedToursScroller.clientWidth) + gap) * direction;
                this.$refs.relatedToursScroller.scrollBy({ left: amount, behavior: 'smooth' });
            },
            scrollToPricing() {
                const el = document.getElementById('pricing-overview');
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            },
            downloadItinerary() {
                const lines = [
                    this.safariTitle,
                    this.safariDuration,
                    this.startingPriceValue !== null ? `From ${this.startingPriceText()}` : '',
                    '',
                    ...this.itinerary.flatMap((day) => {
                        return [
                            `Day ${day.day_number}: ${day.title}`,
                            day.destination ? `Destination: ${day.destination}` : '',
                            day.accommodation ? `Accommodation: ${day.accommodation}` : '',
                            day.description || '',
                            '',
                        ];
                    }),
                ].filter(Boolean);

                const blob = new Blob([lines.join('\n')], { type: 'text/plain;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `${this.safariTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-')}-itinerary.txt`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            },
            openInquiry() {
                this.inquiryOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeInquiry() {
                this.inquiryOpen = false;
                if (!this.accommodationOpen) {
                    document.body.classList.remove('overflow-hidden');
                }
            },
            async submitInquiryForm() {
                this.inquiryLoading = true;
                this.inquiryErrors = {};

                if (!this.inquiryForm.accept_privacy) {
                    this.inquiryErrors.accept_privacy = ['Please accept the privacy policy to continue.'];
                    this.inquiryLoading = false;
                    return;
                }

                const fullName = [this.inquiryForm.title, this.inquiryForm.first_name, this.inquiryForm.last_name]
                    .filter(Boolean)
                    .join(' ')
                    .trim();

                const messageParts = [this.inquiryForm.message?.trim()];

                const payload = {
                    safari_package_id: this.safariId,
                    inquiry_type: 'inquiry',
                    name: fullName,
                    email: this.inquiryForm.email,
                    phone: this.inquiryForm.phone ? `${this.inquiryForm.phone_code} ${this.inquiryForm.phone}`.trim() : '',
                    message: messageParts.filter(Boolean).join('\n\n'),
                };

                try {
                    const res = await fetch(this.inquiryRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    if (res.ok) {
                        this.inquirySubmitted = true;
                        return;
                    }

                    if (res.status === 422) {
                        const data = await res.json();
                        this.inquiryErrors = data.errors || {};
                    }
                } catch (error) {
                    console.error('Inquiry modal submission error:', error);
                } finally {
                    this.inquiryLoading = false;
                }
            },
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        const revealItems = document.querySelectorAll('[data-reveal]');
        if (!('IntersectionObserver' in window) || revealItems.length === 0) {
            revealItems.forEach((item) => item.classList.add('is-visible'));
        } else {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    const delayIndex = Number(entry.target.getAttribute('data-reveal-delay') || 0);
                    entry.target.style.transitionDelay = `${delayIndex * 90}ms`;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.18, rootMargin: '0px 0px -40px 0px' });

            revealItems.forEach((item) => observer.observe(item));
        }

        const timelineRoot = document.querySelector('[data-timeline]');
        if (!timelineRoot) {
            return;
        }

        const timelineItems = Array.from(timelineRoot.querySelectorAll('[data-timeline-item]'));
        const progressLine = timelineRoot.querySelector('.safari-timeline-progress');

        const updateTimelineProgress = () => {
            const rect = timelineRoot.getBoundingClientRect();
            const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
            const start = Math.min(viewportHeight * 0.82, viewportHeight - 80);
            const end = rect.height + viewportHeight * 0.18;
            const progress = Math.min(Math.max((start - rect.top) / end, 0), 1);

            if (progressLine) {
                progressLine.style.setProperty('--timeline-progress', progress.toFixed(3));
            }

            timelineItems.forEach((item) => {
                const itemRect = item.getBoundingClientRect();
                const itemMidpoint = itemRect.top + (itemRect.height * 0.28);
                item.classList.toggle('is-active', itemMidpoint <= start + 24);
            });
        };

        updateTimelineProgress();
        window.addEventListener('scroll', updateTimelineProgress, { passive: true });
        window.addEventListener('resize', updateTimelineProgress);
    });
</script>
@endpush
