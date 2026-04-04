@extends('layouts.app')

@section('title', $category->translated('name') . ' — ' . ($siteName ?? 'Lomo Tanzania Safari'))

@section('content')

{{-- Hero --}}
<section class="relative h-[50vh] min-h-[380px] overflow-hidden">
    @if($category->featured_image)
        <img src="{{ asset('storage/' . $category->featured_image) }}"
             alt="{{ $category->translated('name') }}"
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=1600&h=900&fit=crop&q=60"
             alt="{{ $category->translated('name') }}"
             class="absolute inset-0 w-full h-full object-cover">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/30 to-transparent"></div>
    <div class="relative z-10 h-full flex flex-col justify-end max-w-7xl mx-auto px-6 pb-12">
        <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.category_kicker') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">{{ $category->translated('name') }}</h1>
    </div>
</section>

{{-- Description --}}
@if($category->translated('description'))
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-6">{{ __('messages.about_category', ['name' => $category->translated('name')]) }}</h2>
        <div class="prose prose-lg max-w-none text-brand-dark/70 leading-relaxed">
            {!! nl2br(e($category->translated('description'))) !!}
        </div>
    </div>
</section>
@endif

{{-- Related Safaris --}}
<section class="py-16 md:py-24 {{ $category->translated('description') ? 'bg-brand-light' : 'bg-white' }}">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.featured_in_safaris') }}</p>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-tight">
                {{ __('messages.safaris_for_category', ['name' => $category->translated('name')]) }}
            </h2>
        </div>

        @if($safaris->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($safaris as $safari)
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
        @else
            <div class="text-center py-12">
                <p class="text-brand-dark/40 text-sm">{{ __('messages.no_safaris_category') }}</p>
            </div>
        @endif
    </div>
</section>

@endsection
