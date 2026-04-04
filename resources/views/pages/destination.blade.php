@extends('layouts.app')

@section('title', $destination->translated('name') . ' — ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
@if($destination->latitude && $destination->longitude && config('services.mapbox.token'))
<link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet">
@endif
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
    <div class="relative z-10 h-full flex flex-col justify-end max-w-7xl mx-auto px-6 pb-12">
        <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.explore_destination') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">{{ $destination->translated('name') }}</h1>
        @if($destination->country)
            <p class="mt-3 text-lg text-white/60">{{ $destination->country->name }}</p>
        @endif
    </div>
</section>

{{-- Description + Map --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-5 gap-12 items-start">

            {{-- Description --}}
            <div class="lg:col-span-3">
                <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-6">{{ __('messages.about_destination') }}</h2>
                @if($destination->translated('description'))
                    <div class="prose prose-lg max-w-none text-brand-dark/70 leading-relaxed">
                        {!! nl2br(e($destination->translated('description'))) !!}
                    </div>
                @else
                    <p class="text-brand-dark/40 italic">—</p>
                @endif

                {{-- Quick facts --}}
                @if($destination->latitude && $destination->longitude)
                    <div class="mt-8 flex flex-wrap gap-6">
                        <div class="flex items-center gap-2 text-sm text-brand-dark/50">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ number_format($destination->latitude, 4) }}°, {{ number_format($destination->longitude, 4) }}°
                        </div>
                        @if($destination->country)
                            <div class="flex items-center gap-2 text-sm text-brand-dark/50">
                                <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $destination->country->name }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Map --}}
            <div class="lg:col-span-2">
                @if($destination->latitude && $destination->longitude && config('services.mapbox.token'))
                    <div id="destination-map" class="w-full h-80 lg:h-96 rounded-xl overflow-hidden shadow-md"></div>
                @elseif($destination->featured_image)
                    <div class="rounded-xl overflow-hidden shadow-md">
                        <img src="{{ asset('storage/' . $destination->featured_image) }}"
                             alt="{{ $destination->translated('name') }}"
                             class="w-full h-80 lg:h-96 object-cover">
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Available Safaris --}}
@if($destination->safariPackages->count())
<section class="py-16 md:py-24 bg-brand-light">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.featured_in_safaris') }}</p>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-tight">
                {{ __('messages.available_safaris_in', ['destination' => $destination->translated('name')]) }}
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($destination->safariPackages as $safari)
                <div class="group bg-white rounded-xl shadow-sm overflow-hidden hover:-translate-y-1.5 hover:shadow-md transition-all duration-300">
                    <div class="overflow-hidden">
                        @if($safari->featured_image)
                            <img src="{{ asset('storage/' . $safari->featured_image) }}"
                                 alt="{{ $safari->translated('title') }}" loading="lazy"
                                 class="w-full h-56 object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        @else
                            <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=460&fit=crop&q=80"
                                 alt="{{ $safari->translated('title') }}" loading="lazy"
                                 class="w-full h-56 object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-semibold uppercase tracking-wider text-brand-dark/40">{{ $safari->duration ?? __('messages.multi_day') }}</span>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-brand-dark mb-2">{{ $safari->translated('title') }}</h3>
                        <p class="text-sm text-brand-dark/50 leading-relaxed mb-5">{{ Str::limit($safari->translated('short_description'), 140) }}</p>

                        @if($safari->price)
                            <p class="text-lg font-bold text-brand-green mb-4">${{ number_format($safari->price) }}</p>
                        @endif

                        <a href="{{ route('safaris.show', $safari->slug) }}"
                           class="inline-block px-5 py-2.5 border-2 border-brand-gold text-brand-dark text-xs font-bold uppercase tracking-wider rounded hover:bg-brand-gold hover:text-brand-dark transition-all duration-300">
                            {{ __('messages.view_safari') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@else
<section class="py-16 md:py-24 bg-brand-light">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-brand-dark/40 text-sm">{{ __('messages.no_safaris_destination') }}</p>
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
