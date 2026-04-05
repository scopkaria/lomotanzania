@extends('layouts.app')

@section('title', __('messages.experiences') . ' — ' . ($siteName ?? 'Lomo Tanzania Safari'))

@section('content')

{{-- Hero --}}
<section class="relative h-[50vh] min-h-[380px] overflow-hidden">
    <img src="https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1600&h=900&fit=crop&q=60"
         alt="{{ __('messages.experiences') }}"
         class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/30 to-transparent"></div>
    <div class="relative z-10 h-full flex flex-col justify-end max-w-7xl mx-auto px-6 pb-12">
        <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.curated_journeys') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">{{ __('messages.experiences') }}</h1>
        <p class="mt-4 text-lg text-white/90 max-w-2xl leading-relaxed">{{ __('messages.experiences_subtitle') }}</p>
    </div>
</section>

@if(isset($sections) && $sections->count())
    @include('partials.render-page-sections', ['sections' => $sections, 'sectionDataMap' => $sectionDataMap ?? []])
@endif

{{-- Experience Cards Grid --}}
<section class="py-16 md:py-24 bg-brand-light">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.choose_your_adventure') }}</p>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-tight">{{ __('messages.explore_by_experience') }}</h2>
        </div>

        @if($experiences->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($experiences as $experience)
                    <a href="{{ route('safaris.index') }}?tour_types={{ $experience->slug }}"
                       class="group bg-white rounded-xl shadow-sm overflow-hidden hover:-translate-y-2 hover:shadow-lg transition-all duration-300">
                        <div class="relative overflow-hidden h-64">
                            @if($experience->featured_image)
                                <img src="{{ asset('storage/' . $experience->featured_image) }}"
                                     alt="{{ $experience->translated('name') }}" loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            @else
                                <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=460&fit=crop&q=80"
                                     alt="{{ $experience->translated('name') }}" loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/40 to-transparent"></div>
                            @if($experience->safari_packages_count)
                                <span class="absolute top-4 right-4 px-3 py-1 bg-brand-gold/90 text-brand-dark text-[10px] font-bold uppercase tracking-wider rounded-sm">
                                    {{ $experience->safari_packages_count }} {{ Str::plural(__('messages.safari'), $experience->safari_packages_count) }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="font-heading text-xl font-bold text-brand-dark mb-2 group-hover:text-brand-green transition-colors duration-300">
                                {{ $experience->translated('name') }}
                            </h3>
                            @if($experience->translated('description'))
                                <p class="text-sm text-brand-dark/80 leading-relaxed line-clamp-2 mb-5">
                                    {{ Str::limit(strip_tags($experience->translated('description')), 140) }}
                                </p>
                            @endif
                            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-gold group-hover:text-brand-green transition-colors duration-300">
                                {{ __('messages.explore_experience') }}
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-brand-dark/90 text-sm">{{ __('messages.no_experiences_yet') }}</p>
            </div>
        @endif
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 md:py-20 bg-brand-green">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="font-heading text-3xl md:text-4xl font-bold text-white mb-4">{{ __('messages.cant_decide') }}</h2>
        <p class="text-white/90 leading-relaxed mb-8">{{ __('messages.cant_decide_desc') }}</p>
        <a href="{{ route('plan-safari', ['locale' => app()->getLocale()]) }}"
           class="inline-block px-8 py-3.5 bg-brand-gold text-brand-dark text-xs font-bold uppercase tracking-[0.14em] rounded-sm hover:bg-white transition-all duration-300">
            {{ __('messages.start_planning') }}
        </a>
    </div>
</section>

@endsection
