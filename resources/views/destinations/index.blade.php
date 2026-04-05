@extends('layouts.app')

@section('title', (__('messages.destinations') ?: 'Destinations') . ' - ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
<style>
    .dest-card { opacity: 0; transform: translateY(24px); transition: opacity 600ms cubic-bezier(0.16,1,0.3,1), transform 600ms cubic-bezier(0.16,1,0.3,1); }
    .dest-card.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative bg-brand-dark py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1600&h=500&fit=crop&q=60" alt="" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/60 to-brand-dark/90"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <p class="text-[11px] font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.explore') ?: 'Explore' }}</p>
        <h1 class="font-heading text-3xl md:text-5xl font-bold text-white leading-tight mb-4">{{ __('messages.destinations') ?: 'Destinations' }}</h1>
        <p class="text-base text-white/80 max-w-lg mx-auto">Discover the most breathtaking safari destinations across Africa</p>
    </div>
</section>

@if(isset($sections) && $sections->count())
    @include('partials.render-page-sections', ['sections' => $sections, 'sectionDataMap' => $sectionDataMap ?? []])
@endif

{{-- Destination List --}}
<section class="py-12 md:py-20 bg-brand-light" x-data="destinationExplorer()" x-init="init()">
    <div class="max-w-6xl mx-auto px-6">

        <div class="mb-8 rounded-2xl border border-gray-200 bg-white p-4 md:p-5 shadow-sm">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                <div class="xl:col-span-1">
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-500">Search</label>
                    <input type="text" x-model.debounce.400ms="search" @input="resetAndFetch()" placeholder="Search destinations"
                           class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-brand-gold focus:ring-brand-gold">
                </div>
                <div>
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-500">Country</label>
                    <select x-model="selectedCountry" @change="resetAndFetch()" class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-brand-gold focus:ring-brand-gold">
                        <option value="">All countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->slug }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-500">Experience</label>
                    <select x-model="selectedTourType" @change="resetAndFetch()" class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-brand-gold focus:ring-brand-gold">
                        <option value="">All experiences</option>
                        @foreach($tourTypes as $tourType)
                            <option value="{{ $tourType->slug }}">{{ $tourType->translated('name') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.2em] text-gray-500">Budget</label>
                    <select x-model="selectedCategory" @change="resetAndFetch()" class="w-full rounded-lg border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-brand-gold focus:ring-brand-gold">
                        <option value="">All budgets</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->translated('name') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4">
                <p class="text-sm text-brand-dark/70">
                    Showing <span class="font-semibold text-brand-dark" x-text="totalCount"></span> destination options
                </p>
                <button type="button" @click="clearFilters()" class="text-sm font-medium text-brand-green hover:text-brand-dark transition">
                    Clear filters
                </button>
            </div>
        </div>

        <div id="dest-list" class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @include('destinations._list', ['destinations' => $destinations])
        </div>

        {{-- Infinite scroll sentinel --}}
        <div x-ref="sentinel" class="h-1"></div>

        {{-- Loading indicator --}}
        <div x-show="loading" class="text-center py-8">
            <div class="inline-flex items-center gap-2 text-sm text-brand-dark/60">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Loading destinations...
            </div>
        </div>

        <div x-show="!nextUrl && !loading" class="text-center py-8">
            <p class="text-sm text-brand-dark/40">You've explored all destinations</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('destinationExplorer', () => ({
        baseUrl: @json(route('destinations.index', ['locale' => app()->getLocale()])),
        nextUrl: @json($destinations->nextPageUrl()),
        totalCount: {{ $destinations->total() }},
        loading: false,
        observer: null,
        search: @json(request('search', '')),
        selectedCountry: @json(is_array(request('countries')) ? (request('countries')[0] ?? '') : request('countries', '')),
        selectedTourType: @json(is_array(request('tour_types')) ? (request('tour_types')[0] ?? '') : request('tour_types', '')),
        selectedCategory: @json(is_array(request('categories')) ? (request('categories')[0] ?? '') : request('categories', '')),

        init() {
            this.revealItems();
            this.observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && this.nextUrl && !this.loading) {
                    this.loadMore();
                }
            }, { rootMargin: '200px' });
            this.observer.observe(this.$refs.sentinel);
        },

        buildUrl() {
            const url = new URL(this.baseUrl);
            if (this.search && this.search.trim()) url.searchParams.set('search', this.search.trim());
            if (this.selectedCountry) url.searchParams.set('countries', this.selectedCountry);
            if (this.selectedTourType) url.searchParams.set('tour_types', this.selectedTourType);
            if (this.selectedCategory) url.searchParams.set('categories', this.selectedCategory);
            return url;
        },

        async resetAndFetch() {
            this.loading = true;
            try {
                const url = this.buildUrl();
                const resp = await fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await resp.json();
                document.getElementById('dest-list').innerHTML = data.html;
                this.nextUrl = data.next;
                this.totalCount = data.count;
                window.history.replaceState({}, '', url.toString());
                this.$nextTick(() => this.revealItems());
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async loadMore() {
            if (!this.nextUrl || this.loading) return;
            this.loading = true;
            try {
                const resp = await fetch(this.nextUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await resp.json();
                document.getElementById('dest-list').insertAdjacentHTML('beforeend', data.html);
                this.nextUrl = data.next;
                this.totalCount = data.count;
                this.$nextTick(() => this.revealItems());
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        clearFilters() {
            this.search = '';
            this.selectedCountry = '';
            this.selectedTourType = '';
            this.selectedCategory = '';
            this.resetAndFetch();
        },

        revealItems() {
            document.querySelectorAll('.dest-card:not(.visible)').forEach((el, i) => {
                setTimeout(() => el.classList.add('visible'), i * 80);
            });
        }
    }));
});
</script>
@endpush
