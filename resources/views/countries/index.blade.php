@extends('layouts.app')

@section('title', (__('messages.countries') ?: 'Countries') . ' - ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
<style>
    .country-item { opacity: 0; transform: translateY(24px); transition: opacity 600ms cubic-bezier(0.16,1,0.3,1), transform 600ms cubic-bezier(0.16,1,0.3,1); }
    .country-item.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- Hero --}}
@php $indexHero = \App\Models\IndexHeroImage::forSection('countries'); @endphp
<section class="relative bg-brand-dark py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="{{ $indexHero->image_url ?? 'https://images.unsplash.com/photo-1489392191049-fc10c97e64b6?w=1600&h=500&fit=crop&q=60' }}" alt="" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/60 to-brand-dark/90"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <p class="text-[11px] font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.explore') ?: 'Explore' }}</p>
        <h1 class="font-heading text-3xl md:text-5xl font-bold text-white leading-tight mb-4">{{ __('messages.countries') ?: 'Countries' }}</h1>
        <p class="text-base text-white/80 max-w-lg mx-auto">Explore the countries where your next African adventure begins</p>
    </div>
</section>

{{-- Country Grid --}}
<section class="py-12 md:py-20 bg-brand-light" x-data="{ filter: 'all' }" x-init="$nextTick(() => document.querySelectorAll('.country-item').forEach((el, i) => setTimeout(() => el.classList.add('visible'), i * 100)))">
    <div class="max-w-6xl mx-auto px-6">

        {{-- Filter bar --}}
        @php
            $regions = $countries->pluck('region')->filter()->unique()->sort()->values();
        @endphp
        @if($regions->count() > 1)
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            <button @click="filter = 'all'"
                    class="px-5 py-2 rounded-full text-sm font-semibold border-2 transition-all duration-200"
                    :class="filter === 'all' ? 'border-brand-green bg-brand-green text-white' : 'border-gray-200 text-gray-600 hover:border-brand-green/50'">
                All
            </button>
            @foreach($regions as $region)
            <button @click="filter = '{{ Str::slug($region) }}'"
                    class="px-5 py-2 rounded-full text-sm font-semibold border-2 transition-all duration-200"
                    :class="filter === '{{ Str::slug($region) }}' ? 'border-brand-green bg-brand-green text-white' : 'border-gray-200 text-gray-600 hover:border-brand-green/50'">
                {{ $region }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Card grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($countries as $country)
            <div class="country-item"
                 x-show="filter === 'all' || filter === '{{ Str::slug($country->region ?? '') }}'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <a href="{{ route('countries.show', $country->slug) }}"
                   class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 h-full">

                    {{-- Image --}}
                    <div class="aspect-[16/10] overflow-hidden relative">
                        @if($country->featured_image)
                            <img src="{{ asset('storage/' . $country->featured_image) }}"
                                 alt="{{ $country->name }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-brand-green/20 to-brand-gold/20 flex items-center justify-center">
                                <svg class="w-12 h-12 text-brand-green/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                        @if($country->destinations_count ?? 0)
                        <span class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-[11px] font-bold text-brand-dark">
                            {{ $country->destinations_count }} {{ Str::plural('destination', $country->destinations_count) }}
                        </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <h2 class="font-heading text-xl font-bold text-brand-dark mb-2 group-hover:text-brand-green transition-colors">
                            {{ $country->name }}
                        </h2>
                        @if($country->description)
                            <p class="text-sm text-brand-dark/60 leading-relaxed line-clamp-2 mb-3">{{ Str::limit(strip_tags($country->description), 120) }}</p>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-brand-green group-hover:gap-2.5 transition-all">
                            {{ __('messages.explore') ?: 'Explore' }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <p class="text-brand-dark/40 text-lg">No countries found yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
