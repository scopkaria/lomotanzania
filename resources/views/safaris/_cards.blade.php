@forelse($safaris as $safari)
    @php
        $locations = $safari->destinations
            ->pluck('name')
            ->filter()
            ->values();

        if ($locations->isEmpty()) {
            $locations = $safari->itineraries
                ->sortBy('day_number')
                ->pluck('destination')
                ->filter()
                ->map(fn ($destination) => $destination->translated('name'))
                ->unique()
                ->values();
        }

        $seasonalValues = collect($safari->seasonal_pricing ?? [])
            ->flatMap(fn ($season) => collect($season)->filter(fn ($value) => filled($value)));
        $startingPrice = filled($safari->price) ? (float) $safari->price : ($seasonalValues->isNotEmpty() ? (float) $seasonalValues->min() : null);
    @endphp

    <article class="safari-card group flex h-full flex-col border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <a href="{{ route('safaris.show', ['locale' => app()->getLocale(), 'slug' => $safari->slug]) }}" class="block">
            <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100">
                <img src="{{ $safari->featured_image ? asset('storage/' . $safari->featured_image) : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=460&fit=crop&q=80' }}"
                     alt="{{ $safari->translated('title') }}"
                     loading="lazy"
                     class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                @php
                    $badgePrice = null;
                    if (optional($siteSetting ?? null)->show_card_price_badge ?? true) {
                        $sp = $safari->seasonal_pricing ?? [];
                        $season = optional($siteSetting ?? null)->card_price_season ?? 'low';
                        $pax = optional($siteSetting ?? null)->card_price_pax ?? 'pax_6';
                        $badgePrice = $sp[$season][$pax] ?? null;
                        if (!$badgePrice) {
                            $badgePrice = filled($safari->price) ? (float) $safari->price : null;
                        }
                    }
                @endphp
                @if($badgePrice)
                    <span class="absolute right-3 top-3 bg-brand-gold px-3 py-1 text-xs font-bold text-brand-dark shadow-sm">
                        From ${{ number_format((float) $badgePrice, 0) }}
                    </span>
                @endif
            </div>
        </a>

        <div class="flex flex-1 flex-col p-4 sm:p-5">
            @if($safari->duration)
                <p class="mb-2 flex items-center gap-1.5 text-kicker uppercase tracking-kicker text-brand-green/70">
                    <svg class="w-3.5 h-3.5 text-brand-green/50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $safari->duration }}
                </p>
            @endif

            <h3 class="line-clamp-2 font-body text-base font-bold leading-snug text-brand-dark transition-colors group-hover:text-brand-green sm:text-lg">
                <a href="{{ route('safaris.show', ['locale' => app()->getLocale(), 'slug' => $safari->slug]) }}">
                    {{ $safari->translated('title') }}
                </a>
            </h3>

            @if($locations->isNotEmpty())
                <p class="mt-2 line-clamp-1 text-[12px] text-gray-500">
                    {{ $locations->take(3)->join(' • ') }}
                </p>
            @endif

            <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">
                {{ Str::limit(strip_tags($safari->translated('short_description') ?: $safari->translated('description')), 160) }}
            </p>

            <div class="mt-auto pt-4">
                <div class="flex justify-center">
                      {{-- UPDATED SAFARI CARD: Border button with hover fill --}}
                      <a href="{{ route('safaris.show', ['locale' => app()->getLocale(), 'slug' => $safari->slug]) }}"
                         class="inline-flex w-full items-center justify-center gap-2 border-2 border-brand-dark px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-brand-dark transition-all duration-300 hover:bg-brand-gold hover:border-brand-gold hover:text-brand-dark">
                        View Safari
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </article>
@empty
    <div class="col-span-full py-24 text-center">
        <svg class="mx-auto mb-4 h-16 w-16 text-brand-dark/10" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <p class="mb-2 text-sm text-brand-dark/90">{{ __('messages.no_safaris_found') ?: 'No safaris found. Try adjusting your filters.' }}</p>
        <button @click="clearAll()" class="text-sm text-brand-gold hover:underline">{{ __('messages.clear_filters') ?: 'Clear all filters' }}</button>
    </div>
@endforelse

{{-- CTA Card — Plan a Custom Safari --}}
@if($safaris->isNotEmpty())
<article class="safari-card flex h-full flex-col border border-brand-gold/30 bg-gradient-to-br from-[#083321] to-[#0a4a30] shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
    <div class="flex flex-1 flex-col items-center justify-center p-8 text-center">
        <div class="mb-5 w-14 h-14 rounded-full bg-brand-gold/20 flex items-center justify-center">
            <svg class="w-7 h-7 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        </div>
        <h3 class="font-heading text-xl font-bold text-white mb-3">Plan Your Dream Safari</h3>
        <p class="text-sm text-white/70 leading-relaxed mb-6 max-w-xs">Can't find what you're looking for? Let our experts craft a bespoke safari tailored to your wishes.</p>
        <a href="{{ route('plan-safari', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-brand-gold text-brand-dark text-xs font-bold uppercase tracking-[0.14em] hover:bg-white transition-all duration-300">
            Start Planning
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</article>
@endif
