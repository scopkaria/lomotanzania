{{-- ============================================================
     Safari Package — Form
     Sections: Basic Info · Location · Pricing · Media · Itinerary · Settings
     ============================================================ --}}

@php
    $editing = isset($safari) && $safari->exists;

    $selectedCountryIds = old('countries', $editing ? $safari->countries->pluck('id')->toArray() : []);
    $selectedCountryIds = array_map('intval', $selectedCountryIds);
    $itineraryItems     = old('itinerary', $editing ? $safari->itineraries->toArray() : []);

    $highlightsItems = old('highlights', $editing ? ($safari->highlights ?? []) : []);
    $includedItems   = old('included', $editing ? ($safari->included ?? []) : []);
    $excludedItems   = old('excluded', $editing ? ($safari->excluded ?? []) : []);
    $seasonalPricing = old('seasonal_pricing', $editing ? ($safari->seasonal_pricing ?? []) : []);

    if (is_string($highlightsItems)) {
        $highlightsItems = json_decode($highlightsItems, true) ?: [];
    }

    if (is_string($includedItems)) {
        $includedItems = json_decode($includedItems, true) ?: [];
    }

    if (is_string($excludedItems)) {
        $excludedItems = json_decode($excludedItems, true) ?: [];
    }

    if (is_string($seasonalPricing)) {
        $seasonalPricing = json_decode($seasonalPricing, true) ?: [];
    }

    $highlightsItems = collect($highlightsItems)->filter(fn ($item) => filled($item))->values()->all();
    $includedItems   = collect($includedItems)->filter(fn ($item) => filled($item))->values()->all();
    $excludedItems   = collect($excludedItems)->filter(fn ($item) => filled($item))->values()->all();

    $seasonalPricing = array_replace_recursive([
        'low' => ['pax_2' => '', 'pax_4' => '', 'pax_6' => ''],
        'mid' => ['pax_2' => '', 'pax_4' => '', 'pax_6' => ''],
        'high' => ['pax_2' => '', 'pax_4' => '', 'pax_6' => ''],
    ], is_array($seasonalPricing) ? $seasonalPricing : []);

    $itineraryJson = collect($itineraryItems)->values()->map(fn($item, $i) => [
        'id'             => $i,
        'title'          => $item['title'] ?? '',
        'description'    => $item['description'] ?? '',
        'destination_id' => (string) ($item['destination_id'] ?? ''),
        'accommodation_id' => (string) ($item['accommodation_id'] ?? ''),
        'image_path'     => $item['image_path'] ?? '',
        'open'           => true,
        'showTranslations' => false,
        '_translating'     => false,
        '_translateMsg'    => '',
        '_translateError'  => false,
        '_fieldTranslating' => null,
        'translations'   => [
            'fr' => [
                'title'       => $item['translations']['fr']['title'] ?? ($item['title_translations']['fr'] ?? ''),
                'description' => $item['translations']['fr']['description'] ?? ($item['description_translations']['fr'] ?? ''),
            ],
            'de' => [
                'title'       => $item['translations']['de']['title'] ?? ($item['title_translations']['de'] ?? ''),
                'description' => $item['translations']['de']['description'] ?? ($item['description_translations']['de'] ?? ''),
            ],
            'es' => [
                'title'       => $item['translations']['es']['title'] ?? ($item['title_translations']['es'] ?? ''),
                'description' => $item['translations']['es']['description'] ?? ($item['description_translations']['es'] ?? ''),
            ],
        ],
    ])->all();

    $countriesJson = $countries->map(fn($c) => [
        'id'   => $c->id,
        'name' => $c->name,
    ])->values()->all();

    $destinationsJson = $destinations->map(fn($d) => [
        'id'         => $d->id,
        'name'       => $d->name,
        'country_id' => $d->country_id,
        'latitude'   => $d->latitude,
        'longitude'  => $d->longitude,
    ])->values()->all();

    $accommodationsJson = $accommodations->map(fn($a) => [
        'id' => $a->id,
        'name' => $a->name,
        'destination_id' => $a->destination_id,
        'category' => $a->category,
    ])->values()->all();
@endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
    <style>
        .quill-shell .ql-toolbar.ql-snow,
        .quill-shell .ql-container.ql-snow {
            border-color: rgb(209 213 219);
        }

        .quill-shell .ql-toolbar.ql-snow {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            background: #fff;
        }

        .quill-shell .ql-container.ql-snow {
            min-height: 18rem;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            background: #fff;
            font-size: 0.875rem;
        }
    </style>
