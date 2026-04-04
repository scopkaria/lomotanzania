@forelse($safaris as $safari)
<div class="safari-card group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
    {{-- Image --}}
    <div class="relative overflow-hidden">
        @if($safari->featured_image)
            <img src="{{ asset('storage/' . $safari->featured_image) }}"
                 alt="{{ $safari->translated('title') }}" loading="lazy"
                 class="w-full h-[260px] object-cover transition-transform duration-700 ease-out group-hover:scale-105">
        @else
            <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?w=700&h=460&fit=crop&q=80"
                 alt="{{ $safari->translated('title') }}" loading="lazy"
                 class="w-full h-[260px] object-cover transition-transform duration-700 ease-out group-hover:scale-105">
        @endif
        {{-- Country badge --}}
        @if($safari->countries->isNotEmpty())
            <div class="absolute top-3 left-3 flex gap-1.5">
                @foreach($safari->countries->take(2) as $country)
                    <span class="bg-white/90 backdrop-blur-sm text-[10px] font-semibold uppercase tracking-wider text-brand-dark px-2.5 py-1 rounded">{{ $country->name }}</span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-5">
        <h3 class="font-heading text-lg font-bold text-brand-dark leading-snug mb-1.5 line-clamp-2">{{ $safari->translated('title') }}</h3>

        <p class="text-[13px] text-brand-dark/50 leading-relaxed mb-3 line-clamp-1">{{ Str::limit($safari->translated('short_description'), 90) }}</p>

        {{-- Duration --}}
        @if($safari->duration)
            <p class="text-[11px] font-semibold uppercase tracking-widest text-brand-dark/40 mb-2">
                {{ $safari->duration }} {{ Str::upper(__('messages.days_label') ?: 'Days') }}
            </p>
        @endif

        {{-- Itinerary route --}}
        @php
            $stops = $safari->itineraries
                ->sortBy('day_number')
                ->pluck('destination')
                ->filter()
                ->map(fn($d) => $d->translated('name'))
                ->unique()
                ->values();
        @endphp
        @if($stops->isNotEmpty())
            <p class="text-xs text-brand-dark/40 mb-4 line-clamp-1">
                {{ $stops->take(5)->implode(' • ') }}{{ $stops->count() > 5 ? ' …' : '' }}
            </p>
        @endif

        {{-- Bottom row --}}
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            @if($safari->price)
                <span class="text-sm font-semibold text-brand-green">
                    {{ __('messages.from_label') ?: 'From' }} ${{ number_format($safari->price) }} <span class="font-normal text-brand-dark/40 text-xs">/ person</span>
                </span>
                <a href="{{ route('safaris.show', $safari->slug) }}"
                   class="inline-flex items-center gap-1 px-4 py-2 border border-brand-dark/20 text-xs font-semibold uppercase tracking-wider text-brand-dark rounded hover:bg-brand-dark hover:text-white transition-all duration-200">
                    {{ __('messages.view_safari') }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <a href="{{ route('safaris.show', $safari->slug) }}"
                   class="mx-auto inline-flex items-center gap-1 px-5 py-2 border border-brand-dark/20 text-xs font-semibold uppercase tracking-wider text-brand-dark rounded hover:bg-brand-dark hover:text-white transition-all duration-200">
                    {{ __('messages.view_safari') }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>
    </div>
</div>
@empty
<div class="col-span-full text-center py-24">
    <svg class="mx-auto w-16 h-16 text-brand-dark/10 mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <p class="text-brand-dark/40 text-sm mb-2">{{ __('messages.no_safaris_found') ?: 'No safaris found. Try adjusting your filters.' }}</p>
    <button @click="clearAll()" class="text-sm text-brand-gold hover:underline">{{ __('messages.clear_filters') ?: 'Clear all filters' }}</button>
</div>
@endforelse
