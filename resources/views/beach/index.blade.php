@extends('layouts.app')

@section('title', 'Beach Holidays - ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('styles')
<style>
    .beach-item { opacity: 0; transform: translateY(24px); transition: opacity 600ms cubic-bezier(0.16,1,0.3,1), transform 600ms cubic-bezier(0.16,1,0.3,1); }
    .beach-item.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative bg-brand-dark py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-25">
        <img src="https://images.unsplash.com/photo-1590523741831-ab7e8b8f9c7f?w=1600&h=500&fit=crop&q=60" alt="" class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/50 to-brand-dark/90"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <p class="text-[11px] font-semibold tracking-[0.3em] uppercase text-brand-gold mb-3">{{ __('messages.coastal_paradise') }}</p>
        <h1 class="font-heading text-3xl md:text-5xl font-bold text-white leading-tight mb-4">{{ __('messages.beach_holidays') }}</h1>
        <p class="text-base text-white/80 max-w-lg mx-auto">{{ __('messages.beach_subtitle') }}</p>
    </div>
</section>

{{-- Beach List --}}
<section class="py-12 md:py-20 bg-brand-light" x-data="beachScroll()" x-init="init()">
    <div class="max-w-5xl mx-auto px-6">

        <div id="beach-list" class="space-y-8">
            @forelse($safaris as $safari)
            <div class="beach-item">
                <a href="{{ route('safaris.show', $safari->slug) }}"
                   class="group flex flex-col md:flex-row items-stretch bg-white overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 {{ $loop->iteration % 2 === 0 ? 'md:flex-row-reverse' : '' }}">

                    {{-- Text side --}}
                    <div class="flex-1 p-6 md:p-8 flex flex-col justify-center">
                        <h2 class="font-heading text-xl md:text-2xl font-bold text-brand-dark mb-2 group-hover:text-brand-green transition-colors">
                            {{ $safari->translated('title') }}
                        </h2>
                        @if($safari->duration)
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-brand-dark/60 mb-2">{{ $safari->duration }} Days</p>
                        @endif
                        @if($safari->translated('short_description'))
                            <p class="text-sm text-brand-dark/70 leading-relaxed line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($safari->translated('short_description')), 200) }}</p>
                        @endif
                        <div class="mt-4 flex items-center gap-4">
                            @if($safari->price)
                                <span class="text-sm font-semibold text-brand-green">From ${{ number_format($safari->price) }}</span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-brand-green group-hover:gap-2.5 transition-all">
                                View Details
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>

                    {{-- Image side --}}
                    <div class="w-full md:w-[380px] flex-shrink-0 aspect-[16/10] md:aspect-auto overflow-hidden">
                        @if($safari->featured_image)
                            <img src="{{ asset('storage/' . $safari->featured_image) }}"
                                 alt="{{ $safari->translated('title') }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        @else
                            <div class="w-full h-full min-h-[200px] bg-gradient-to-br from-blue-100 to-brand-gold/20 flex items-center justify-center">
                                <svg class="w-12 h-12 text-blue-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
            @empty
            <div class="text-center py-20">
                <svg class="mx-auto w-16 h-16 text-brand-dark/10 mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-brand-dark/50 text-lg">{{ __('messages.no_beach_holidays') }}</p>
            </div>
            @endforelse
        </div>

        {{-- Infinite scroll sentinel --}}
        <div x-ref="sentinel" class="h-1"></div>

        <div x-show="loading" class="text-center py-8">
            <div class="inline-flex items-center gap-2 text-sm text-brand-dark/60">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Loading...
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('beachScroll', () => ({
        nextUrl: @json($safaris->nextPageUrl()),
        loading: false,
        observer: null,

        init() {
            this.revealItems();
            this.observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && this.nextUrl && !this.loading) this.loadMore();
            }, { rootMargin: '200px' });
            this.observer.observe(this.$refs.sentinel);
        },

        async loadMore() {
            if (!this.nextUrl || this.loading) return;
            this.loading = true;
            try {
                const resp = await fetch(this.nextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                const data = await resp.json();
                document.getElementById('beach-list').insertAdjacentHTML('beforeend', data.html);
                this.nextUrl = data.next;
                this.$nextTick(() => this.revealItems());
            } catch (e) { console.error(e); }
            finally { this.loading = false; }
        },

        revealItems() {
            document.querySelectorAll('.beach-item:not(.visible)').forEach((el, i) => {
                setTimeout(() => el.classList.add('visible'), i * 80);
            });
        }
    }));
});
</script>
@endpush
