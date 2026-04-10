@extends('layouts.app')

@section('title', __('messages.safari_listing') . ' - ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
<style>
    /* Filter sidebar sticky */
    .filter-sidebar {
        position: sticky;
        top: 104px;
    }

    /* Filter pill colors */
    .pill-country  { background: #E8F5E9; color: #1B5E20; }
    .pill-style    { background: #FFF8E1; color: #6D4C00; }
    .pill-interest { background: #F5F5F5; color: #424242; }
    .pill-duration { background: #E3F2FD; color: #0D47A1; }
    .pill-price    { background: #FFF3E0; color: #BF360C; }
    .pill-month    { background: #F3E5F5; color: #4A148C; }

    /* Mobile drawer */
    .filter-drawer {
        transform: translateX(-100%);
        transition: transform 380ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .filter-drawer.open { transform: translateX(0); }
    .drawer-backdrop { opacity: 0; transition: opacity 300ms ease; pointer-events: none; }
    .drawer-backdrop.open { opacity: 1; pointer-events: auto; }

    /* Loading skeleton */
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 6px;
    }

    /* Checkbox styling */
    .filter-check { accent-color: #083321; }

    /* Card animation + line clamp utility */
    .safari-card {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 560ms cubic-bezier(0.16, 1, 0.3, 1), transform 560ms cubic-bezier(0.16, 1, 0.3, 1), box-shadow 220ms ease;
    }
    .safari-card.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; }
    .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .line-clamp-3 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; }
</style>
@endpush

@section('content')

<div x-data="safariExplorer()" x-init="init()" x-cloak>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- HERO HEADER --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="bg-brand-dark py-14 md:py-20 relative overflow-hidden">
        @php $indexHero = \App\Models\IndexHeroImage::forSection('safaris'); @endphp
        <div class="absolute inset-0 opacity-15">
            <img src="{{ $indexHero->image_url ?? 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=500&fit=crop&q=60' }}"
                 alt="" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/50 to-brand-dark/90"></div>
        <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
            <p class="text-kicker tracking-kicker uppercase text-brand-gold mb-3">{{ __('messages.safari_listing_kicker') ?: 'Curated Experiences' }}</p>
            <h1 class="font-heading text-3xl md:text-5xl font-bold uppercase tracking-heading text-white leading-tight mb-6">Find Your Perfect Safari</h1>

            {{-- Search bar --}}
            <div class="max-w-lg mx-auto relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-brand-dark/30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text"
                       x-model.debounce.400ms="search"
                       @input="fetchSafaris()"
                       placeholder="Where do you want to go?"
                       class="w-full pl-12 pr-4 py-3.5 rounded-lg bg-white/95 backdrop-blur text-sm text-brand-dark placeholder-brand-dark/40 border-0 focus:ring-2 focus:ring-brand-gold shadow-lg">
            </div>
        </div>
    </section>

    @if(isset($sections) && $sections->count())
        @include('partials.render-page-sections', ['sections' => $sections, 'sectionDataMap' => $sectionDataMap ?? []])
    @endif

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <section class="bg-brand-light min-h-screen">
        <div class="max-w-[1340px] mx-auto px-4 md:px-6 py-8 md:py-12">

            {{-- Mobile filter toggle --}}
            <div class="lg:hidden mb-4">
                <button @click="drawerOpen = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm font-medium text-brand-dark shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filters
                    <span x-show="activeFilterCount > 0" x-text="activeFilterCount"
                          class="bg-brand-green text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center"></span>
                </button>
            </div>

            <div class="flex gap-8">

                {{-- ═══════════════════════════════════════ --}}
                {{-- LEFT SIDEBAR (Desktop) --}}
                {{-- ═══════════════════════════════════════ --}}
                <aside class="hidden lg:block w-[280px] flex-shrink-0">
                    <div class="filter-sidebar bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="font-heading text-lg font-bold text-brand-dark">Filters</h2>
                            <button x-show="activeFilterCount > 0" @click="clearAll()"
                                    class="text-xs text-brand-dark/90 hover:text-brand-dark transition underline underline-offset-2">
                                Clear all
                            </button>
                        </div>

                        @include('safaris._filters')
                    </div>
                </aside>

                {{-- ═══════════════════════════════════════ --}}
                {{-- RIGHT CONTENT --}}
                {{-- ═══════════════════════════════════════ --}}
                <div class="flex-1 min-w-0">

                    {{-- Results info + active pills --}}
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                            <p class="text-sm text-brand-dark/80">
                                Showing <span class="font-semibold text-brand-dark" x-text="totalCount"></span> results
                                <template x-if="search.length > 0">
                                    <span> for "<span class="font-medium text-brand-dark" x-text="search"></span>"</span>
                                </template>
                            </p>
                        </div>

                        {{-- Active filter pills --}}
                        <div x-show="activeFilterCount > 0" class="flex flex-wrap gap-2">
                            <template x-for="pill in activePills" :key="pill.key">
                                <span :class="pill.cls"
                                      class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-md cursor-pointer hover:opacity-80 transition"
                                      @click="removePill(pill)">
                                    <span x-text="pill.label"></span>
                                    <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </span>
                            </template>
                        </div>
                    </div>

                    {{-- Safari grid --}}
                    <div class="relative">
                        {{-- Loading overlay --}}
                        <div x-show="loading" x-transition.opacity class="absolute inset-0 bg-brand-light/60 z-10 flex items-start justify-center pt-20">
                            <div class="flex items-center gap-2 text-sm text-brand-dark/80">
                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Loading...
                            </div>
                        </div>

                        <div id="safari-grid"
                             class="grid grid-cols-2 gap-4 md:gap-6 lg:grid-cols-3">
                            @include('safaris._cards', ['safaris' => $safaris])
                        </div>

                        {{-- Infinite scroll sentinel --}}
                        <div x-ref="sentinel" class="h-4 mt-6"></div>
                        <div x-show="loadingMore" class="flex items-center justify-center gap-2 py-6 text-sm text-brand-dark/60">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Loading more safaris...
                        </div>
                        <div x-show="!nextPageUrl && totalCount > 0 && !loading" class="text-center py-6">
                            <p class="text-sm text-brand-dark/40">You've seen all safaris</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- MOBILE FILTER DRAWER --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="lg:hidden fixed inset-0 z-50" x-show="drawerOpen" x-cloak>
        <div class="drawer-backdrop fixed inset-0 bg-black/40"
             :class="drawerOpen && 'open'"
             @click="drawerOpen = false"></div>
        <div class="filter-drawer fixed inset-y-0 left-0 w-[320px] max-w-[85vw] bg-white shadow-2xl flex flex-col"
             :class="drawerOpen && 'open'">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="font-heading text-lg font-bold text-brand-dark">Filters</h2>
                <button @click="drawerOpen = false" class="p-1 hover:bg-gray-100 rounded">
                    <svg class="w-5 h-5 text-brand-dark/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-5">
                @include('safaris._filters')
            </div>
            <div class="px-5 py-4 border-t border-gray-100 flex gap-3">
                <button @click="clearAll(); drawerOpen = false"
                        class="flex-1 py-2.5 text-sm font-medium text-brand-dark/80 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    Clear
                </button>
                <button @click="drawerOpen = false"
                        class="flex-1 py-2.5 text-sm font-semibold text-white bg-brand-green rounded-lg hover:bg-brand-green/90 transition">
                    Show Results
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ADDED: Reviews Section --}}
@if(($testimonials ?? collect())->isNotEmpty())
<section class="py-16 md:py-24 bg-[#F9F7F3]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12 scroll-reveal">
            <p class="font-accent text-2xl md:text-3xl text-brand-gold mb-2">Testimonials</p>
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-tight">What Our Guests Say</h2>
            <p class="mt-3 text-base text-brand-dark/50 max-w-lg mx-auto">Real stories from travellers who experienced the magic of Tanzania with us.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
            <div class="scroll-reveal bg-white rounded-2xl p-7 shadow-sm hover:shadow-md transition-shadow duration-300">
                @if($testimonial->rating)
                <div class="flex items-center gap-1 mb-4">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-4 h-4 {{ $s <= $testimonial->rating ? 'text-brand-gold' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                @endif
                <svg class="w-7 h-7 text-brand-dark/8 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>
                <p class="text-sm text-brand-dark/70 leading-relaxed mb-5">{{ Str::limit($testimonial->message, 200) }}</p>
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <div class="w-9 h-9 rounded-full bg-brand-green/10 flex items-center justify-center text-brand-green font-bold text-sm">
                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-brand-dark">{{ $testimonial->name }}</p>
                        @if($testimonial->safariPackage)
                        <p class="text-xs text-brand-dark/40">{{ Str::limit($testimonial->safariPackage->translated('title'), 40) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ADDED CTA: Start Planning Your Safari --}}
<section class="relative py-20 md:py-28 bg-brand-dark overflow-hidden">
    <div class="absolute inset-0 opacity-15">
        <img src="https://images.unsplash.com/photo-1547970810-dc1eac37d174?w=1600&h=600&fit=crop&q=60" alt="" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/70 to-brand-dark/95"></div>
    <div class="relative z-10 max-w-2xl mx-auto px-6 text-center scroll-reveal">
        <p class="text-kicker tracking-kicker uppercase text-brand-gold mb-3">Let's get started</p>
        <h2 class="font-heading text-3xl md:text-5xl font-bold text-white leading-tight mb-6">Start Planning Your Safari</h2>
        <p class="text-white/70 text-base md:text-lg mb-10 max-w-lg mx-auto">Whether it's your first or fiftieth adventure — our team is ready to make it unforgettable.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @if(optional($siteSetting ?? null)->whatsapp_number)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSetting->whatsapp_number) }}" target="_blank" rel="noopener"
               class="px-8 py-3.5 bg-green-600 text-white text-sm font-bold uppercase tracking-wider hover:bg-green-500 transition-all duration-300 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                WhatsApp Us
            </a>
            @endif
            <a href="{{ route('plan-safari', ['locale' => app()->getLocale()]) }}"
               class="px-8 py-3.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider hover:bg-white transition-all duration-300 inline-flex items-center gap-2">
                Inquiry Form
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('safariExplorer', () => ({
        // State
        search: '{{ request("search", "") }}',
        selectedCountries: @json(request('countries') ? (is_array(request('countries')) ? request('countries') : explode(',', request('countries'))) : []),
        selectedTourTypes: @json(request('tour_types') ? (is_array(request('tour_types')) ? request('tour_types') : explode(',', request('tour_types'))) : []),
        selectedCategories: @json(request('categories') ? (is_array(request('categories')) ? request('categories') : explode(',', request('categories'))) : []),
        selectedDurations: @json(request('duration') ? (is_array(request('duration')) ? request('duration') : explode(',', request('duration'))) : []),
        selectedPrices: @json(request('price') ? (is_array(request('price')) ? request('price') : explode(',', request('price'))) : []),
        selectedMonths: [],

        loading: false,
        loadingMore: false,
        drawerOpen: false,
        openFilter: 'country',
        totalCount: {{ $safaris->total() }},
        nextPageUrl: @json($safaris->nextPageUrl()),

        // Lookup maps for labels
        countryMap: @json($countries->pluck('name', 'slug')),
        tourTypeMap: @json($tourTypes->mapWithKeys(fn($t) => [$t->slug => $t->translated('name')])),
        categoryMap: @json($categories->mapWithKeys(fn($c) => [$c->slug => $c->translated('name')])),
        durationMap: { '1_3': '1-3 Days', '4_7': '4-7 Days', '8_12': '8-12 Days', '12_plus': '12+ Days' },
        priceMap: { 'under_2k': 'Under $2,000', '2k_5k': '$2K-$5K', '5k_10k': '$5K-$10K', 'over_10k': '$10K+' },
        monthMap: { '1':'Jan','2':'Feb','3':'Mar','4':'Apr','5':'May','6':'Jun','7':'Jul','8':'Aug','9':'Sep','10':'Oct','11':'Nov','12':'Dec' },

        // Computed
        get activeFilterCount() {
            return this.selectedCountries.length + this.selectedTourTypes.length +
                   this.selectedCategories.length + this.selectedDurations.length +
                   this.selectedPrices.length + this.selectedMonths.length +
                   (this.search.length > 0 ? 1 : 0);
        },

        get activePills() {
            let pills = [];
            this.selectedCountries.forEach(s => pills.push({ key: 'c_'+s, type: 'countries', slug: s, label: this.countryMap[s] || s, cls: 'pill-country' }));
            this.selectedTourTypes.forEach(s => pills.push({ key: 't_'+s, type: 'tour_types', slug: s, label: this.tourTypeMap[s] || s, cls: 'pill-style' }));
            this.selectedCategories.forEach(s => pills.push({ key: 'cat_'+s, type: 'categories', slug: s, label: this.categoryMap[s] || s, cls: 'pill-interest' }));
            this.selectedDurations.forEach(s => pills.push({ key: 'd_'+s, type: 'duration', slug: s, label: this.durationMap[s] || s, cls: 'pill-duration' }));
            this.selectedPrices.forEach(s => pills.push({ key: 'p_'+s, type: 'price', slug: s, label: this.priceMap[s] || s, cls: 'pill-price' }));
            this.selectedMonths.forEach(s => pills.push({ key: 'm_'+s, type: 'months', slug: s, label: this.monthMap[s] || s, cls: 'pill-month' }));
            return pills;
        },

        init() {
            ['selectedCountries','selectedTourTypes','selectedCategories','selectedDurations','selectedPrices','selectedMonths'].forEach(key => {
                this.$watch(key, () => this.fetchSafaris(), { deep: true });
            });

            this.$nextTick(() => this.revealCards());

            // Infinite scroll via IntersectionObserver
            this.$nextTick(() => {
                const sentinel = this.$refs.sentinel;
                if (sentinel) {
                    this._observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting && this.nextPageUrl && !this.loadingMore && !this.loading) {
                            this.loadMore();
                        }
                    }, { rootMargin: '200px' });
                    this._observer.observe(sentinel);
                }
            });
        },

        toggleFilter(arr, value) {
            const idx = arr.indexOf(value);
            if (idx > -1) arr.splice(idx, 1);
            else arr.push(value);
        },

        removePill(pill) {
            const map = {
                countries: 'selectedCountries',
                tour_types: 'selectedTourTypes',
                categories: 'selectedCategories',
                duration: 'selectedDurations',
                price: 'selectedPrices',
                months: 'selectedMonths',
            };
            const arr = this[map[pill.type]];
            const idx = arr.indexOf(pill.slug);
            if (idx > -1) arr.splice(idx, 1);
        },

        clearAll() {
            this.search = '';
            this.selectedCountries = [];
            this.selectedTourTypes = [];
            this.selectedCategories = [];
            this.selectedDurations = [];
            this.selectedPrices = [];
            this.selectedMonths = [];
            this.fetchSafaris();
        },

        buildParams() {
            const p = new URLSearchParams();
            if (this.search) p.set('search', this.search);
            if (this.selectedCountries.length) p.set('countries', this.selectedCountries.join(','));
            if (this.selectedTourTypes.length) p.set('tour_types', this.selectedTourTypes.join(','));
            if (this.selectedCategories.length) p.set('categories', this.selectedCategories.join(','));
            if (this.selectedDurations.length) p.set('duration', this.selectedDurations.join(','));
            if (this.selectedPrices.length) p.set('price', this.selectedPrices.join(','));
            return p;
        },

        revealCards() {
            document.querySelectorAll('.safari-card:not(.visible)').forEach((el, index) => {
                setTimeout(() => el.classList.add('visible'), index * 55);
            });
        },

        async fetchSafaris() {
            this.loading = true;
            const params = this.buildParams();

            // Update URL without reload
            const qs = params.toString();
            const url = window.location.pathname + (qs ? '?' + qs : '');
            window.history.replaceState({}, '', url);

            try {
                const resp = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await resp.json();
                document.getElementById('safari-grid').innerHTML = data.html;
                this.totalCount = data.count;
                this.nextPageUrl = data.next;
                this.$nextTick(() => this.revealCards());
            } catch (e) {
                console.error('Filter error:', e);
            } finally {
                this.loading = false;
            }
        },

        async loadMore() {
            if (!this.nextPageUrl || this.loadingMore) return;
            this.loadingMore = true;
            try {
                const resp = await fetch(this.nextPageUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await resp.json();
                document.getElementById('safari-grid').insertAdjacentHTML('beforeend', data.html);
                this.nextPageUrl = data.next;
                this.$nextTick(() => this.revealCards());
            } catch (e) {
                console.error('Load more error:', e);
            } finally {
                this.loadingMore = false;
            }
        },
    }));
});
</script>
@endpush
