@extends('layouts.app')

@section('content')
<div x-data="safariPlanner()" x-cloak class="min-h-screen bg-brand-light">

    {{-- ═══════════ SUCCESS STATE ═══════════ --}}
    @if(session('success'))
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="max-w-md w-full text-center">
            <div class="w-16 h-16 bg-brand-green/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="font-heading text-3xl font-bold text-brand-dark mb-3">{{ __('messages.youre_all_set') }}</h2>
            <p class="text-gray-600 mb-8">{{ session('success') }}</p>
            @if(session('whatsapp_url'))
                <a href="{{ session('whatsapp_url') }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 px-8 py-3 bg-[#25D366] text-white text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 transition-all">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.61.609l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.357 0-4.528-.67-6.387-1.826l-.458-.282-2.65.888.886-2.647-.282-.458A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                    {{ __('messages.continue_whatsapp') }}
                </a>
            @endif
            <div class="mt-6">
                <a href="{{ url('/') }}" class="text-sm text-brand-green hover:underline">{{ __('messages.back_to_home') }}</a>
            </div>
        </div>
    </div>
    @else

    {{-- ═══════════ HERO BANNER (always visible) ═══════════ --}}
    <div class="bg-brand-green relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=600&fit=crop')] bg-cover bg-center opacity-15"></div>
        <div class="relative max-w-3xl mx-auto px-6 py-16 md:py-20 text-center">
            <p class="text-brand-gold text-xs uppercase tracking-[5px] font-semibold mb-4 hero-animate hero-delay-1">{{ __('messages.safari_planning') }}</p>
            <h1 class="font-heading text-3xl md:text-5xl font-bold text-white leading-tight mb-4 hero-animate hero-delay-2">
                {{ $settings->get('intro')?->title ?? 'Your dream African safari starts here' }}
            </h1>
            <p class="text-white/85 text-base md:text-lg leading-relaxed max-w-xl mx-auto hero-animate hero-delay-3">
                {{ $settings->get('intro')?->description ?? 'Answer a few quick questions and our safari experts will craft a personalised itinerary tailored to your pace, interests, and budget.' }}
            </p>
        </div>
    </div>

    {{-- ═══════════ STICKY PROGRESS BAR ═══════════ --}}
    <div x-show="currentStep > 0" x-transition class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 md:px-6">
            <div class="flex items-center justify-between h-14">
                {{-- Back --}}
                <button type="button" @click="prevStep()" x-show="currentStep > (hasSafari ? 1 : 0)"
                        class="flex items-center gap-1 text-sm text-gray-500 hover:text-brand-dark transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('messages.back') }}
                </button>
                <div x-show="currentStep <= (hasSafari ? 1 : 0)"></div>

                {{-- Progress bar --}}
                <div class="flex items-center gap-1">
                    <template x-for="s in totalSteps" :key="s">
                        <div class="h-1.5 rounded-full transition-all duration-300"
                             :class="s < currentStep ? 'w-8 md:w-10 bg-brand-green' : s === currentStep ? 'w-8 md:w-10 bg-brand-gold' : 'w-3 bg-gray-200'"></div>
                    </template>
                </div>

                {{-- Step counter --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 hidden sm:inline" x-text="'Step ' + currentStep + ' / ' + totalSteps"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ FORM ═══════════ --}}
    <form action="{{ route('plan-safari.store') }}" method="POST" @submit="handleSubmit($event)">
        @csrf
        <input type="hidden" name="safari_id" :value="safariId">
        <input type="hidden" name="know_destination" :value="knowDestination">
        {{-- Honeypot --}}
        <div class="hidden" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        {{-- ═══════════ STEP 0 — ENTRY QUESTION ═══════════ --}}
        <div x-show="currentStep === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-16 md:py-20">
                <div class="text-center mb-12">
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-3">Do you know where you want to travel?</h2>
                    <p class="text-gray-500 text-sm md:text-base">This helps us personalize your experience</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-xl mx-auto">
                    <button type="button" @click="knowDestination = 'Yes, I do'; nextStep()"
                            class="group p-6 rounded-2xl border-2 transition-all duration-200 text-center cursor-pointer border-gray-200 hover:border-brand-green hover:bg-brand-green/5 bg-white">
                        <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center bg-brand-green/10 text-brand-green group-hover:bg-brand-green group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm font-bold text-brand-dark">Yes, I do</span>
                    </button>

                    <button type="button" @click="knowDestination = 'I have an idea'; nextStep()"
                            class="group p-6 rounded-2xl border-2 transition-all duration-200 text-center cursor-pointer border-gray-200 hover:border-brand-gold hover:bg-brand-gold/5 bg-white">
                        <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center bg-brand-gold/10 text-brand-gold group-hover:bg-brand-gold group-hover:text-brand-dark transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-brand-dark">I have an idea</span>
                    </button>

                    <button type="button" @click="knowDestination = 'No, help me decide'; nextStep()"
                            class="group p-6 rounded-2xl border-2 transition-all duration-200 text-center cursor-pointer border-gray-200 hover:border-brand-dark hover:bg-brand-dark/5 bg-white">
                        <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center bg-brand-dark/10 text-brand-dark group-hover:bg-brand-dark group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-brand-dark">No, help me decide</span>
                    </button>
                </div>
            </div>
        </div>


        {{-- ═══════════ STEP 1 — DESTINATIONS (Pill buttons, no images) ═══════════ --}}
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-3xl mx-auto px-6 py-12 md:py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 1</p>
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('destinations')?->title ?? 'Where would you like to go?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('destinations')?->description ?? 'Select one or more destinations that interest you' }}</p>
                </div>

                <div class="flex flex-wrap justify-center gap-3">
                    @foreach($destinations as $destination)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="destinations[]" value="{{ $destination->id }}"
                               x-model.number="selectedDestinations" class="sr-only peer">
                        <div class="px-6 py-3 rounded-full border-2 text-sm font-semibold transition-all duration-200
                                    peer-checked:border-brand-green peer-checked:bg-brand-green peer-checked:text-white
                                    border-gray-200 hover:border-brand-green/50 text-brand-dark bg-white hover:shadow-sm">
                            {{ $destination->translated('name') }}
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="mt-4 text-center">
                    <p x-show="errors.destinations" class="text-red-500 text-sm" x-text="errors.destinations"></p>
                </div>

                <div class="mt-10 text-center">
                    <button type="button" @click="validateAndNext('destinations')"
                            class="inline-block px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300">
                        {{ __('messages.continue') }}
                    </button>
                </div>
            </div>
        </div>


        {{-- ═══════════ STEP 2 — TRAVEL TIME + DATE RANGE ═══════════ --}}
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-12 md:py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 2</p>
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('travel_time')?->title ?? 'When would you like to travel?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('travel_time')?->description ?? 'Select your preferred travel months' }}</p>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <template x-for="month in months" :key="month">
                        <label class="cursor-pointer">
                            <input type="checkbox" name="months[]" :value="month" x-model="selectedMonths" class="sr-only peer">
                            <div class="rounded-xl border-2 py-4 text-center transition-all duration-200
                                        peer-checked:border-brand-green peer-checked:bg-brand-green/10 peer-checked:text-brand-dark
                                        border-gray-200 hover:border-brand-green/50 text-gray-600">
                                <span class="text-sm font-semibold" x-text="month"></span>
                            </div>
                        </label>
                    </template>
                </div>

                {{-- Exact dates toggle --}}
                <div class="mt-8 p-5 bg-white rounded-xl border border-gray-100">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="hasExactDates"
                               class="rounded border-gray-300 text-brand-green focus:ring-brand-green w-5 h-5">
                        <span class="text-sm font-semibold text-brand-dark">I have exact travel dates</span>
                    </label>

                    <div x-show="hasExactDates" x-collapse class="mt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Start Date</label>
                                <input type="date" name="travel_start_date" x-model="travelStartDate"
                                       :min="todayDate"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">End Date</label>
                                <input type="date" name="travel_end_date" x-model="travelEndDate"
                                       :min="travelStartDate || todayDate"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4">
                            </div>
                        </div>
                        <p x-show="travelStartDate && travelEndDate" class="mt-3 text-sm text-brand-green font-medium">
                            <span x-text="calculateDays()"></span> days selected
                        </p>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p x-show="errors.months" class="text-red-500 text-sm" x-text="errors.months"></p>
                </div>

                <div class="mt-10 text-center">
                    <button type="button" @click="validateAndNext('months')"
                            class="inline-block px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300">
                        {{ __('messages.continue') }}
                    </button>
                </div>
            </div>
        </div>


        {{-- ═══════════ STEP 3 — TRAVEL GROUP ═══════════ --}}
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-xl mx-auto px-6 py-12 md:py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 3</p>
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('travel_group')?->title ?? 'Who are you traveling with?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('travel_group')?->description ?? 'This helps us tailor your experience' }}</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <template x-for="group in travelGroups" :key="group.label">
                        <label class="cursor-pointer">
                            <input type="radio" name="travel_group" :value="group.label" x-model="selectedGroup" class="sr-only peer">
                            <div class="rounded-2xl border-2 py-6 text-center transition-all duration-200
                                        peer-checked:border-brand-green peer-checked:bg-brand-green/10 peer-checked:shadow-md
                                        border-gray-200 hover:border-brand-green/50 bg-white">
                                <div class="text-3xl mb-2" x-text="group.icon"></div>
                                <span class="text-sm font-bold text-brand-dark" x-text="group.label"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <div class="mt-4 text-center">
                    <p x-show="errors.travel_group" class="text-red-500 text-sm" x-text="errors.travel_group"></p>
                </div>

                <div class="mt-10 text-center">
                    <button type="button" @click="validateAndNext('travel_group')"
                            class="inline-block px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300">
                        {{ __('messages.continue') }}
                    </button>
                </div>
            </div>
        </div>


        {{-- ═══════════ STEP 4 — EXPERIENCES / INTERESTS ═══════════ --}}
        <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-12 md:py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 4</p>
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('interests')?->title ?? 'What experiences excite you most?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('interests')?->description ?? 'Select all that appeal to you — this is optional' }}</p>
                </div>

                <div class="flex flex-wrap justify-center gap-3">
                    @foreach($tourTypes as $tourType)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="interests[]" value="{{ $tourType->name }}" x-model="selectedInterests" class="sr-only peer">
                        <div class="px-5 py-3 rounded-full border-2 text-sm font-semibold transition-all duration-200
                                    peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 peer-checked:text-brand-dark
                                    border-gray-200 hover:border-brand-gold/50 text-gray-600 bg-white">
                            {{ $tourType->name }}
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <button type="button" @click="nextStep()"
                            class="inline-block px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300">
                        {{ __('messages.continue') }}
                    </button>
                    <button type="button" @click="nextStep()"
                            class="block mx-auto mt-3 text-sm text-gray-400 hover:text-gray-600 transition-colors">
                        {{ __('messages.skip_this_step') }}
                    </button>
                </div>
            </div>
        </div>


        {{-- ═══════════ STEP 5 — BUDGET RANGE SLIDER ═══════════ --}}
        <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-xl mx-auto px-6 py-12 md:py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 5</p>
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('budget')?->title ?? 'What is your budget range?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('budget')?->description ?? 'Per person, approximate range' }}</p>
                </div>

                {{-- Budget display --}}
                <div class="text-center mb-8" x-show="!customBudget">
                    <div class="inline-flex items-center gap-3 px-8 py-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <span class="font-heading text-2xl md:text-3xl font-bold text-brand-green" x-text="'$' + budgetMin.toLocaleString()"></span>
                        <span class="text-gray-400 text-lg">—</span>
                        <span class="font-heading text-2xl md:text-3xl font-bold text-brand-green" x-text="'$' + budgetMax.toLocaleString()"></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">per person</p>
                </div>

                {{-- Dual range slider --}}
                <div class="px-4 mb-6" x-show="!customBudget">
                    <div class="relative h-2 bg-gray-200 rounded-full">
                        {{-- Active range highlight --}}
                        <div class="absolute h-full bg-brand-green rounded-full"
                             :style="'left:' + ((budgetMin - 1500) / 18500 * 100) + '%; right:' + (100 - (budgetMax - 1500) / 18500 * 100) + '%'"></div>
                    </div>
                    <div class="relative">
                        {{-- Min slider --}}
                        <input type="range" min="1500" max="20000" step="500"
                               x-model.number="budgetMin"
                               @input="if(budgetMin >= budgetMax) budgetMin = budgetMax - 500"
                               class="budget-slider absolute w-full -top-[5px] appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-brand-green [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-brand-green [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-white [&::-moz-range-thumb]:shadow-md [&::-moz-range-thumb]:cursor-pointer">
                        {{-- Max slider --}}
                        <input type="range" min="1500" max="20000" step="500"
                               x-model.number="budgetMax"
                               @input="if(budgetMax <= budgetMin) budgetMax = budgetMin + 500"
                               class="budget-slider absolute w-full -top-[5px] appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-brand-green [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:cursor-pointer [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-brand-green [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-white [&::-moz-range-thumb]:shadow-md [&::-moz-range-thumb]:cursor-pointer">
                    </div>
                    <div class="flex justify-between mt-6 text-xs text-gray-400">
                        <span>$1,500</span>
                        <span>$5,000</span>
                        <span>$10,000</span>
                        <span>$15,000</span>
                        <span>$20,000</span>
                    </div>
                </div>

                {{-- Hidden input for form submission --}}
                <input type="hidden" name="budget_range" :value="customBudget ? customBudgetValue : ('$' + budgetMin.toLocaleString() + ' – $' + budgetMax.toLocaleString())">

                {{-- Custom budget toggle --}}
                <div class="mt-6 text-center">
                    <button type="button" @click="customBudget = !customBudget"
                            class="text-sm text-brand-green hover:text-brand-dark transition font-medium underline underline-offset-4">
                        <span x-text="customBudget ? 'Use range slider instead' : 'I have a different budget'"></span>
                    </button>
                </div>

                {{-- Custom budget input --}}
                <div x-show="customBudget" x-collapse class="mt-4 max-w-xs mx-auto">
                    <input type="text" x-model="customBudgetValue" placeholder="e.g. $25,000 total for 2 people"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4 text-center">
                </div>

                <div class="mt-4 text-center">
                    <p x-show="errors.budget_range" class="text-red-500 text-sm" x-text="errors.budget_range"></p>
                </div>

                <div class="mt-10 text-center">
                    <button type="button" @click="validateAndNext('budget_range')"
                            class="inline-block px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300">
                        {{ __('messages.continue') }}
                    </button>
                </div>
            </div>
        </div>


        {{-- ═══════════ STEP 6 — CONTACT DETAILS ═══════════ --}}
        <div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-12 md:py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">{{ __('messages.final_step') }}</p>
                    <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('contact')?->title ?? 'Almost there! How should we reach you?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('contact')?->description ?? 'Share your details so our team can reach out' }}</p>
                </div>

                <div class="space-y-5 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
                    {{-- Names --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.first_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" x-model="firstName" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4"
                                   placeholder="First name">
                            <p x-show="errors.first_name" class="mt-1 text-sm text-red-500" x-text="errors.first_name"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.last_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" x-model="lastName" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4"
                                   placeholder="Last name">
                            <p x-show="errors.last_name" class="mt-1 text-sm text-red-500" x-text="errors.last_name"></p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                        <input type="email" name="email" x-model="email" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4"
                               placeholder="you@example.com">
                        <p x-show="errors.email" class="mt-1 text-sm text-red-500" x-text="errors.email"></p>
                    </div>

                    {{-- Phone with full country codes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone') }}</label>
                        <div class="flex gap-2">
                            <select name="country_code" x-model="countryCode"
                                    class="w-[130px] rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-3">
                                <template x-for="c in countryCodes" :key="c.code">
                                    <option :value="c.dial" x-text="c.flag + ' ' + c.dial" :selected="c.dial === countryCode"></option>
                                </template>
                            </select>
                            <input type="tel" name="phone" x-model="phone"
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-brand-green focus:ring-brand-green text-sm py-3 px-4"
                                   placeholder="Phone number">
                        </div>
                    </div>

                    {{-- Contact Methods --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.preferred_contact') }} <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-400 mb-3">{{ __('messages.select_up_to_2') }}</p>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="method in contactMethodOptions" :key="method">
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="contact_methods[]" :value="method" x-model="contactMethods"
                                           :disabled="!contactMethods.includes(method) && contactMethods.length >= 2"
                                           class="sr-only peer">
                                    <div class="px-5 py-2.5 rounded-full border-2 text-sm font-medium transition-all duration-200
                                                peer-checked:border-brand-green peer-checked:bg-brand-green/10 peer-checked:text-brand-dark
                                                peer-disabled:opacity-40 peer-disabled:cursor-not-allowed
                                                border-gray-200 hover:border-brand-green/50 text-gray-600"
                                         x-text="method"></div>
                                </label>
                            </template>
                        </div>
                        <p x-show="errors.contact_methods" class="mt-2 text-sm text-red-500" x-text="errors.contact_methods"></p>
                    </div>

                    {{-- Consent checkbox --}}
                    <label class="flex items-start gap-3 cursor-pointer pt-2 border-t border-gray-100">
                        <input type="checkbox" name="wants_updates" value="1" x-model="wantsUpdates"
                               class="rounded border-gray-300 text-brand-green focus:ring-brand-green mt-0.5">
                        <span class="text-sm text-gray-600 leading-relaxed">By submitting this form, you agree to receive updates and special offers from <strong>{{ $siteName ?? 'Lomo Tanzania Safari' }}</strong>. You can unsubscribe at any time.</span>
                    </label>
                </div>

                <div class="mt-4 text-center">
                    <p x-show="errors.contact" class="text-red-500 text-sm" x-text="errors.contact"></p>
                </div>

                {{-- FINAL CTA --}}
                <div class="mt-10 text-center">
                    <button type="submit"
                            :disabled="submitting"
                            class="inline-flex items-center gap-2 px-12 py-4 bg-brand-green text-white text-base font-bold uppercase tracking-wider rounded-xl hover:bg-brand-dark hover:scale-[1.02] transition-all duration-300 shadow-lg shadow-brand-green/20 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg x-show="submitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-text="submitting ? 'Submitting...' : 'Plan My Safari'"></span>
                    </button>
                    <p class="mt-3 text-xs text-gray-400">{{ __('messages.we_respond_24h') }}</p>
                </div>
            </div>
        </div>

    </form>

    {{-- Mobile sticky CTA --}}
    <div x-show="currentStep > 0 && currentStep < totalSteps"
         class="fixed bottom-0 inset-x-0 z-30 bg-white border-t border-gray-100 p-3 sm:hidden">
        <button type="button" @click="handleContinue()"
                class="w-full py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 transition">
            {{ __('messages.continue') }}
        </button>
    </div>

    @endif
</div>

@push('styles')
<style>
    .budget-slider::-webkit-slider-runnable-track { height: 0; }
    .budget-slider::-moz-range-track { height: 0; }
</style>
@endpush

<script>
function safariPlanner() {
    return {
        safariId: {{ $safariId ?: 'null' }},
        hasSafari: {{ $safariId ? 'true' : 'false' }},
        currentStep: {{ $safariId ? 1 : 0 }},
        totalSteps: 6,

        // Step 0
        knowDestination: '',

        // Step 1
        selectedDestinations: {!! json_encode($preselectedDestinations) !!},

        // Step 2
        months: [{!! json_encode(__('messages.january')) !!}, {!! json_encode(__('messages.february')) !!}, {!! json_encode(__('messages.march')) !!}, {!! json_encode(__('messages.april')) !!}, {!! json_encode(__('messages.may')) !!}, {!! json_encode(__('messages.june')) !!}, {!! json_encode(__('messages.july')) !!}, {!! json_encode(__('messages.august')) !!}, {!! json_encode(__('messages.september')) !!}, {!! json_encode(__('messages.october')) !!}, {!! json_encode(__('messages.november')) !!}, {!! json_encode(__('messages.december')) !!}],
        selectedMonths: [],
        hasExactDates: false,
        travelStartDate: '',
        travelEndDate: '',
        todayDate: new Date().toISOString().split('T')[0],

        // Step 3
        travelGroups: [
            { label: {!! json_encode(__('messages.solo')) !!}, icon: "\u{1F9D1}" },
            { label: {!! json_encode(__('messages.couple')) !!}, icon: "\u{1F491}" },
            { label: {!! json_encode(__('messages.family')) !!}, icon: "\u{1F468}\u200D\u{1F469}\u200D\u{1F467}\u200D\u{1F466}" },
            { label: {!! json_encode(__('messages.friends')) !!}, icon: "\u{1F46F}" },
            { label: {!! json_encode(__('messages.group')) !!}, icon: "\u{1F465}" },
        ],
        selectedGroup: '',

        // Step 4
        selectedInterests: [],

        // Step 5
        budgetMin: 3000,
        budgetMax: 8000,
        customBudget: false,
        customBudgetValue: '',
        selectedBudget: '',

        // Step 6
        firstName: '',
        lastName: '',
        email: '',
        countryCode: '+255',
        phone: '',
        contactMethodOptions: ['Email', 'WhatsApp', 'Phone', 'Video Call'],
        contactMethods: [],
        wantsUpdates: false,

        // Country codes (comprehensive)
        countryCodes: [
            {code:'AF',dial:'+93',flag:'🇦🇫'},{code:'AL',dial:'+355',flag:'🇦🇱'},{code:'DZ',dial:'+213',flag:'🇩🇿'},
            {code:'AR',dial:'+54',flag:'🇦🇷'},{code:'AU',dial:'+61',flag:'🇦🇺'},{code:'AT',dial:'+43',flag:'🇦🇹'},
            {code:'BD',dial:'+880',flag:'🇧🇩'},{code:'BE',dial:'+32',flag:'🇧🇪'},{code:'BR',dial:'+55',flag:'🇧🇷'},
            {code:'BW',dial:'+267',flag:'🇧🇼'},{code:'CA',dial:'+1',flag:'🇨🇦'},{code:'CL',dial:'+56',flag:'🇨🇱'},
            {code:'CN',dial:'+86',flag:'🇨🇳'},{code:'CO',dial:'+57',flag:'🇨🇴'},{code:'CD',dial:'+243',flag:'🇨🇩'},
            {code:'CR',dial:'+506',flag:'🇨🇷'},{code:'HR',dial:'+385',flag:'🇭🇷'},{code:'CZ',dial:'+420',flag:'🇨🇿'},
            {code:'DK',dial:'+45',flag:'🇩🇰'},{code:'EG',dial:'+20',flag:'🇪🇬'},{code:'ET',dial:'+251',flag:'🇪🇹'},
            {code:'FI',dial:'+358',flag:'🇫🇮'},{code:'FR',dial:'+33',flag:'🇫🇷'},{code:'DE',dial:'+49',flag:'🇩🇪'},
            {code:'GH',dial:'+233',flag:'🇬🇭'},{code:'GR',dial:'+30',flag:'🇬🇷'},{code:'HK',dial:'+852',flag:'🇭🇰'},
            {code:'HU',dial:'+36',flag:'🇭🇺'},{code:'IN',dial:'+91',flag:'🇮🇳'},{code:'ID',dial:'+62',flag:'🇮🇩'},
            {code:'IE',dial:'+353',flag:'🇮🇪'},{code:'IL',dial:'+972',flag:'🇮🇱'},{code:'IT',dial:'+39',flag:'🇮🇹'},
            {code:'JP',dial:'+81',flag:'🇯🇵'},{code:'KE',dial:'+254',flag:'🇰🇪'},{code:'KR',dial:'+82',flag:'🇰🇷'},
            {code:'KW',dial:'+965',flag:'🇰🇼'},{code:'MY',dial:'+60',flag:'🇲🇾'},{code:'MX',dial:'+52',flag:'🇲🇽'},
            {code:'MA',dial:'+212',flag:'🇲🇦'},{code:'MZ',dial:'+258',flag:'🇲🇿'},{code:'NL',dial:'+31',flag:'🇳🇱'},
            {code:'NZ',dial:'+64',flag:'🇳🇿'},{code:'NG',dial:'+234',flag:'🇳🇬'},{code:'NO',dial:'+47',flag:'🇳🇴'},
            {code:'PK',dial:'+92',flag:'🇵🇰'},{code:'PE',dial:'+51',flag:'🇵🇪'},{code:'PH',dial:'+63',flag:'🇵🇭'},
            {code:'PL',dial:'+48',flag:'🇵🇱'},{code:'PT',dial:'+351',flag:'🇵🇹'},{code:'QA',dial:'+974',flag:'🇶🇦'},
            {code:'RO',dial:'+40',flag:'🇷🇴'},{code:'RU',dial:'+7',flag:'🇷🇺'},{code:'RW',dial:'+250',flag:'🇷🇼'},
            {code:'SA',dial:'+966',flag:'🇸🇦'},{code:'SG',dial:'+65',flag:'🇸🇬'},{code:'ZA',dial:'+27',flag:'🇿🇦'},
            {code:'ES',dial:'+34',flag:'🇪🇸'},{code:'SE',dial:'+46',flag:'🇸🇪'},{code:'CH',dial:'+41',flag:'🇨🇭'},
            {code:'TW',dial:'+886',flag:'🇹🇼'},{code:'TZ',dial:'+255',flag:'🇹🇿'},{code:'TH',dial:'+66',flag:'🇹🇭'},
            {code:'TR',dial:'+90',flag:'🇹🇷'},{code:'UG',dial:'+256',flag:'🇺🇬'},{code:'AE',dial:'+971',flag:'🇦🇪'},
            {code:'GB',dial:'+44',flag:'🇬🇧'},{code:'US',dial:'+1',flag:'🇺🇸'},{code:'VN',dial:'+84',flag:'🇻🇳'},
            {code:'ZM',dial:'+260',flag:'🇿🇲'},{code:'ZW',dial:'+263',flag:'🇿🇼'},
        ],

        // State
        errors: {},
        submitting: false,

        calculateDays() {
            if (!this.travelStartDate || !this.travelEndDate) return 0;
            const start = new Date(this.travelStartDate);
            const end = new Date(this.travelEndDate);
            return Math.max(0, Math.ceil((end - start) / (1000 * 60 * 60 * 24)));
        },

        get budgetValue() {
            return this.customBudget ? this.customBudgetValue : ('$' + this.budgetMin.toLocaleString() + ' – $' + this.budgetMax.toLocaleString());
        },

        handleContinue() {
            const fields = ['', 'destinations', 'months', 'travel_group', '', 'budget_range'];
            const field = fields[this.currentStep];
            if (field) {
                this.validateAndNext(field);
            } else {
                this.nextStep();
            }
        },

        nextStep() {
            this.errors = {};
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            this.errors = {};
            if (this.currentStep > (this.hasSafari ? 1 : 0)) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        validateAndNext(field) {
            this.errors = {};
            if (field === 'destinations' && this.selectedDestinations.length === 0) {
                this.errors.destinations = {!! json_encode(__('messages.select_dest')) !!};
                return;
            }
            if (field === 'months' && this.selectedMonths.length === 0 && !this.hasExactDates) {
                this.errors.months = {!! json_encode(__('messages.select_month')) !!};
                return;
            }
            if (field === 'months' && this.hasExactDates && (!this.travelStartDate || !this.travelEndDate)) {
                this.errors.months = 'Please select both start and end dates';
                return;
            }
            if (field === 'travel_group' && !this.selectedGroup) {
                this.errors.travel_group = {!! json_encode(__('messages.select_group')) !!};
                return;
            }
            if (field === 'budget_range') {
                if (this.customBudget && !this.customBudgetValue.trim()) {
                    this.errors.budget_range = {!! json_encode(__('messages.select_budget')) !!};
                    return;
                }
            }
            this.nextStep();
        },

        handleSubmit(event) {
            this.errors = {};
            let valid = true;

            if (!this.firstName.trim()) {
                this.errors.first_name = {!! json_encode(__('messages.first_name_required')) !!};
                valid = false;
            }
            if (!this.lastName.trim()) {
                this.errors.last_name = {!! json_encode(__('messages.last_name_required')) !!};
                valid = false;
            }
            if (!this.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                this.errors.email = {!! json_encode(__('messages.valid_email_required')) !!};
                valid = false;
            }
            if (this.contactMethods.length === 0) {
                this.errors.contact_methods = {!! json_encode(__('messages.select_contact_method')) !!};
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                return;
            }
            this.submitting = true;
        }
    };
}
</script>
@endsection
