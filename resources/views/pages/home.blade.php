@extends('layouts.app')

@section('content')
@if(isset($sections) && $sections->count())
    @foreach($sections as $section)
        @php
            $sectionData = $sectionDataMap[$section->id] ?? [];
            $partialName = str_replace('_', '-', $section->section_type);
        @endphp

        @if(view()->exists('sections.' . $partialName))
            @include('sections.' . $partialName, ['section' => $section, 'sectionData' => $sectionData])
        @endif
    @endforeach
@else
    {{-- Fallback: basic hero when no sections are configured --}}
    <section class="relative min-h-[60vh] flex items-center justify-center bg-[#083321]">
        <div class="text-center px-6">
            <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                {{ __('messages.hero_headline') }}
            </h1>
            <p class="text-lg text-white/60 mb-8 max-w-lg mx-auto">{{ __('messages.hero_subtext') }}</p>
            <a href="{{ route('plan-safari') }}"
               class="inline-block px-8 py-4 bg-[#FEBC11] text-[#131414] text-sm font-bold uppercase tracking-wider rounded hover:scale-105 transition-all duration-300">
                {{ __('messages.start_planning') }}
            </a>
        </div>
    </section>
@endif
@endsection
