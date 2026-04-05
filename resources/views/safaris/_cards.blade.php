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
            </div>
        </a>

        <div class="flex flex-1 flex-col p-4 sm:p-5">
            @if($safari->duration)
                <p class="mb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-brand-green/70">{{ $safari->duration }}</p>
            @endif

            <h3 class="line-clamp-2 font-heading text-base font-bold leading-snug text-brand-dark transition-colors group-hover:text-brand-green sm:text-lg">
                {{ $safari->translated('title') }}
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
                @if($startingPrice !== null)
                    <p class="mb-3 text-sm font-semibold text-brand-green">
                        From ${{ number_format($startingPrice, 0) }} <span class="font-normal text-gray-500">/ person</span>
                    </p>
                @endif

                <div class="flex {{ $startingPrice !== null ? 'justify-start' : 'justify-center' }}">
                    <a href="{{ route('safaris.show', ['locale' => app()->getLocale(), 'slug' => $safari->slug]) }}"
                       class="inline-flex w-full items-center justify-center gap-2 bg-brand-dark px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-white transition-all duration-200 hover:bg-brand-gold hover:text-brand-dark">
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
