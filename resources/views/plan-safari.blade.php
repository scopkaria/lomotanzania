@extends('layouts.app')

@section('content')
<div x-data="safariPlanner()" x-cloak class="min-h-screen bg-brand-light">

    {{-- â•â•â•â•â•â•â•â•â•â•â• SUCCESS STATE â•â•â•â•â•â•â•â•â•â•â• --}}
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
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.61.609l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.357 0-4.542-.803-6.271-2.15l-.438-.353-3.135 1.052 1.052-3.135-.353-.438A9.955 9.955 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                    {{ __('messages.continue_whatsapp') }}
                </a>
            @endif
            <div class="mt-6">
                <a href="{{ url('/') }}" class="text-sm text-brand-green hover:underline">{{ __('messages.back_to_home') }}</a>
            </div>
        </div>
    </div>
    @else

    {{-- â•â•â•â•â•â•â•â•â•â•â• PROGRESS BAR (STEPS 1-6) â•â•â•â•â•â•â•â•â•â•â• --}}
    <div x-show="currentStep > 0" x-transition class="sticky top-0 z-40 bg-white/90 backdrop-blur-sm border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-6 py-3 flex items-center justify-between">
            <button @click="prevStep()" x-show="currentStep > (hasSafari ? 1 : 0)"
                    class="flex items-center gap-1 text-sm text-gray-500 hover:text-brand-dark transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('messages.back') }}
            </button>
            <div x-show="currentStep === 0"></div>
            <div class="flex items-center gap-2">
                <template x-for="s in totalSteps" :key="s">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                             :class="s < currentStep ? 'bg-brand-green text-white' : s === currentStep ? 'bg-brand-gold text-brand-dark' : 'bg-gray-200 text-gray-400'"
                             x-text="s"></div>
                        <div x-show="s < totalSteps" class="w-6 h-px" :class="s < currentStep ? 'bg-brand-green' : 'bg-gray-200'"></div>
                    </div>
                </template>
            </div>
            <span class="text-xs text-gray-400 tracking-wide" x-text="`{!! str_replace([':x', ':y'], ['${currentStep}', '${totalSteps}'], __('messages.step_x_of_y')) !!}`"></span>
        </div>
    </div>

    {{-- â•â•â•â•â•â•â•â•â•â•â• FORM WRAPPER â•â•â•â•â•â•â•â•â•â•â• --}}
    <form action="{{ route('plan-safari.store') }}" method="POST" @submit="handleSubmit($event)">
        @csrf
        <input type="hidden" name="safari_id" :value="safariId">
        <input type="hidden" name="know_destination" :value="knowDestination">

        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 0 "” INTRO â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-brand-green relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=600&fit=crop')] bg-cover bg-center opacity-15"></div>
                <div class="relative max-w-3xl mx-auto px-6 py-24 text-center">
                    <p class="text-brand-gold text-xs uppercase tracking-[5px] font-semibold mb-5 hero-animate hero-delay-1">{{ __('messages.safari_planning') }}</p>
                    <h1 class="font-heading text-4xl md:text-5xl font-bold text-white leading-tight mb-5 hero-animate hero-delay-2">
                        {{ $settings->get('intro')?->title ?? 'Your dream African safari starts here' }}
                    </h1>
                    <p class="text-white/85 text-lg leading-relaxed max-w-xl mx-auto hero-animate hero-delay-3">
                        {{ $settings->get('intro')?->description ?? 'Answer a few quick questions and our safari experts will craft a personalised itinerary tailored to your pace, interests, and budget.' }}
                    </p>
                </div>
            </div>

            <div class="max-w-2xl mx-auto px-6 py-16">
                <h2 class="font-heading text-2xl font-bold text-brand-dark text-center mb-3">{{ __('messages.do_you_know_where') }}</h2>
                <p class="text-gray-500 text-center text-sm mb-10">{{ __('messages.helps_personalize') }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <template x-for="option in knowOptions" :key="option">
                        <button type="button" @click="knowDestination = option; nextStep()"
                                class="group p-6 rounded-xl border-2 transition-all duration-200 text-center cursor-pointer"
                                :class="knowDestination === option ? 'border-brand-gold bg-brand-gold/5' : 'border-gray-200 hover:border-brand-gold/50 bg-white'">
                            <div class="w-10 h-10 rounded-full mx-auto mb-3 flex items-center justify-center transition-colors"
                                 :class="knowDestination === option ? 'bg-brand-gold text-brand-dark' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-gold/10 group-hover:text-brand-gold'">
                                <svg x-show="option === 'Yes, I do!'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="option === 'I have an idea, need advice'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <svg x-show="option === 'No - Help me decide!'" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </div>
                            <span class="text-sm font-semibold" :class="knowDestination === option ? 'text-brand-dark' : 'text-gray-700'" x-text="option"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>


        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 1 "” DESTINATIONS â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-3xl mx-auto px-6 py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 1</p>
                    <h2 class="font-heading text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('destinations')?->title ?? 'Where would you like to travel?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('destinations')?->description ?? 'Select one or more destinations that interest you' }}</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($destinations as $destination)
                    <label class="relative cursor-pointer group">
                        <input type="checkbox" name="destinations[]" value="{{ $destination->id }}"
                               x-model.number="selectedDestinations"
                               class="sr-only peer">
                        <div class="rounded-xl border-2 overflow-hidden transition-all duration-200
                                    peer-checked:border-brand-gold peer-checked:ring-2 peer-checked:ring-brand-gold/30
                                    border-gray-200 hover:border-brand-gold/50">
                            @if($destination->featured_image)
                                <div class="h-28 bg-gray-100 overflow-hidden">
                                    <img src="{{ asset('storage/' . $destination->featured_image) }}" alt="{{ $destination->translated('name') }}"
                                         loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div class="h-28 bg-gradient-to-br from-brand-green/10 to-brand-green/5 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-brand-green/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </div>
                            @endif
                            <div class="p-3 text-center">
                                <span class="text-sm font-semibold text-brand-dark">{{ $destination->translated('name') }}</span>
                            </div>
                            {{-- Check indicator --}}
                            <div class="absolute top-2 right-2 w-6 h-6 rounded-full flex items-center justify-center transition-all
                                        peer-checked:bg-brand-gold peer-checked:text-brand-dark bg-white/80 text-transparent">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
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


        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 2 "” TRAVEL TIME â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 2</p>
                    <h2 class="font-heading text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('travel_time')?->title ?? 'When would you like to travel?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('travel_time')?->description ?? 'Select your preferred travel months' }}</p>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <template x-for="month in months" :key="month">
                        <label class="cursor-pointer">
                            <input type="checkbox" name="months[]" :value="month" x-model="selectedMonths" class="sr-only peer">
                            <div class="rounded-xl border-2 py-4 text-center transition-all duration-200
                                        peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 peer-checked:text-brand-dark
                                        border-gray-200 hover:border-brand-gold/50 text-gray-600">
                                <span class="text-sm font-semibold" x-text="month"></span>
                            </div>
                        </label>
                    </template>
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


        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 3 "” TRAVEL GROUP â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 3</p>
                    <h2 class="font-heading text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('travel_group')?->title ?? 'Who are you traveling with?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('travel_group')?->description ?? 'This helps us tailor your experience' }}</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-w-lg mx-auto">
                    <template x-for="group in travelGroups" :key="group.label">
                        <label class="cursor-pointer">
                            <input type="radio" name="travel_group" :value="group.label" x-model="selectedGroup" class="sr-only peer">
                            <div class="rounded-xl border-2 py-5 text-center transition-all duration-200
                                        peer-checked:border-brand-gold peer-checked:bg-brand-gold/10
                                        border-gray-200 hover:border-brand-gold/50">
                                <div class="text-2xl mb-2" x-text="group.icon"></div>
                                <span class="text-sm font-semibold text-brand-dark" x-text="group.label"></span>
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


        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 4 "” INTERESTS â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-3xl mx-auto px-6 py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 4</p>
                    <h2 class="font-heading text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('interests')?->title ?? 'What experiences excite you most?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('interests')?->description ?? 'Select all that appeal to you' }}</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($tourTypes as $tourType)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="interests[]" value="{{ $tourType->name }}" x-model="selectedInterests" class="sr-only peer">
                        <div class="rounded-xl border-2 py-5 px-4 text-center transition-all duration-200
                                    peer-checked:border-brand-gold peer-checked:bg-brand-gold/10
                                    border-gray-200 hover:border-brand-gold/50">
                            <span class="text-sm font-semibold text-brand-dark">{{ $tourType->name }}</span>
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


        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 5 "” BUDGET â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">Step 5</p>
                    <h2 class="font-heading text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('budget')?->title ?? 'What is your budget range?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('budget')?->description ?? 'Per person, approximate range' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md mx-auto">
                    @foreach($budgetRanges as $range)
                    <label class="cursor-pointer">
                        <input type="radio" name="budget_range" value="{{ $range }}" x-model="selectedBudget" class="sr-only peer">
                        <div class="rounded-xl border-2 py-5 text-center transition-all duration-200
                                    peer-checked:border-brand-gold peer-checked:bg-brand-gold/10
                                    border-gray-200 hover:border-brand-gold/50">
                            <span class="text-sm font-semibold text-brand-dark">{{ $range }}</span>
                        </div>
                    </label>
                    @endforeach
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


        {{-- â•â•â•â•â•â•â•â•â•â•â• STEP 6 "” CONTACT DETAILS â•â•â•â•â•â•â•â•â•â•â• --}}
        <div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl mx-auto px-6 py-16">
                <div class="text-center mb-10">
                    <p class="text-brand-gold text-xs uppercase tracking-[4px] font-semibold mb-3">{{ __('messages.final_step') }}</p>
                    <h2 class="font-heading text-3xl font-bold text-brand-dark mb-2">
                        {{ $settings->get('contact')?->title ?? 'How should we contact you?' }}
                    </h2>
                    <p class="text-gray-500 text-sm">{{ $settings->get('contact')?->description ?? 'Share your details so our team can reach out' }}</p>
                </div>

                <div class="space-y-6">
                    {{-- Names --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.first_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" x-model="firstName" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                   placeholder="First name">
                            <p x-show="errors.first_name" class="mt-1 text-sm text-red-500" x-text="errors.first_name"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.last_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" x-model="lastName" required
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                                   placeholder="Last name">
                            <p x-show="errors.last_name" class="mt-1 text-sm text-red-500" x-text="errors.last_name"></p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                        <input type="email" name="email" x-model="email" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
                               placeholder="you@example.com">
                        <p x-show="errors.email" class="mt-1 text-sm text-red-500" x-text="errors.email"></p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone') }}</label>
                        <div class="flex gap-3">
                            <select name="country_code" x-model="countryCode"
                                    class="w-28 rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-3">
                                <option value=->Code</option>
                                <option value="+1">+1 US</option>
                                <option value="+44">+44 UK</option>
                                <option value="+255">+255 TZ</option>
                                <option value="+254">+254 KE</option>
                                <option value="+256">+256 UG</option>
                                <option value="+27">+27 ZA</option>
                                <option value="+49">+49 DE</option>
                                <option value="+33">+33 FR</option>
                                <option value="+61">+61 AU</option>
                                <option value="+91">+91 IN</option>
                                <option value="+86">+86 CN</option>
                                <option value="+81">+81 JP</option>
                                <option value="+971">+971 AE</option>
                                <option value="+31">+31 NL</option>
                                <option value="+34">+34 ES</option>
                                <option value="+39">+39 IT</option>
                                <option value="+46">+46 SE</option>
                                <option value="+47">+47 NO</option>
                            </select>
                            <input type="tel" name="phone" x-model="phone"
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm py-3 px-4"
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
                                                peer-checked:border-brand-gold peer-checked:bg-brand-gold/10 peer-checked:text-brand-dark
                                                peer-disabled:opacity-40 peer-disabled:cursor-not-allowed
                                                border-gray-200 hover:border-brand-gold/50 text-gray-600"
                                         x-text="method"></div>
                                </label>
                            </template>
                        </div>
                        <p x-show="errors.contact_methods" class="mt-2 text-sm text-red-500" x-text="errors.contact_methods"></p>
                    </div>

                    {{-- Updates checkbox --}}
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="wants_updates" value="1" x-model="wantsUpdates"
                               class="rounded border-gray-300 text-brand-gold focus:ring-brand-gold">
                        <span class="text-sm text-gray-600">{{ __('messages.receive_updates') }}</span>
                    </label>
                </div>

                <div class="mt-4 text-center">
                    <p x-show="errors.contact" class="text-red-500 text-sm" x-text="errors.contact"></p>
                </div>

                <div class="mt-10 text-center">
                    <button type="submit"
                            :disabled="submitting"
                            class="inline-flex items-center gap-2 px-10 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-lg hover:brightness-90 hover:scale-[1.02] transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-text="submitting ? {!! json_encode(__('messages.submitting')) !!} : {!! json_encode(__('messages.start_planning_safari')) !!}"></span>
                    </button>
                    <p class="mt-3 text-xs text-gray-400">{{ __('messages.we_respond_24h') }}</p>
                </div>
            </div>
        </div>
    </form>

    @endif
</div>

@push('styles')
<style>
    /* Hide default checkboxes inside cards */
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
</style>
@endpush

<script>
    function safariPlanner() {
        return {
            // Entry mode
            safariId: {{ $safariId ?: 'null' }},
            hasSafari: {{ $safariId ? 'true' : 'false' }},
            currentStep: {{ $safariId ? 1 : 0 }},
            totalSteps: 6,

            // Step 0
            knowDestination: '',
            knowOptions: [{!! json_encode(__('messages.yes_i_do')) !!}, {!! json_encode(__('messages.have_idea')) !!}, {!! json_encode(__('messages.no_help_decide')) !!}],

            // Step 1
            selectedDestinations: {!! json_encode($preselectedDestinations) !!},

            // Step 2
            months: [{!! json_encode(__('messages.january')) !!}, {!! json_encode(__('messages.february')) !!}, {!! json_encode(__('messages.march')) !!}, {!! json_encode(__('messages.april')) !!}, {!! json_encode(__('messages.may')) !!}, {!! json_encode(__('messages.june')) !!}, {!! json_encode(__('messages.july')) !!}, {!! json_encode(__('messages.august')) !!}, {!! json_encode(__('messages.september')) !!}, {!! json_encode(__('messages.october')) !!}, {!! json_encode(__('messages.november')) !!}, {!! json_encode(__('messages.december')) !!}],
            selectedMonths: [],

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
            selectedBudget: '',

            // Step 6
            firstName: '',
            lastName: '',
            email: '',
            countryCode: '',
            phone: '',
            contactMethodOptions: ['Email', 'WhatsApp', 'Phone', 'Video Call'],
            contactMethods: [],
            wantsUpdates: false,

            // State
            errors: {},
            submitting: false,

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
                if (field === 'months' && this.selectedMonths.length === 0) {
                    this.errors.months = {!! json_encode(__('messages.select_month')) !!};
                    return;
                }
                if (field === 'travel_group' && !this.selectedGroup) {
                    this.errors.travel_group = {!! json_encode(__('messages.select_group')) !!};
                    return;
                }
                if (field === 'budget_range' && !this.selectedBudget) {
                    this.errors.budget_range = {!! json_encode(__('messages.select_budget')) !!};
                    return;
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
