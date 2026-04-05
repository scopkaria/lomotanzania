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

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    {{-- HERO HEADER --}}
    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    <section class="bg-brand-dark py-14 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-15">
            <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1600&h=500&fit=crop&q=60"
                 alt=- class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/50 to-brand-dark/90"></div>
        <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
            <p class="text-[11px] font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.safari_listing_kicker') ?: 'Curated Experiences' }}</p>
            <h1 class="font-heading text-3xl md:text-5xl font-bold uppercase tracking-[0.16em] text-white leading-tight mb-6">Find Your Perfect Safari</h1>

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

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    {{-- MAIN CONTENT --}}
    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
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

                {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
                {{-- LEFT SIDEBAR (Desktop) --}}
                {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
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

                {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
                {{-- RIGHT CONTENT --}}
                {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
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

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    {{-- MOBILE FILTER DRAWER --}}
    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
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