@endpush

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <div>
                <p class="text-sm font-semibold text-red-800 mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div x-data="safariForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ══════════════════════════════════════
         LEFT COLUMN
         ══════════════════════════════════════ --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- 1. BASIC INFO --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    Basic Information
                </span>
            </h3>
            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required
                           value="{{ old('title', $safari->title ?? '') }}"
                           placeholder="e.g. The Great Migration Safari"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                    @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                        Slug <span class="text-xs text-gray-400 font-normal">(auto-generated if empty)</span>
                    </label>
                    <input type="text" name="slug" id="slug"
                           value="{{ old('slug', $safari->slug ?? '') }}"
                           placeholder="the-great-migration-safari"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm font-mono">
                    @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                    <textarea name="short_description" id="short_description" rows="2" maxlength="500"
                              placeholder="Brief summary for cards and listings…"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">{{ old('short_description', $safari->short_description ?? '') }}</textarea>
                    @error('short_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="overview_title" class="block text-sm font-medium text-gray-700 mb-1">Overview Heading</label>
                    <input type="text" name="overview_title" id="overview_title"
                           value="{{ old('overview_title', $safari->overview_title ?? 'Experience Overview') }}"
                           placeholder="What to Expect"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                    <p class="mt-1 text-xs text-gray-400">Used as the main heading above the long-form editorial description.</p>
                    @error('overview_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                    <input type="hidden" name="description" id="description" value="{{ old('description', $safari->description ?? '') }}">
                    <div class="quill-shell" data-quill-wrapper>
                        <div id="description_editor"
                             class="rounded-lg"
                             data-initial-html="{{ e(old('description', $safari->description ?? '')) }}"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Safe HTML is allowed and will render on the safari detail page.</p>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- 2. TRANSLATIONS --}}
        @include('admin.partials.translation-tabs', [
            'model' => $safari ?? null,
            'fields' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['name' => 'short_description', 'label' => 'Short Description', 'type' => 'textarea', 'rows' => 2],
                ['name' => 'overview_title', 'label' => 'Overview Heading', 'type' => 'text'],
                ['name' => 'description', 'label' => 'Full Description', 'type' => 'richtext', 'rows' => 6],
            ],
        ])

        {{-- 3. HIGHLIGHTS --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286l-11.54 6.347c-1.25.687-2.779-.216-2.779-1.643V5.653z"/></svg>
                    Highlights
                </span>
            </h3>
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="highlights_title" class="block text-sm font-medium text-gray-700 mb-1">Section Heading</label>
                        <input type="text" name="highlights_title" id="highlights_title"
                               value="{{ old('highlights_title', $safari->highlights_title ?? 'Highlights') }}"
                               placeholder="Highlights"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">
                        @error('highlights_title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="highlights_intro" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="highlights_intro" id="highlights_intro" rows="2" maxlength="500"
                                  placeholder="The standout moments built into this safari."
                                  class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">{{ old('highlights_intro', $safari->highlights_intro ?? 'The standout moments built into this safari.') }}</textarea>
                        @error('highlights_intro') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Highlights List</label>
                <div class="rounded-xl border border-gray-200 bg-gray-50/40 p-4">
                    <div class="flex flex-wrap gap-2 mb-3" x-show="highlights.length > 0">
                        <template x-for="(item, idx) in highlights" :key="item + idx">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#FEBC11]/15 px-3 py-1.5 text-sm font-medium text-[#131414]">
                                <span x-text="item"></span>
                                <button type="button" @click="removeListItem('highlights', idx)" class="text-[#131414]/50 transition hover:text-red-500">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <input type="hidden" name="highlights[]" :value="item">
                            </span>
                        </template>
                    </div>
                    <input type="text"
                           x-model="listInputs.highlights"
                           @keydown.enter.prevent="addListItem('highlights')"
                           placeholder="Type a highlight and press Enter"
                           class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">
                    <p class="mt-2 text-xs text-gray-400">Examples: Big Five safari, Luxury lodges, Serengeti migration</p>
                </div>
                    @error('highlights') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    @error('highlights.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Highlights Translations --}}
        @include('admin.partials.translation-tabs', [
            'model' => $safari ?? null,
            'fields' => [
                ['name' => 'highlights_title', 'label' => 'Highlights Heading', 'type' => 'text'],
                ['name' => 'highlights_intro', 'label' => 'Highlights Description', 'type' => 'textarea', 'rows' => 2],
            ],
        ])

        {{-- 3. LOCATION & CLASSIFICATION --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Location & Classification
                </span>
            </h3>
            <div class="space-y-5">

                {{-- Country Multi-Select Pills --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Countries <span class="text-red-500">*</span></label>

                    {{-- Selected pills --}}
                    <div class="flex flex-wrap gap-2 mb-3" x-show="selectedCountries.length > 0">
                        <template x-for="cid in selectedCountries" :key="cid">
                            <span class="inline-flex items-center gap-1.5 bg-[#FEBC11]/15 text-[#131414] text-sm font-medium px-3 py-1.5 rounded-full">
                                <span x-text="getCountryName(cid)"></span>
                                <button type="button" @click="removeCountry(cid)" class="text-[#131414]/60 hover:text-red-500 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <input type="hidden" name="countries[]" :value="cid">
                            </span>
                        </template>
                    </div>

                    {{-- Search input --}}
                    <div class="relative">
                        <input type="text" x-model="countrySearch" @input="countryDropdownOpen = true" @focus="countryDropdownOpen = true"
                               @keydown.escape="countryDropdownOpen = false"
                               placeholder="Search countries…"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm"
                               autocomplete="off">
                        <div x-show="countryDropdownOpen && filteredCountryOptions.length > 0"
                             x-transition @click.outside="countryDropdownOpen = false"
                             class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                            <ul class="max-h-48 overflow-y-auto">
                                <template x-for="country in filteredCountryOptions" :key="country.id">
                                    <li @click="addCountry(country.id)"
                                        class="px-4 py-3 text-sm hover:bg-[#F9F7F3] cursor-pointer transition flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        <span x-text="country.name"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Countries this safari operates in</p>
                    @error('countries') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Classification grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                        <input type="text" name="duration" id="duration"
                               value="{{ old('duration', $safari->duration ?? '') }}"
                               placeholder="7 Days / 6 Nights"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                        @error('duration') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tour_type_id" class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                        <select name="tour_type_id" id="tour_type_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                            <option value="">Select…</option>
                            @foreach($tourTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('tour_type_id', $safari->tour_type_id ?? '') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('tour_type_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                        <select name="category_id" id="category_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                            <option value="">Select…</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $safari->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="safari_type" class="block text-sm font-medium text-gray-700 mb-1">Safari Type</label>
                        <select name="safari_type" id="safari_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                            <option value="safari" @selected(old('safari_type', $safari->safari_type ?? 'safari') === 'safari')>Safari</option>
                            <option value="trekking" @selected(old('safari_type', $safari->safari_type ?? 'safari') === 'trekking')>Trekking</option>
                        </select>
                        @error('safari_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                        <select name="difficulty" id="difficulty"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                            <option value="">Select…</option>
                            @foreach(['Easy', 'Moderate', 'Challenging', 'Strenuous'] as $diff)
                                <option value="{{ $diff }}" @selected(old('difficulty', $safari->difficulty ?? '') === $diff)>{{ $diff }}</option>
                            @endforeach
                        </select>
                        @error('difficulty') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. PRICING --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pricing
                </span>
            </h3>
            <div class="space-y-6">
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" id="currency" x-model="selectedCurrency"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                        @foreach(['USD' => 'USD — US Dollar', 'EUR' => 'EUR — Euro', 'GBP' => 'GBP — British Pound', 'TZS' => 'TZS — Tanzanian Shilling'] as $code => $label)
                            <option value="{{ $code }}" @selected(old('currency', $safari->currency ?? 'USD') === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('currency') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-400">This global selector applies to every seasonal price shown on the frontend.</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-[#F9F7F3] p-5">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-700">Seasonal Pricing Matrix</h4>
                            <p class="mt-1 text-xs text-gray-500">Configure per-person rates by season and group size for the unified pricing card on the safari detail page.</p>
                        </div>
                        <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-gray-500 shadow-sm">2 / 4 / 6 Pax</span>
                    </div>
                    <div class="space-y-4">
                        @foreach(['low' => 'Low Season', 'mid' => 'Mid Season', 'high' => 'High Season'] as $seasonKey => $seasonLabel)
                            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <h5 class="font-semibold text-[#131414]">{{ $seasonLabel }}</h5>
                                    <span class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Per Person</span>
                                </div>
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                                    @foreach(['pax_2' => '2 Pax', 'pax_4' => '4 Pax', 'pax_6' => '6 Pax'] as $paxKey => $paxLabel)
                                        <div>
                                            <label for="seasonal_pricing_{{ $seasonKey }}_{{ $paxKey }}" class="block text-xs font-medium text-gray-600 mb-1">{{ $paxLabel }}</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" x-text="selectedCurrency"></span>
                                                <input type="number"
                                                       name="seasonal_pricing[{{ $seasonKey }}][{{ $paxKey }}]"
                                                       id="seasonal_pricing_{{ $seasonKey }}_{{ $paxKey }}"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old('seasonal_pricing.' . $seasonKey . '.' . $paxKey, data_get($seasonalPricing, $seasonKey . '.' . $paxKey, '')) }}"
                                                       placeholder="0.00"
                                                       class="w-full rounded-lg border border-gray-300 bg-white py-3 pl-14 pr-4 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">
                                            </div>
                                            @error('seasonal_pricing.' . $seasonKey . '.' . $paxKey) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. MEDIA --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.41a2.25 2.25 0 013.182 0l2.909 2.91m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    Media
                </span>
            </h3>
            <div class="space-y-5">
                <div>
                    @include('admin.media.picker', [
                        'name'  => 'featured_image',
                        'value' => old('featured_image', $safari->featured_image ?? ''),
                        'label' => 'Featured Image',
                    ])
                </div>
                <div>
                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </span>
                        <input type="url" name="video_url" id="video_url"
                               value="{{ old('video_url', $safari->video_url ?? '') }}"
                               placeholder="https://youtube.com/watch?v=…"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm pl-9">
                    </div>
                    @error('video_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-400">Route mapping now uses itinerary destination coordinates and the public Mapbox view automatically.</p>
                </div>
            </div>
        </div>

        {{-- 6. ITINERARY BUILDER --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-5">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        Itinerary
                    </span>
                    <span x-show="days.length > 0" class="ml-2 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-[#FEBC11] rounded-full" x-text="days.length"></span>
                </h3>
                <div class="flex items-center gap-2">
                    <button x-show="days.length > 1" type="button" @click="expandAllDays()"
                            class="text-gray-400 hover:text-[#FEBC11] transition p-1.5 rounded-lg hover:bg-gray-50" title="Expand all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                    </button>
                    <button x-show="days.length > 1" type="button" @click="collapseAllDays()"
                            class="text-gray-400 hover:text-[#FEBC11] transition p-1.5 rounded-lg hover:bg-gray-50" title="Collapse all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                    </button>
                    <div class="w-px h-5 bg-gray-200" x-show="days.length > 0"></div>
                    <button type="button" @click="addDay()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#FEBC11]/10 text-[#131414] text-xs font-semibold rounded-lg hover:bg-[#FEBC11]/20 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Add Day
                    </button>
                </div>
            </div>

            <div class="space-y-3">
                <template x-for="(day, idx) in days" :key="day.id">
                    <div class="border rounded-xl overflow-hidden transition-all duration-200"
                         :class="day.open ? 'border-[#FEBC11]/40 shadow-sm' : 'border-gray-200 hover:border-gray-300'">

                        {{-- Accordion Header --}}
                        <div class="flex items-center gap-3 px-4 py-3 cursor-pointer select-none"
                             :class="day.open ? 'bg-[#FEBC11]/5' : 'bg-gray-50/50 hover:bg-gray-50'"
                             @click="day.open = !day.open">
                            <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                                 :class="day.open && 'rotate-90'"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[#131414] bg-[#FEBC11]/15 px-2.5 py-1 rounded-full shrink-0">
                                Day <span x-text="idx + 1"></span>
                            </span>
                            <span class="text-sm text-gray-600 truncate min-w-0 flex-1"
                                  x-text="day.title || 'Untitled day…'" :class="!day.title && 'italic text-gray-400'"></span>
                            <div class="flex items-center gap-0.5 shrink-0" @click.stop>
                                <button type="button" @click="moveDay(idx, -1)" :disabled="idx === 0"
                                        class="p-1.5 rounded-lg transition"
                                        :class="idx === 0 ? 'text-gray-200 cursor-not-allowed' : 'text-gray-400 hover:text-[#131414] hover:bg-gray-100'" title="Move up">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                                </button>
                                <button type="button" @click="moveDay(idx, 1)" :disabled="idx === days.length - 1"
                                        class="p-1.5 rounded-lg transition"
                                        :class="idx === days.length - 1 ? 'text-gray-200 cursor-not-allowed' : 'text-gray-400 hover:text-[#131414] hover:bg-gray-100'" title="Move down">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                </button>
                                <div class="w-px h-4 bg-gray-200 mx-1"></div>
                                <button type="button" @click="removeDay(idx)"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition" title="Remove day">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Accordion Body --}}
                        <div x-show="day.open" x-collapse>
                            <div class="px-4 pb-4 pt-2 border-t border-gray-100 space-y-3">
                                <input type="hidden" :name="'itinerary[' + idx + '][day_number]'" :value="idx + 1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Title <span class="text-red-400">*</span></label>
                                        <input type="text" :name="'itinerary[' + idx + '][title]'" x-model="day.title"
                                               placeholder="e.g. Arrival & Arusha City Tour"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Destination</label>
                                        <select :name="'itinerary[' + idx + '][destination_id]'"
                                                x-model="day.destination_id"
                                                @change="syncAccommodation(day)"
                                                x-html="destinationOptionsHtml(day.destination_id)"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                        <textarea :name="'itinerary[' + idx + '][description]'" x-model="day.description" rows="3"
                                                  placeholder="Activities, sightings, and experiences…"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Accommodation</label>
                                        <select :name="'itinerary[' + idx + '][accommodation_id]'"
                                                x-model="day.accommodation_id"
                                                x-html="accommodationOptionsHtml(day.destination_id, day.accommodation_id)"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                                        </select>
                                        <p class="mt-1 text-[11px] text-gray-400">Select destination first to filter accommodations.</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Day Image</label>
                                    <div x-data="mediaPicker(day.image_path || '')" class="space-y-2">
                                        <div x-show="currentImage" x-transition class="relative inline-block group">
                                            <img :src="'/storage/' + currentImage"
                                                 class="h-20 w-28 rounded-lg object-cover border border-gray-200 shadow-sm" alt="Day image">
                                            <button type="button" @click="removeImage()"
                                                    class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] shadow hover:bg-red-600 transition opacity-0 group-hover:opacity-100">&times;</button>
                                        </div>
                                        <input type="hidden" :name="'itinerary[' + idx + '][image_path]'" :value="currentImage">
                                        <div class="flex items-center gap-2">
                                            <button type="button" @click="openLibrary()"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 border border-gray-300 text-[11px] font-semibold text-gray-700 rounded-lg hover:bg-gray-50 hover:border-[#FEBC11] transition">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                                Select
                                            </button>
                                            <button type="button" @click="$refs.fileUpload.click()"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-[#FEBC11]/10 text-[11px] font-semibold text-[#131414] rounded-lg hover:bg-[#FEBC11]/20 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                                Upload
                                            </button>
                                            <input type="file" x-ref="fileUpload" @change="uploadFile($event)" class="hidden" accept="image/*">
                                            <span x-show="uploading" class="text-[11px] text-gray-400">Uploading…</span>
                                        </div>
                                        {{-- Inline library modal --}}
                                        <div x-show="libraryOpen" x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4" style="display:none;">
                                            <div @click.outside="libraryOpen = false" class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">
                                                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                                                    <h3 class="font-bold text-gray-900 text-base">Media Library</h3>
                                                    <button type="button" @click="libraryOpen = false" class="p-1 hover:bg-gray-100 rounded-lg transition"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                </div>
                                                <div class="px-5 py-3 border-b border-gray-100 shrink-0">
                                                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchMedia()" placeholder="Search images…" class="w-full px-4 py-2 text-sm rounded-lg border border-gray-200 focus:ring-2 focus:ring-[#FEBC11]/50 focus:border-[#FEBC11]">
                                                </div>
                                                <div class="flex-1 overflow-y-auto p-5">
                                                    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                                                        <template x-for="item in libraryItems" :key="item.id">
                                                            <button type="button" @click="selectItem(item)" class="aspect-square rounded-lg overflow-hidden border-2 transition hover:shadow-md" :class="selectedId === item.id ? 'border-[#FEBC11] ring-2 ring-[#FEBC11]/30' : 'border-transparent hover:border-gray-200'">
                                                                <img :src="'/storage/' + item.path" class="w-full h-full object-cover">
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="px-5 py-3 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                                                    <button type="button" @click="libraryOpen = false" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                                                    <button type="button" @click="confirmSelection()" :disabled="!selectedId" class="px-4 py-2 bg-[#FEBC11] text-[#131414] text-sm font-bold rounded-lg hover:bg-yellow-400 transition disabled:opacity-40 disabled:cursor-not-allowed">Use Image</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Per-day Translations --}}
                                <div class="border-t border-gray-100 pt-3 mt-3">
                                    <div class="flex items-center justify-between">
                                        <button type="button" @click="day.showTranslations = !day.showTranslations"
                                                class="flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-gray-700 transition">
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="day.showTranslations && 'rotate-90'"
                                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                            <svg class="w-3.5 h-3.5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
                                            Translations (FR / DE / ES)
                                        </button>
                                        <div x-show="day.showTranslations" class="flex items-center gap-2">
                                            {{-- Auto-Translate All Languages --}}
                                            <button type="button"
                                                @click="autoTranslateDay(idx)"
                                                :disabled="day._translating"
                                                class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 transition hover:bg-blue-100 disabled:opacity-50 disabled:cursor-wait">
                                                <template x-if="!day._translating">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
                                                </template>
                                                <template x-if="day._translating">
                                                    <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                </template>
                                                <span x-text="day._translating ? 'Translating…' : 'Auto-Translate All'"></span>
                                            </button>
                                            {{-- Copy from English --}}
                                            <button type="button"
                                                @click="copyDayFromEnglish(idx)"
                                                class="inline-flex items-center gap-1 rounded-md bg-[#FEBC11]/10 px-2.5 py-1 text-[11px] font-semibold text-[#8b6d00] transition hover:bg-[#FEBC11]/20">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                                                Copy EN
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Status toast per day --}}
                                    <div x-show="day._translateMsg" x-transition.opacity class="mt-2"
                                         :class="day._translateError ? 'bg-red-50 text-red-700 border-red-200' : 'bg-green-50 text-green-700 border-green-200'"
                                         class="px-3 py-1.5 rounded-lg border text-[11px] font-medium">
                                        <span x-text="day._translateMsg"></span>
                                    </div>

                                    <div x-show="day.showTranslations" x-collapse class="mt-3 space-y-3">
                                        <template x-for="lang in [{code:'fr',flag:'🇫🇷',name:'French'},{code:'de',flag:'🇩🇪',name:'German'},{code:'es',flag:'🇪🇸',name:'Spanish'}]" :key="lang.code">
                                            <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-[11px] font-semibold text-gray-500" x-text="lang.flag + ' ' + lang.name"></p>
                                                    <button type="button"
                                                        @click="autoTranslateDayLang(idx, lang.code)"
                                                        :disabled="day._fieldTranslating === lang.code"
                                                        class="inline-flex items-center gap-1 text-[10px] font-medium text-blue-600 hover:text-blue-800 transition disabled:opacity-50 disabled:cursor-wait">
                                                        <template x-if="day._fieldTranslating === lang.code">
                                                            <svg class="w-2.5 h-2.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                        </template>
                                                        <template x-if="day._fieldTranslating !== lang.code">
                                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
                                                        </template>
                                                        Translate
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-500 mb-1">Title</label>
                                                        <input type="text" :name="'itinerary['+idx+'][translations]['+lang.code+'][title]'"
                                                               x-model="day.translations[lang.code].title"
                                                               class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white focus:ring-1 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                                                               :placeholder="'Title in ' + lang.name">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-500 mb-1">Description</label>
                                                        <textarea :name="'itinerary['+idx+'][translations]['+lang.code+'][description]'"
                                                                  x-model="day.translations[lang.code].description"
                                                                  rows="2"
                                                                  class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg bg-white focus:ring-1 focus:ring-[#FEBC11] focus:border-[#FEBC11]"
                                                                  :placeholder="'Description in ' + lang.name"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="days.length === 0" class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <p class="text-sm font-medium text-gray-500 mb-1">No itinerary days yet</p>
                    <p class="text-xs text-gray-400 mb-3">Start building this safari's day-by-day schedule</p>
                    <button type="button" @click="addDay()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#FEBC11]/10 text-[#131414] text-sm font-semibold rounded-lg hover:bg-[#FEBC11]/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Add your first day
                    </button>
                </div>
            </div>
        </div>

        {{-- 7. INCLUSIONS & EXCLUSIONS --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m5.25 2.25a8.25 8.25 0 1 1-16.5 0 8.25 8.25 0 0 1 16.5 0Z"/></svg>
                    Inclusions & Exclusions
                </span>
            </h3>
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="inclusions_title" class="block text-sm font-medium text-gray-700 mb-1">Section Heading</label>
                        <input type="text" name="inclusions_title" id="inclusions_title"
                               value="{{ old('inclusions_title', $safari->inclusions_title ?? 'Inclusions & Exclusions') }}"
                               placeholder="Inclusions & Exclusions"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">
                        @error('inclusions_title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="inclusions_intro" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="inclusions_intro" id="inclusions_intro" rows="2" maxlength="500"
                                  placeholder="A clear view of what is covered and what to plan for separately."
                                  class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">{{ old('inclusions_intro', $safari->inclusions_intro ?? 'A clear view of what is covered and what to plan for separately.') }}</textarea>
                        @error('inclusions_intro') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Included</label>
                    <div class="rounded-xl border border-gray-200 bg-gray-50/40 p-4">
                        <div class="flex flex-wrap gap-2 mb-3" x-show="included.length > 0">
                            <template x-for="(item, idx) in included" :key="item + idx">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-green/10 px-3 py-1.5 text-sm font-medium text-brand-green">
                                    <span x-text="item"></span>
                                    <button type="button" @click="removeListItem('included', idx)" class="text-brand-green/50 transition hover:text-red-500">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <input type="hidden" name="included[]" :value="item">
                                </span>
                            </template>
                        </div>
                        <input type="text"
                               x-model="listInputs.included"
                               @keydown.enter.prevent="addListItem('included')"
                               placeholder="Type an inclusion and press Enter"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">
                    </div>
                    @error('included') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    @error('included.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excluded</label>
                    <div class="rounded-xl border border-gray-200 bg-gray-50/40 p-4">
                        <div class="flex flex-wrap gap-2 mb-3" x-show="excluded.length > 0">
                            <template x-for="(item, idx) in excluded" :key="item + idx">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700">
                                    <span x-text="item"></span>
                                    <button type="button" @click="removeListItem('excluded', idx)" class="text-red-700/50 transition hover:text-red-500">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <input type="hidden" name="excluded[]" :value="item">
                                </span>
                            </template>
                        </div>
                        <input type="text"
                               x-model="listInputs.excluded"
                               @keydown.enter.prevent="addListItem('excluded')"
                               placeholder="Type an exclusion and press Enter"
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#FEBC11] focus:ring-2 focus:ring-[#FEBC11]">
                    </div>
                    @error('excluded') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    @error('excluded.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                </div>
            </div>
        </div>

        {{-- Inclusions Translations --}}
        @include('admin.partials.translation-tabs', [
            'model' => $safari ?? null,
            'fields' => [
                ['name' => 'inclusions_title', 'label' => 'Inclusions Heading', 'type' => 'text'],
                ['name' => 'inclusions_intro', 'label' => 'Inclusions Description', 'type' => 'textarea', 'rows' => 2],
            ],
        ])

    </div>

    {{-- ══════════════════════════════════════
         RIGHT COLUMN — Settings
         ══════════════════════════════════════ --}}
    <div class="space-y-6">

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </span>
            </h3>
            <div class="space-y-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                        <option value="draft" @selected(old('status', $safari->status ?? 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $safari->status ?? '') === 'published')>Published</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-2.5 p-3 rounded-lg border border-gray-200 hover:border-[#FEBC11]/30 cursor-pointer transition">
                    <input type="hidden" name="featured" value="0">
                    <input type="checkbox" name="featured" value="1"
                           @checked(old('featured', $safari->featured ?? false))
                           class="w-4 h-4 rounded border-gray-300 text-[#FEBC11] focus:ring-[#FEBC11]">
                    <div>
                        <span class="text-sm font-medium text-gray-700 block">Featured</span>
                        <span class="text-xs text-gray-400">Show on homepage</span>
                    </div>
                </label>
            </div>

            {{-- Featured Order & Label (shown when featured) --}}
            <div x-show="$el.closest('.space-y-4') && document.querySelector('[name=featured]:checked')"
                 x-data="{ shown: {{ old('featured', $safari->featured ?? false) ? 'true' : 'false' }} }"
                 x-init="$watch(() => document.querySelector('input[name=featured][type=checkbox]').checked, v => shown = v)"
                 x-show="shown"
                 x-collapse
                 class="space-y-3 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Sort Order</label>
                    <input type="number" name="featured_order" min="0"
                           value="{{ old('featured_order', $safari->featured_order ?? 0) }}"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-[#FEBC11] focus:ring-[#FEBC11]"
                           placeholder="0 = first">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Hero Label</label>
                    <input type="text" name="featured_label"
                           value="{{ old('featured_label', $safari->featured_label ?? '') }}"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-[#FEBC11] focus:ring-[#FEBC11]"
                           placeholder="e.g. Best Seller, Most Popular">
                    <p class="text-[10px] text-gray-400 mt-1">Shown above the title in the hero slider</p>
                </div>
            </div>
        </div>

        {{-- Destination Map Preview --}}
        <div class="bg-white rounded-xl shadow-sm p-6" x-show="selectedCountries.length > 0">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">
                Destinations
            </h3>
            <div class="space-y-2 max-h-48 overflow-y-auto">
                <template x-for="dest in filteredDestinations" :key="dest.id">
                    <div class="flex items-center justify-between text-sm py-1.5">
                        <span class="text-gray-700" x-text="dest.name"></span>
                        <span class="text-xs text-gray-400" x-show="dest.latitude"
                              x-text="dest.latitude?.toFixed(2) + ', ' + dest.longitude?.toFixed(2)"></span>
                    </div>
                </template>
                <p x-show="filteredDestinations.length === 0" class="text-xs text-gray-400 italic">No destinations for selected countries</p>
            </div>
        </div>

        @if($editing)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4">Summary</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Linked Accommodations</dt>
                        <dd class="font-medium text-gray-900">{{ $safari->itineraries->pluck('accommodation_id')->filter()->unique()->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Itinerary Days</dt>
                        <dd class="font-medium text-gray-900">{{ $safari->itineraries->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Countries</dt>
                        <dd class="font-medium text-gray-900">{{ $safari->countries->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Created</dt>
                        <dd class="font-medium text-gray-900">{{ $safari->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        @endif

    </div>
</div>

{{-- SEO Settings --}}
<div class="max-w-4xl mt-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        @include('admin.partials.seo-panel', ['model' => $safari ?? null])
    </div>
</div>

<script>
const MAPBOX_TOKEN = @json(config('services.mapbox.token'));

document.addEventListener('alpine:init', () => {
    Alpine.data('safariForm', () => ({
        // Country multi-select
        allCountries: @json($countriesJson),
        selectedCountries: @json($selectedCountryIds),
        countrySearch: '',
        countryDropdownOpen: false,

        get filteredCountryOptions() {
            const q = this.countrySearch.toLowerCase().trim();
            return this.allCountries.filter(c =>
                !this.selectedCountries.includes(c.id) &&
                (!q || c.name.toLowerCase().includes(q))
            );
        },

        getCountryName(id) {
            const c = this.allCountries.find(c => c.id === id);
            return c ? c.name : '';
        },

        addCountry(id) {
            if (!this.selectedCountries.includes(id)) {
                this.selectedCountries.push(id);
            }
            this.countrySearch = '';
            this.countryDropdownOpen = false;
            this.refreshDestinations();
        },

        removeCountry(id) {
            this.selectedCountries = this.selectedCountries.filter(c => c !== id);
            this.refreshDestinations();
        },

        // Destinations filtered by selected countries
        allDestinations: @json($destinationsJson),
        allAccommodations: @json($accommodationsJson),
        selectedCurrency: @json(old('currency', $safari->currency ?? 'USD')),

        get filteredDestinations() {
            if (this.selectedCountries.length === 0) return [];
            return this.allDestinations.filter(d => this.selectedCountries.includes(d.country_id));
        },

        destinationOptionsHtml(selectedVal) {
            let html = '<option value="">— Select —</option>';
            for (const dest of this.filteredDestinations) {
                const val = String(dest.id);
                const sel = val === String(selectedVal) ? ' selected' : '';
                const name = dest.name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                html += `<option value="${val}"${sel}>${name}</option>`;
            }
            return html;
        },

        accommodationOptionsHtml(destinationId, selectedVal) {
            let html = '<option value="">— Select —</option>';
            if (!destinationId) {
                return html;
            }

            const matches = this.allAccommodations.filter(accommodation => String(accommodation.destination_id) === String(destinationId));
            for (const accommodation of matches) {
                const val = String(accommodation.id);
                const sel = val === String(selectedVal) ? ' selected' : '';
                const name = (accommodation.name + ' · ' + accommodation.category).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                html += `<option value="${val}"${sel}>${name}</option>`;
            }
            return html;
        },

        syncAccommodation(day) {
            const matches = this.allAccommodations.filter(accommodation => String(accommodation.destination_id) === String(day.destination_id));
            if (!matches.some(accommodation => String(accommodation.id) === String(day.accommodation_id))) {
                day.accommodation_id = '';
            }
        },

        async refreshDestinations() {
            // Fetch destinations for all selected countries
            if (this.selectedCountries.length === 0) return;

            try {
                const promises = this.selectedCountries.map(cid => {
                    const url = new URL("{{ route('admin.api.destinations') }}", window.location.origin);
                    url.searchParams.set('country_id', cid);
                    return fetch(url, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(r => r.json());
                });
                const results = await Promise.all(promises);
                const newDests = results.flat();
                // Merge without duplicates
                const existingIds = new Set(this.allDestinations.map(d => d.id));
                for (const d of newDests) {
                    if (!existingIds.has(d.id)) {
                        this.allDestinations.push(d);
                    }
                }
            } catch (e) {
                console.error('[Safari] Failed to refresh destinations:', e);
            }
        },

        // Itinerary
        days: @json($itineraryJson),
        nextId: {{ count($itineraryItems) }},
        highlights: @json($highlightsItems),
        included: @json($includedItems),
        excluded: @json($excludedItems),
        listInputs: {
            highlights: '',
            included: '',
            excluded: '',
        },

        init() {
            // After Alpine renders options via x-html, re-apply x-model values
            this.$nextTick(() => {
                const saved = this.days.map(d => d.destination_id);
                const accommodationIds = this.days.map(d => d.accommodation_id);
                this.days = this.days.map((d, i) => ({ ...d, destination_id: saved[i] || '', accommodation_id: accommodationIds[i] || '' }));
            });
        },

        addDay() {
            this.days.forEach(d => d.open = false);
            this.days.push({
                id: this.nextId++,
                title: '',
                description: '',
                destination_id: '',
                accommodation_id: '',
                image_path: '',
                open: true,
                showTranslations: false,
                _translating: false,
                _translateMsg: '',
                _translateError: false,
                _fieldTranslating: null,
                translations: {
                    fr: { title: '', description: '' },
                    de: { title: '', description: '' },
                    es: { title: '', description: '' },
                },
            });
        },

        removeDay(idx) { this.days.splice(idx, 1); },

        moveDay(idx, direction) {
            const target = idx + direction;
            if (target < 0 || target >= this.days.length) return;
            const temp = this.days[idx];
            this.days[idx] = this.days[target];
            this.days[target] = temp;
            this.days = [...this.days];
        },

        addListItem(listName) {
            const value = (this.listInputs[listName] || '').trim();
            if (!value) return;
            this[listName].push(value);
            this.listInputs[listName] = '';
        },

        removeListItem(listName, idx) {
            this[listName].splice(idx, 1);
        },

        expandAllDays() { this.days.forEach(d => d.open = true); },
        collapseAllDays() { this.days.forEach(d => d.open = false); },

        // --- Itinerary translation helpers ---
        async _translateText(text, targetLang) {
            if (!text || !text.trim()) return '';
            const encoded = encodeURIComponent(text.substring(0, 4500));
            const url = `https://api.mymemory.translated.net/get?q=${encoded}&langpair=en|${targetLang}`;
            const resp = await fetch(url);
            const data = await resp.json();
            if (data.responseStatus === 200 && data.responseData?.translatedText) {
                let result = data.responseData.translatedText;
                if (result === result.toUpperCase() && text !== text.toUpperCase()) {
                    result = result.charAt(0).toUpperCase() + result.slice(1).toLowerCase();
                }
                return result;
            }
            throw new Error(data.responseData?.translatedText || 'Translation failed');
        },

        async autoTranslateDayLang(dayIdx, langCode) {
            const day = this.days[dayIdx];
            if (!day) return;
            day._fieldTranslating = langCode;
            day._translateMsg = '';
            day._translateError = false;
            try {
                const fields = ['title', 'description'];
                for (const field of fields) {
                    const src = day[field];
                    if (src && src.trim()) {
                        day.translations[langCode][field] = await this._translateText(src, langCode);
                        await new Promise(r => setTimeout(r, 300));
                    }
                }
                day._translateMsg = langCode.toUpperCase() + ' translated successfully!';
                day._translateError = false;
            } catch (e) {
                day._translateMsg = 'Translation error: ' + e.message;
                day._translateError = true;
            } finally {
                day._fieldTranslating = null;
                setTimeout(() => { day._translateMsg = ''; }, 4000);
            }
        },

        async autoTranslateDay(dayIdx) {
            const day = this.days[dayIdx];
            if (!day) return;
            day._translating = true;
            day._translateMsg = '';
            day._translateError = false;
            try {
                for (const langCode of ['fr', 'de', 'es']) {
                    for (const field of ['title', 'description']) {
                        const src = day[field];
                        if (src && src.trim()) {
                            day.translations[langCode][field] = await this._translateText(src, langCode);
                            await new Promise(r => setTimeout(r, 300));
                        }
                    }
                }
                day._translateMsg = 'All languages translated!';
                day._translateError = false;
            } catch (e) {
                day._translateMsg = 'Translation error: ' + e.message;
                day._translateError = true;
            } finally {
                day._translating = false;
                setTimeout(() => { day._translateMsg = ''; }, 4000);
            }
        },

        copyDayFromEnglish(dayIdx) {
            const day = this.days[dayIdx];
            if (!day) return;
            for (const langCode of ['fr', 'de', 'es']) {
                day.translations[langCode].title = day.title || '';
                day.translations[langCode].description = day.description || '';
            }
            day._translateMsg = 'English content copied to all languages.';
            day._translateError = false;
            setTimeout(() => { day._translateMsg = ''; }, 3000);
        },
    }));
});
</script>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editorElement = document.getElementById('description_editor');
            const hiddenInput = document.getElementById('description');

            if (!editorElement || !hiddenInput || typeof Quill === 'undefined') {
                return;
            }

            const quill = new Quill(editorElement, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [3, 4, false] }],
                        ['bold', 'italic', 'underline', 'blockquote'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link'],
                        ['clean'],
                    ],
                },
                placeholder: 'Craft the long-form editorial overview for this safari...',
            });

            const initialHtml = editorElement.dataset.initialHtml || '';
            quill.root.innerHTML = initialHtml || '<p><br></p>';

            const syncEditor = () => {
                hiddenInput.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
            };

            quill.on('text-change', syncEditor);
            syncEditor();
        });
    </script>
@endpush
