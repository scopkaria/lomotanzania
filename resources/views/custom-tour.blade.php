@extends('layouts.app')

@section('content')
<div class="bg-brand-green relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=600&fit=crop')] bg-cover bg-center opacity-20"></div>
    <div class="relative max-w-4xl mx-auto px-6 py-20 text-center">
        <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-4 hero-animate hero-delay-1">{{ __('messages.bespoke_planning') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl font-bold text-white leading-tight hero-animate hero-delay-2">{{ __('messages.design_dream_safari') }}</h1>
        <p class="mt-5 text-white/90 text-lg leading-relaxed max-w-2xl mx-auto hero-animate hero-delay-3">{{ __('messages.custom_tour_intro') }}</p>
    </div>
</div>

<section class="max-w-3xl mx-auto px-6 py-16">

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('custom-tour.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.full_name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                       placeholder="Your name">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                       placeholder="you@example.com">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone_with_code') }}</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                       placeholder="+1 234 567 8900">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Number of People --}}
            <div>
                <label for="number_of_people" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.number_of_travellers') }}</label>
                <input type="number" name="number_of_people" id="number_of_people" value="{{ old('number_of_people') }}" min="1" max="100"
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                       placeholder="2">
                @error('number_of_people') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Travel Dates --}}
        <div>
            <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.preferred_travel_date') }}</label>
            <input type="date" name="travel_date" id="travel_date" value="{{ old('travel_date') }}"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4">
            @error('travel_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Destinations of Interest --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.destinations_of_interest') }}</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($destinations as $destination)
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="destinations[]" value="{{ $destination->id }}"
                               class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold"
                               {{ in_array($destination->id, old('destinations', [])) ? 'checked' : '' }}>
                        {{ $destination->translated('name') }}
                    </label>
                @endforeach
            </div>
            @error('destinations') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Message --}}
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.dream_trip_label') }}</label>
            <textarea name="message" id="message" rows="5"
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                      placeholder="{{ __('messages.dream_trip_placeholder') }}">{{ old('message') }}</textarea>
            @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="text-center pt-4">
            <button type="submit"
                    class="inline-block px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300">
                {{ __('messages.submit_your_request') }}
            </button>
            <p class="mt-3 text-xs text-gray-400">{{ __('messages.we_respond_24h') }}</p>
        </div>
    </form>
</section>
@endsection
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    