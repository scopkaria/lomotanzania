@extends('layouts.app')

@section('title', $country->name . ' — ' . ($siteName ?? 'Lomo Tanzania Safari'))

@section('content')

{{-- Hero --}}
<section class="relative h-[50vh] min-h-[380px] overflow-hidden">
    @if($country->featured_image)
        <img src="{{ asset('storage/' . $country->featured_image) }}"
             alt="{{ $country->name }}"
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=900&fit=crop&q=60"
             alt="{{ $country->name }}"
             class="absolute inset-0 w-full h-full object-cover">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/30 to-transparent"></div>
    <div class="relative z-10 h-full flex flex-col justify-end max-w-7xl mx-auto px-6 pb-12">
        <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.explore_country') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">{{ $country->name }}</h1>
    </div>
</section>

{{-- Description --}}
@if($country->description)
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-6">{{ __('messages.about_country', ['country' => $country->name]) }}</h2>
        <div class="prose prose-lg max-w-none text-brand-dark/70 leading-relaxed">
            {!! nl2br(e($country->description)) !!}
        </div>
    </div>
</section>
@endif

{{-- Destinations --}}
<section class="py-16 md:py-24 {{ $country->description ? 'bg-brand-light' : 'bg-white' }}">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.destinations') }}</p>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-tight">
                {{ __('messages.destinations_in', ['country' => $country->name]) }}
            </h2>
        </div>

        @if($country->destinations->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($country->destinations as $dest)
                    <a href="{{ route('destinations.show', $dest->slug) }}"
                       class="group relative rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1.5">
                        <div class="aspect-[4/3] overflow-hidden">
                            @if($dest->featured_image)
                                <img src="{{ asset('storage/' . $dest->featured_image) }}"
                                     alt="{{ $dest->translated('name') }}" loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-brand-green/20 to-brand-gold/20 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-brand-green/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="font-heading text-xl font-bold text-white mb-1">{{ $dest->translated('name') }}</h3>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-brand-gold group-hover:gap-2 transition-all">
                                {{ __('messages.explore_destination') }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-brand-dark/40 text-sm">{{ __('messages.no_destinations_country') }}</p>
            </div>
        @endif
    </div>
</section>

{{-- Safaris --}}
@if($country->safariPackages->count())
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.featured_in_safaris') }}</p>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-tight">
                {{ __('messages.safaris_in', ['country' => $country->name]) }}
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($country->safariPackages as $safari)
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
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-brand-dark/40 text-sm">{{ __('messages.no_safaris_country') }}</p>
    </div>
</section>
@endif

@endsection
