@extends('layouts.app')

@section('title', $destination->translated('name') . ' | ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
@if($destination->latitude && $destination->longitude && config('services.mapbox.token'))
<link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet">
@endif
{{-- ADDED: destination page prose typography --}}
<style>
    .dest-prose h2 { font-family: 'Cormorant Garamond', serif; font-size: 1.875rem; font-weight: 700; color: #131414; margin-top: 2.5rem; margin-bottom: 1rem; line-height: 1.2; }
    .dest-prose h3 { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 700; color: #131414; margin-top: 2rem; margin-bottom: 0.75rem; line-height: 1.3; }
    .dest-prose h4 { font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; font-weight: 600; color: #131414; margin-top: 1.5rem; margin-bottom: 0.625rem; }
    .dest-prose p { margin-bottom: 1.25rem; line-height: 1.8; color: rgba(19,20,20,0.85); }
    .dest-prose ul, .dest-prose ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
    .dest-prose li { margin-bottom: 0.5rem; line-height: 1.7; color: rgba(19,20,20,0.85); }
    .dest-prose ul li { list-style-type: disc; }
    .dest-prose ol li { list-style-type: decimal; }
    .dest-prose strong { color: #131414; font-weight: 600; }
    .dest-prose a { color: #083321; text-decoration: underline; }
    .dest-prose blockquote { border-left: 4px solid #FEBC11; padding-left: 1.25rem; margin: 1.5rem 0; font-style: italic; color: rgba(19,20,20,0.75); }
    .dest-prose img { border-radius: 0.75rem; margin: 1.5rem 0; }
    @media (min-width: 768px) {
        .dest-prose h2 { font-size: 2.25rem; }
        .dest-prose h3 { font-size: 1.75rem; }
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative h-[50vh] min-h-[380px] overflow-hidden">
    @if($destination->featured_image)
        <img src="{{ asset('storage/' . $destination->featured_image) }}"
             alt="{{ $destination->translated('name') }}"
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=900&fit=crop&q=60"
             alt="{{ $destination->translated('name') }}"
             class="absolute inset-0 w-full h-full object-cover">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/30 to-transparent"></div>
    <div class="relative z-10 h-full flex flex-col items-center justify-center max-w-7xl mx-auto px-6 text-center">
        <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.explore_destination') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">{{ $destination->translated('name') }}</h1>
        @if($destination->country)
            <p class="mt-3 text-lg text-white/85">{{ $destination->country->name }}</p>
        @endif
        <nav class="mt-5 flex items-center justify-center gap-2 text-sm text-white/70">
            <a href="{{ route('home') }}" class="hover:text-white transition">{{ __('messages.home') }}</a>
            <span class="text-white/40">/</span>
            <a href="{{ route('destinations.index') }}" class="hover:text-white transition">{{ __('messages.destinations') }}</a>
            <span class="text-white/40">/</span>
            <span class="text-white/90">{{ $destination->translated('name') }}</span>
        </nav>
    </div>
</section>

{{-- UPDATED: Description with improved typography --}}
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        {{-- FIXED: H2 size increased for proper hierarchy --}}
        <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark mb-8">{{ __('messages.about_destination') }}</h2>

        @if($destination->translated('description'))
            {{-- FIXED: Rich text renders with proper heading/paragraph hierarchy --}}
            <div class="dest-prose text-base md:text-lg max-w-none leading-relaxed">
                {!! $destination->translated('description') !!}
            </div>
        @else
            <p class="text-brand-dark/70 italic text-lg">{{ __('messages.no_description_available') }}</p>
        @endif

        {{-- Quick facts --}}
        @if($destination->latitude && $destination->longitude)
            <div class="mt-10 pt-8 border-t border-gray-100 flex flex-wrap gap-8">
                <div class="flex items-center gap-3 text-sm text-brand-dark/80">
                    <div class="w-9 h-9 rounded-full bg-[#FEBC11]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span>{{ number_format($destination->latitude, 4) }}&deg;, {{ number_format($destination->longitude, 4) }}&deg;</span>
                </div>
                @if($destination->country)
                    <div class="flex items-center gap-3 text-sm text-brand-dark/80">
                        <div class="w-9 h-9 rounded-full bg-[#FEBC11]/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span>{{ $destination->country->name }}</span>
                    </div>
                @endif
                @if($destination->safariPackages->count())
                    <div class="flex items-center gap-3 text-sm text-brand-dark/80">
                        <div class="w-9 h-9 rounded-full bg-[#FEBC11]/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        </div>
                        <span>{{ $destination->safariPackages->count() }} {{ \Illuminate\Support\Str::plural('Safari', $destination->safariPackages->count()) }}</span>
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- Map --}}
@if($destination->latitude && $destination->longitude && config('services.mapbox.token'))
<section class="py-12 bg-brand-light">
    <div class="max-w-7xl mx-auto px-6">
        <div id="destination-map" class="w-full h-80 md:h-96 rounded-xl overflow-hidden shadow-md"></div>
    </div>
</section>
@endif

{{-- UPDATED: Available Safaris — redesigned premium cards --}}
@if($destination->safariPackages->count())
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">
            <p class="font-accent text-xl md:text-2xl text-brand-gold mb-2">{{ __('messages.featured_in_safaris') }}</p>
            {{-- FIXED: H2 size prominent --}}
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-brand-dark leading-tight">
                {{ __('messages.available_safaris_in', ['destination' => $destination->translated('name')]) }}
            </h2>
        </div>

        {{-- UPDATED: larger cards, 3-col max, with description + CTA button --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($destination->safariPackages as $safari)
                @php
                    $locale = app()->getLocale();
                    $days = null;
                    $nights = null;
                    if ($safari->duration) {
                        preg_match('/(\d+)\s*day/i', $safari->duration, $dm);
                        preg_match('/(\d+)\s*night/i', $safari->duration, $nm);
                        $days = $dm[1] ?? null;
                        $nights = $nm[1] ?? null;
                        if ($days && !$nights) $nights = max(1, (int)$days - 1);
                    }
                    $whatsappNum = optional($siteSetting ?? null)->whatsapp_number;
                    $whatsappMsg = urlencode("Hi, I'm interested in the \"{$safari->translated('title')}\" safari to {$destination->translated('name')}. Can you help me plan?");
                @endphp
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:-translate-y-2 hover:shadow-xl transition-all duration-500">
                    {{-- UPDATED: Larger image --}}
                    <a href="{{ route('safaris.show', ['locale' => $locale, 'slug' => $safari->slug]) }}" class="block overflow-hidden">
                        @if($safari->featured_image)
                            <img src="{{ asset('storage/' . $safari->featured_image) }}"
                                 alt="{{ $safari->translated('title') }}" loading="lazy"
                                 class="w-full h-56 md:h-64 object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        @else
                            <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=460&fit=crop&q=80"
                                 alt="{{ $safari->translated('title') }}" loading="lazy"
                                 class="w-full h-56 md:h-64 object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        @endif
                    </a>
                    <div class="p-6">
                        {{-- UPDATED: Duration at top with calendar icon --}}
                        @if($days)
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-gold mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $days }} {{ \Illuminate\Support\Str::plural('Day', (int)$days) }} / {{ $nights }} {{ \Illuminate\Support\Str::plural('Night', (int)$nights) }}
                            </div>
                        @endif

                        {{-- FIXED: Title with larger font --}}
                        <a href="{{ route('safaris.show', ['locale' => $locale, 'slug' => $safari->slug]) }}">
                            <h3 class="font-heading text-xl md:text-2xl font-bold text-brand-dark mb-3 line-clamp-2 group-hover:text-[#083321] transition-colors duration-300">
                                {{ $safari->translated('title') }}
                            </h3>
                        </a>

                        {{-- ADDED: Description — max 3 lines --}}
                        @if($safari->translated('short_description'))
                            <p class="text-sm text-brand-dark/70 leading-relaxed mb-5 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($safari->translated('short_description')), 150) }}
                            </p>
                        @endif

                        {{-- ADDED: Action buttons --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('safaris.show', ['locale' => $locale, 'slug' => $safari->slug]) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#083321] text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-[#083321]/90 transition-all duration-300">
                                {{ __('messages.view_safari') }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                            {{-- ADDED: WhatsApp CTA --}}
                            @if($whatsappNum)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNum) }}?text={{ $whatsappMsg }}"
                               target="_blank" rel="noopener"
                               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all duration-300"
                               title="Plan on WhatsApp">
                                <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.725-1.394A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.308-.724-5.993-1.953l-.42-.307-3.063.904.842-3.132-.327-.44A9.964 9.964 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ADDED: Destination CTA section --}}
@php
    $whatsappNum = optional($siteSetting ?? null)->whatsapp_number;
    $ctaMsg = urlencode("Hi, I'm interested in a safari to {$destination->translated('name')}. Can you help me plan?");
@endphp
<section class="relative py-20 md:py-28 overflow-hidden bg-[#083321]">
    <div class="absolute inset-0 opacity-10">
        @if($destination->featured_image)
            <img src="{{ asset('storage/' . $destination->featured_image) }}" alt="" class="w-full h-full object-cover" loading="lazy">
        @endif
    </div>
    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <p class="font-accent text-xl text-[#FEBC11] mb-3">{{ __('messages.ready_for_adventure') }}</p>
        <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-5 leading-tight">
            {{ __('messages.plan_safari_to', ['destination' => $destination->translated('name')]) }}
        </h2>
        <p class="text-white/80 text-base md:text-lg mb-10 max-w-xl mx-auto leading-relaxed">
            {{ __('messages.destination_cta_text') }}
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('plan-safari', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-[#FEBC11] text-[#131414] font-bold uppercase tracking-wider text-sm rounded hover:scale-105 hover:brightness-90 transition-all duration-300 shadow-lg shadow-[#FEBC11]/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('messages.plan_my_safari') }}
            </a>
            @if($whatsappNum)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNum) }}?text={{ $ctaMsg }}"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-8 py-4 border-2 border-white/30 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-white hover:text-[#131414] transition-all duration-300">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.725-1.394A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.308-.724-5.993-1.953l-.42-.307-3.063.904.842-3.132-.327-.44A9.964 9.964 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                WhatsApp Us
            </a>
            @endif
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
@if($destination->latitude && $destination->longitude && config('services.mapbox.token'))
<script src="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    mapboxgl.accessToken = @js(config('services.mapbox.token'));

    const map = new mapboxgl.Map({
        container: 'destination-map',
        style: 'mapbox://styles/mapbox/outdoors-v12',
        center: [@js($destination->longitude), @js($destination->latitude)],
        zoom: 9,
        attributionControl: false,
    });

    map.addControl(new mapboxgl.NavigationControl(), 'top-left');

    new mapboxgl.Marker({ color: '#FEBC11' })
        .setLngLat([@js($destination->longitude), @js($destination->latitude)])
        .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML('<strong>{{ $destination->translated("name") }}</strong>'))
        .addTo(map);
});
</script>
@endif
@endpush
