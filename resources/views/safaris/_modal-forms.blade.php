{{-- ========== SAFARI MODAL FORMS (Booking + Inquiry) ========== --}}

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Flatpickr brand theme */
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #FEBC11 !important;
        border-color: #FEBC11 !important;
        color: #131414 !important;
    }
    .flatpickr-day:hover { background: #FEBC11/20; }
    .flatpickr-months .flatpickr-month { height: 40px; }

    /* Modal transitions */
    .modal-backdrop { transition: opacity 0.25s ease; }
    .modal-panel { transition: opacity 0.25s ease, transform 0.25s ease; }
    .modal-panel.entering { opacity: 0; transform: scale(0.95); }
    .modal-panel.entered { opacity: 1; transform: scale(1); }

    /* Focus ring override */
    .lomo-input:focus {
        border-color: #FEBC11 !important;
        box-shadow: 0 0 0 3px rgba(254, 188, 17, 0.15) !important;
        outline: none !important;
    }

    /* Country search dropdown */
    .country-dropdown {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .country-dropdown::-webkit-scrollbar { width: 6px; }
    .country-dropdown::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

    /* Phone code dropdown */
    .phone-code-dropdown {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .phone-code-dropdown::-webkit-scrollbar { width: 6px; }
    .phone-code-dropdown::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
</style>
@endpush

{{-- ==================== BOOKING MODAL ==================== --}}
<template x-teleport="body">
    <div x-show="bookingOpen" x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
         @keydown.escape.window="bookingOpen = false">

        {{-- Overlay --}}
        <div x-show="bookingOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="bookingOpen = false"
             class="absolute inset-0 bg-black/60"></div>

        {{-- Panel --}}
        <div x-show="bookingOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10"
             @click.stop
             x-data="safariBookingForm()"
             x-init="$watch('$root.bookingOpen', v => { if(v) { $nextTick(() => $refs.bookingName.focus()); document.body.style.overflow='hidden'; } else { document.body.style.overflow=''; } })">

            {{-- Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-5 rounded-t-2xl z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading text-xl font-bold text-brand-dark">Book This Safari</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $safari->translated('title') }}</p>
                    </div>
                    <button @click="bookingOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Success State --}}
            <div x-show="submitted" x-cloak class="px-6 py-14 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </div>
                <h4 class="font-heading text-xl font-bold text-brand-dark mb-2">Booking Request Sent!</h4>
                <p class="text-gray-600 text-sm leading-relaxed">Our safari expert will contact you within 24 hours to confirm your booking.</p>
                <button @click="bookingOpen = false" class="mt-6 px-6 py-2.5 bg-brand-gold text-brand-dark font-semibold rounded-xl hover:bg-yellow-400 transition">Close</button>
            </div>

            {{-- Form --}}
            <form x-show="!submitted" @submit.prevent="submitForm" class="px-6 py-6 space-y-5">
                <input type="hidden" name="safari_package_id" value="{{ $safari->id }}">
                <input type="hidden" name="inquiry_type" value="booking">

                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" x-model="form.name" x-ref="bookingName" required
                           class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                           placeholder="John Smith">
                    <template x-if="errors.name"><p class="text-xs text-red-500 mt-1" x-text="errors.name[0]"></p></template>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" x-model="form.email" required
                           class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                           placeholder="john@example.com">
                    <template x-if="errors.email"><p class="text-xs text-red-500 mt-1" x-text="errors.email[0]"></p></template>
                </div>

                {{-- Phone (split: code + number) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                    <div class="flex gap-2" x-data="phoneCodePicker()" x-init="init()">
                        {{-- Country code --}}
                        <div class="relative" style="min-width:110px; max-width:130px;">
                            <button type="button" @click="open = !open"
                                    class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3 py-3 text-sm text-brand-dark flex items-center gap-1.5 transition">
                                <span x-text="selectedCode" class="font-medium"></span>
                                <svg class="w-3 h-3 text-gray-400 ml-auto shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute top-full left-0 mt-1 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                <div class="p-2">
                                    <input type="text" x-model="codeSearch" placeholder="Search country..."
                                           class="w-full rounded-md border border-gray-200 px-3 py-2 text-xs focus:border-brand-gold focus:ring-0 transition"
                                           @click.stop>
                                </div>
                                <div class="phone-code-dropdown">
                                    <template x-for="c in filteredCodes" :key="c.code + c.name">
                                        <button type="button"
                                                @click="selectCode(c); $dispatch('input')"
                                                class="w-full text-left px-3 py-2 text-xs hover:bg-brand-gold/10 flex items-center gap-2 transition">
                                            <span x-text="c.code" class="font-medium text-brand-dark w-12 shrink-0"></span>
                                            <span x-text="c.name" class="text-gray-600 truncate"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        {{-- Phone number --}}
                        <input type="tel" x-model="$parent.form.phone"
                               class="lomo-input flex-1 rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                               placeholder="712 345 678">
                    </div>
                    <template x-if="errors.phone"><p class="text-xs text-red-500 mt-1" x-text="errors.phone[0]"></p></template>
                </div>

                {{-- Country (searchable dropdown) --}}
                <div x-data="countryPicker()" x-init="init()">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                    <div class="relative">
                        <input type="text" x-model="search" @focus="open = true" @click="open = true"
                               @input="open = true"
                               class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                               placeholder="Select your country">
                        <div x-show="open && filteredCountries.length > 0" x-cloak @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-gray-200 z-50 country-dropdown">
                            <template x-for="country in filteredCountries" :key="country">
                                <button type="button"
                                        @click="selectCountry(country)"
                                        class="w-full text-left px-4 py-2.5 text-sm hover:bg-brand-gold/10 text-gray-700 transition"
                                        x-text="country"></button>
                            </template>
                        </div>
                    </div>
                    <template x-if="$parent.errors.country"><p class="text-xs text-red-500 mt-1" x-text="$parent.errors.country[0]"></p></template>
                </div>

                {{-- Travel Date + Number of People --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Travel Date</label>
                        <input type="text" x-model="form.travel_date" x-ref="bookingDate" readonly
                               class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition cursor-pointer"
                               placeholder="Select date">
                        <template x-if="errors.travel_date"><p class="text-xs text-red-500 mt-1" x-text="errors.travel_date[0]"></p></template>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Number of People</label>
                        <input type="number" x-model="form.number_of_people" min="1" max="999"
                               class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                               placeholder="2">
                    </div>
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                    <textarea x-model="form.message" rows="3"
                              class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition resize-none"
                              placeholder="Any special requests or questions..."></textarea>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading"
                        class="w-full bg-brand-gold text-brand-dark font-bold py-3.5 rounded-xl hover:bg-yellow-400 hover:shadow-lg hover:shadow-brand-gold/20 active:scale-[0.98] transition-all duration-200 text-sm tracking-wide disabled:opacity-60 cursor-pointer">
                    <span x-show="!loading">Book This Safari</span>
                    <span x-show="loading" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Submitting…
                    </span>
                </button>

                <p class="text-xs text-gray-400 text-center">We never share your details. Response within 24h.</p>
            </form>
        </div>
    </div>
</template>


{{-- ==================== INQUIRY MODAL ==================== --}}
<template x-teleport="body">
    <div x-show="inquiryOpen" x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
         @keydown.escape.window="inquiryOpen = false">

        {{-- Overlay --}}
        <div x-show="inquiryOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="inquiryOpen = false"
             class="absolute inset-0 bg-black/60"></div>

        {{-- Panel --}}
        <div x-show="inquiryOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10"
             @click.stop
             x-data="safariInquiryForm()"
             x-init="$watch('$root.inquiryOpen', v => { if(v) { $nextTick(() => $refs.inquiryName.focus()); document.body.style.overflow='hidden'; } else { document.body.style.overflow=''; } })">

            {{-- Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-5 rounded-t-2xl z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading text-xl font-bold text-brand-dark">Inquire About This Safari</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $safari->translated('title') }}</p>
                    </div>
                    <button @click="inquiryOpen = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Success State --}}
            <div x-show="submitted" x-cloak class="px-6 py-14 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                </div>
                <h4 class="font-heading text-xl font-bold text-brand-dark mb-2">Thank You!</h4>
                <p class="text-gray-600 text-sm leading-relaxed">Our safari expert will contact you shortly. We typically respond within 24 hours.</p>
                <button @click="inquiryOpen = false" class="mt-6 px-6 py-2.5 bg-brand-gold text-brand-dark font-semibold rounded-xl hover:bg-yellow-400 transition">Close</button>
            </div>

            {{-- Form --}}
            <form x-show="!submitted" @submit.prevent="submitForm" class="px-6 py-6 space-y-5">
                <input type="hidden" name="safari_package_id" value="{{ $safari->id }}">
                <input type="hidden" name="inquiry_type" value="inquiry">

                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <input type="text" x-model="form.name" x-ref="inquiryName" required
                           class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                           placeholder="John Smith">
                    <template x-if="errors.name"><p class="text-xs text-red-500 mt-1" x-text="errors.name[0]"></p></template>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input type="email" x-model="form.email" required
                           class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                           placeholder="john@example.com">
                    <template x-if="errors.email"><p class="text-xs text-red-500 mt-1" x-text="errors.email[0]"></p></template>
                </div>

                {{-- Phone (split: code + number) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                    <div class="flex gap-2" x-data="phoneCodePicker()" x-init="init()">
                        <div class="relative" style="min-width:110px; max-width:130px;">
                            <button type="button" @click="open = !open"
                                    class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3 py-3 text-sm text-brand-dark flex items-center gap-1.5 transition">
                                <span x-text="selectedCode" class="font-medium"></span>
                                <svg class="w-3 h-3 text-gray-400 ml-auto shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute top-full left-0 mt-1 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                <div class="p-2">
                                    <input type="text" x-model="codeSearch" placeholder="Search country..."
                                           class="w-full rounded-md border border-gray-200 px-3 py-2 text-xs focus:border-brand-gold focus:ring-0 transition"
                                           @click.stop>
                                </div>
                                <div class="phone-code-dropdown">
                                    <template x-for="c in filteredCodes" :key="c.code + c.name">
                                        <button type="button"
                                                @click="selectCode(c); $dispatch('input')"
                                                class="w-full text-left px-3 py-2 text-xs hover:bg-brand-gold/10 flex items-center gap-2 transition">
                                            <span x-text="c.code" class="font-medium text-brand-dark w-12 shrink-0"></span>
                                            <span x-text="c.name" class="text-gray-600 truncate"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <input type="tel" x-model="$parent.form.phone"
                               class="lomo-input flex-1 rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                               placeholder="712 345 678">
                    </div>
                    <template x-if="errors.phone"><p class="text-xs text-red-500 mt-1" x-text="errors.phone[0]"></p></template>
                </div>

                {{-- Country (searchable dropdown) --}}
                <div x-data="countryPicker()" x-init="init()">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                    <div class="relative">
                        <input type="text" x-model="search" @focus="open = true" @click="open = true"
                               @input="open = true"
                               class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                               placeholder="Select your country">
                        <div x-show="open && filteredCountries.length > 0" x-cloak @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-gray-200 z-50 country-dropdown">
                            <template x-for="country in filteredCountries" :key="country">
                                <button type="button"
                                        @click="selectCountry(country)"
                                        class="w-full text-left px-4 py-2.5 text-sm hover:bg-brand-gold/10 text-gray-700 transition"
                                        x-text="country"></button>
                            </template>
                        </div>
                    </div>
                    <template x-if="$parent.errors.country"><p class="text-xs text-red-500 mt-1" x-text="$parent.errors.country[0]"></p></template>
                </div>

                {{-- Travel Date + Number of People --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Travel Date</label>
                        <input type="text" x-model="form.travel_date" x-ref="inquiryDate" readonly
                               class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition cursor-pointer"
                               placeholder="Select date">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Number of People</label>
                        <input type="number" x-model="form.number_of_people" min="1" max="999"
                               class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition"
                               placeholder="2">
                    </div>
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                    <textarea x-model="form.message" rows="3"
                              class="lomo-input w-full rounded-lg border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-brand-dark placeholder-gray-400 transition resize-none"
                              placeholder="Tell us about your ideal safari experience..."></textarea>
                </div>

                {{-- Preferred Contact Methods --}}
                <div x-data="contactMethodPicker()">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Contact Methods <span class="text-gray-400 font-normal">(choose up to 2)</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="method in methods" :key="method.value">
                            <label @click.prevent="toggle(method.value)"
                                   class="flex items-center gap-2.5 px-4 py-3 rounded-lg border cursor-pointer transition-all text-sm"
                                   :class="isSelected(method.value) ? 'border-brand-gold bg-brand-gold/5 text-brand-dark font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                                <div class="w-4.5 h-4.5 rounded border-2 flex items-center justify-center shrink-0 transition-all"
                                     :class="isSelected(method.value) ? 'border-brand-gold bg-brand-gold' : 'border-gray-300'">
                                    <svg x-show="isSelected(method.value)" class="w-3 h-3 text-brand-dark" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                <span x-text="method.label"></span>
                            </label>
                        </template>
                    </div>
                    <p x-show="warning" x-cloak class="text-xs text-amber-600 mt-1.5">You can select a maximum of 2 contact methods.</p>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading"
                        class="w-full bg-brand-gold text-brand-dark font-bold py-3.5 rounded-xl hover:bg-yellow-400 hover:shadow-lg hover:shadow-brand-gold/20 active:scale-[0.98] transition-all duration-200 text-sm tracking-wide disabled:opacity-60 cursor-pointer">
                    <span x-show="!loading">Send Inquiry</span>
                    <span x-show="loading" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Sending…
                    </span>
                </button>

                <p class="text-xs text-gray-400 text-center">We never share your details. Response within 24h.</p>
            </form>
        </div>
    </div>
</template>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// ========== SHARED DATA ===========

const WORLD_COUNTRIES = [
    "Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria",
    "Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan",
    "Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cabo Verde","Cambodia",
    "Cameroon","Canada","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo (DRC)","Congo (Republic)",
    "Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor",
    "Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Eswatini","Ethiopia","Fiji","Finland",
    "France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea",
    "Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq",
    "Ireland","Israel","Italy","Ivory Coast","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati",
    "Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein",
    "Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania",
    "Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar",
    "Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia",
    "Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines",
    "Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines",
    "Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore",
    "Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan",
    "Suriname","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga",
    "Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States",
    "Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"
];

const PHONE_CODES = [
    {code: "+93", name: "Afghanistan"}, {code: "+355", name: "Albania"}, {code: "+213", name: "Algeria"},
    {code: "+376", name: "Andorra"}, {code: "+244", name: "Angola"}, {code: "+1-268", name: "Antigua and Barbuda"},
    {code: "+54", name: "Argentina"}, {code: "+374", name: "Armenia"}, {code: "+61", name: "Australia"},
    {code: "+43", name: "Austria"}, {code: "+994", name: "Azerbaijan"}, {code: "+1-242", name: "Bahamas"},
    {code: "+973", name: "Bahrain"}, {code: "+880", name: "Bangladesh"}, {code: "+1-246", name: "Barbados"},
    {code: "+375", name: "Belarus"}, {code: "+32", name: "Belgium"}, {code: "+501", name: "Belize"},
    {code: "+229", name: "Benin"}, {code: "+975", name: "Bhutan"}, {code: "+591", name: "Bolivia"},
    {code: "+387", name: "Bosnia"}, {code: "+267", name: "Botswana"}, {code: "+55", name: "Brazil"},
    {code: "+673", name: "Brunei"}, {code: "+359", name: "Bulgaria"}, {code: "+226", name: "Burkina Faso"},
    {code: "+257", name: "Burundi"}, {code: "+238", name: "Cabo Verde"}, {code: "+855", name: "Cambodia"},
    {code: "+237", name: "Cameroon"}, {code: "+1", name: "Canada"}, {code: "+236", name: "Central African Republic"},
    {code: "+235", name: "Chad"}, {code: "+56", name: "Chile"}, {code: "+86", name: "China"},
    {code: "+57", name: "Colombia"}, {code: "+269", name: "Comoros"}, {code: "+243", name: "Congo (DRC)"},
    {code: "+242", name: "Congo (Republic)"}, {code: "+506", name: "Costa Rica"}, {code: "+385", name: "Croatia"},
    {code: "+53", name: "Cuba"}, {code: "+357", name: "Cyprus"}, {code: "+420", name: "Czech Republic"},
    {code: "+45", name: "Denmark"}, {code: "+253", name: "Djibouti"}, {code: "+1-767", name: "Dominica"},
    {code: "+1-809", name: "Dominican Republic"}, {code: "+670", name: "East Timor"}, {code: "+593", name: "Ecuador"},
    {code: "+20", name: "Egypt"}, {code: "+503", name: "El Salvador"}, {code: "+240", name: "Equatorial Guinea"},
    {code: "+291", name: "Eritrea"}, {code: "+372", name: "Estonia"}, {code: "+268", name: "Eswatini"},
    {code: "+251", name: "Ethiopia"}, {code: "+679", name: "Fiji"}, {code: "+358", name: "Finland"},
    {code: "+33", name: "France"}, {code: "+241", name: "Gabon"}, {code: "+220", name: "Gambia"},
    {code: "+995", name: "Georgia"}, {code: "+49", name: "Germany"}, {code: "+233", name: "Ghana"},
    {code: "+30", name: "Greece"}, {code: "+1-473", name: "Grenada"}, {code: "+502", name: "Guatemala"},
    {code: "+224", name: "Guinea"}, {code: "+245", name: "Guinea-Bissau"}, {code: "+592", name: "Guyana"},
    {code: "+509", name: "Haiti"}, {code: "+504", name: "Honduras"}, {code: "+36", name: "Hungary"},
    {code: "+354", name: "Iceland"}, {code: "+91", name: "India"}, {code: "+62", name: "Indonesia"},
    {code: "+98", name: "Iran"}, {code: "+964", name: "Iraq"}, {code: "+353", name: "Ireland"},
    {code: "+972", name: "Israel"}, {code: "+39", name: "Italy"}, {code: "+225", name: "Ivory Coast"},
    {code: "+1-876", name: "Jamaica"}, {code: "+81", name: "Japan"}, {code: "+962", name: "Jordan"},
    {code: "+7", name: "Kazakhstan"}, {code: "+254", name: "Kenya"}, {code: "+686", name: "Kiribati"},
    {code: "+383", name: "Kosovo"}, {code: "+965", name: "Kuwait"}, {code: "+996", name: "Kyrgyzstan"},
    {code: "+856", name: "Laos"}, {code: "+371", name: "Latvia"}, {code: "+961", name: "Lebanon"},
    {code: "+266", name: "Lesotho"}, {code: "+231", name: "Liberia"}, {code: "+218", name: "Libya"},
    {code: "+423", name: "Liechtenstein"}, {code: "+370", name: "Lithuania"}, {code: "+352", name: "Luxembourg"},
    {code: "+261", name: "Madagascar"}, {code: "+265", name: "Malawi"}, {code: "+60", name: "Malaysia"},
    {code: "+960", name: "Maldives"}, {code: "+223", name: "Mali"}, {code: "+356", name: "Malta"},
    {code: "+692", name: "Marshall Islands"}, {code: "+222", name: "Mauritania"}, {code: "+230", name: "Mauritius"},
    {code: "+52", name: "Mexico"}, {code: "+691", name: "Micronesia"}, {code: "+373", name: "Moldova"},
    {code: "+377", name: "Monaco"}, {code: "+976", name: "Mongolia"}, {code: "+382", name: "Montenegro"},
    {code: "+212", name: "Morocco"}, {code: "+258", name: "Mozambique"}, {code: "+95", name: "Myanmar"},
    {code: "+264", name: "Namibia"}, {code: "+674", name: "Nauru"}, {code: "+977", name: "Nepal"},
    {code: "+31", name: "Netherlands"}, {code: "+64", name: "New Zealand"}, {code: "+505", name: "Nicaragua"},
    {code: "+227", name: "Niger"}, {code: "+234", name: "Nigeria"}, {code: "+850", name: "North Korea"},
    {code: "+389", name: "North Macedonia"}, {code: "+47", name: "Norway"}, {code: "+968", name: "Oman"},
    {code: "+92", name: "Pakistan"}, {code: "+680", name: "Palau"}, {code: "+970", name: "Palestine"},
    {code: "+507", name: "Panama"}, {code: "+675", name: "Papua New Guinea"}, {code: "+595", name: "Paraguay"},
    {code: "+51", name: "Peru"}, {code: "+63", name: "Philippines"}, {code: "+48", name: "Poland"},
    {code: "+351", name: "Portugal"}, {code: "+974", name: "Qatar"}, {code: "+40", name: "Romania"},
    {code: "+7", name: "Russia"}, {code: "+250", name: "Rwanda"}, {code: "+1-869", name: "Saint Kitts and Nevis"},
    {code: "+1-758", name: "Saint Lucia"}, {code: "+1-784", name: "St. Vincent"}, {code: "+685", name: "Samoa"},
    {code: "+378", name: "San Marino"}, {code: "+239", name: "Sao Tome"}, {code: "+966", name: "Saudi Arabia"},
    {code: "+221", name: "Senegal"}, {code: "+381", name: "Serbia"}, {code: "+248", name: "Seychelles"},
    {code: "+232", name: "Sierra Leone"}, {code: "+65", name: "Singapore"}, {code: "+421", name: "Slovakia"},
    {code: "+386", name: "Slovenia"}, {code: "+677", name: "Solomon Islands"}, {code: "+252", name: "Somalia"},
    {code: "+27", name: "South Africa"}, {code: "+82", name: "South Korea"}, {code: "+211", name: "South Sudan"},
    {code: "+34", name: "Spain"}, {code: "+94", name: "Sri Lanka"}, {code: "+249", name: "Sudan"},
    {code: "+597", name: "Suriname"}, {code: "+46", name: "Sweden"}, {code: "+41", name: "Switzerland"},
    {code: "+963", name: "Syria"}, {code: "+886", name: "Taiwan"}, {code: "+992", name: "Tajikistan"},
    {code: "+255", name: "Tanzania"}, {code: "+66", name: "Thailand"}, {code: "+228", name: "Togo"},
    {code: "+676", name: "Tonga"}, {code: "+1-868", name: "Trinidad and Tobago"}, {code: "+216", name: "Tunisia"},
    {code: "+90", name: "Turkey"}, {code: "+993", name: "Turkmenistan"}, {code: "+688", name: "Tuvalu"},
    {code: "+256", name: "Uganda"}, {code: "+380", name: "Ukraine"}, {code: "+971", name: "UAE"},
    {code: "+44", name: "United Kingdom"}, {code: "+1", name: "United States"}, {code: "+598", name: "Uruguay"},
    {code: "+998", name: "Uzbekistan"}, {code: "+678", name: "Vanuatu"}, {code: "+39", name: "Vatican City"},
    {code: "+58", name: "Venezuela"}, {code: "+84", name: "Vietnam"}, {code: "+967", name: "Yemen"},
    {code: "+260", name: "Zambia"}, {code: "+263", name: "Zimbabwe"}
];

// ========== ALPINE COMPONENTS ===========

function phoneCodePicker() {
    return {
        open: false,
        codeSearch: '',
        selectedCode: '+255',
        selectedName: 'Tanzania',
        codes: PHONE_CODES,
        init() {},
        get filteredCodes() {
            if (!this.codeSearch) return this.codes;
            const q = this.codeSearch.toLowerCase();
            return this.codes.filter(c => c.name.toLowerCase().includes(q) || c.code.includes(q));
        },
        selectCode(c) {
            this.selectedCode = c.code;
            this.selectedName = c.name;
            this.open = false;
            this.codeSearch = '';
            // Update parent form's country_code
            if (this.$parent && this.$parent.form) {
                this.$parent.form.country_code = c.code;
            }
        }
    };
}

function countryPicker() {
    return {
        open: false,
        search: '',
        countries: WORLD_COUNTRIES,
        init() {},
        get filteredCountries() {
            if (!this.search) return this.countries.slice(0, 20);
            const q = this.search.toLowerCase();
            return this.countries.filter(c => c.toLowerCase().includes(q)).slice(0, 15);
        },
        selectCountry(c) {
            this.search = c;
            this.open = false;
            if (this.$parent && this.$parent.form) {
                this.$parent.form.country = c;
            }
        }
    };
}

function contactMethodPicker() {
    return {
        selected: [],
        warning: false,
        methods: [
            { value: 'email', label: 'Email' },
            { value: 'phone', label: 'Phone' },
            { value: 'whatsapp', label: 'WhatsApp' },
            { value: 'video_call', label: 'Video Call' }
        ],
        isSelected(val) { return this.selected.includes(val); },
        toggle(val) {
            if (this.isSelected(val)) {
                this.selected = this.selected.filter(v => v !== val);
                this.warning = false;
            } else if (this.selected.length < 2) {
                this.selected.push(val);
                this.warning = false;
            } else {
                this.warning = true;
            }
            // Update parent form
            if (this.$parent && this.$parent.form) {
                this.$parent.form.contact_methods = [...this.selected];
            }
        }
    };
}

function safariBookingForm() {
    return {
        loading: false,
        submitted: false,
        errors: {},
        form: {
            safari_package_id: '{{ $safari->id }}',
            inquiry_type: 'booking',
            name: '',
            email: '',
            phone: '',
            country_code: '+255',
            country: '',
            travel_date: '',
            number_of_people: '',
            message: '',
            contact_methods: []
        },
        init() {
            this.$nextTick(() => {
                if (this.$refs.bookingDate) {
                    flatpickr(this.$refs.bookingDate, {
                        minDate: 'today',
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'F j, Y',
                        disableMobile: true,
                        onChange: (selectedDates, dateStr) => { this.form.travel_date = dateStr; }
                    });
                }
            });
        },
        async submitForm() {
            this.loading = true;
            this.errors = {};
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                    || document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1];
                const payload = { ...this.form };
                if (payload.phone && payload.country_code) {
                    payload.phone = payload.country_code + ' ' + payload.phone;
                }
                const res = await fetch('{{ route("inquiries.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    this.submitted = true;
                } else if (res.status === 422) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                }
            } catch (e) {
                console.error('Booking submission error:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}

function safariInquiryForm() {
    return {
        loading: false,
        submitted: false,
        errors: {},
        form: {
            safari_package_id: '{{ $safari->id }}',
            inquiry_type: 'inquiry',
            name: '',
            email: '',
            phone: '',
            country_code: '+255',
            country: '',
            travel_date: '',
            number_of_people: '',
            message: '',
            contact_methods: []
        },
        init() {
            this.$nextTick(() => {
                if (this.$refs.inquiryDate) {
                    flatpickr(this.$refs.inquiryDate, {
                        minDate: 'today',
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'F j, Y',
                        disableMobile: true,
                        onChange: (selectedDates, dateStr) => { this.form.travel_date = dateStr; }
                    });
                }
            });
        },
        async submitForm() {
            this.loading = true;
            this.errors = {};
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                    || document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1];
                const payload = { ...this.form };
                if (payload.phone && payload.country_code) {
                    payload.phone = payload.country_code + ' ' + payload.phone;
                }
                const res = await fetch('{{ route("inquiries.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    this.submitted = true;
                } else if (res.status === 422) {
                    const data = await res.json();
                    this.errors = data.errors || {};
                }
            } catch (e) {
                console.error('Inquiry submission error:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
