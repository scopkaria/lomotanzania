{{-- Safari Grid Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Our Safaris');
    $subheading = $section->getData('subheading', $locale);
    $safaris = $sectionData['safaris'] ?? collect();
@endphp

<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="font-accent text-2xl md:text-3xl text-brand-gold mb-2">Safari Collection</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-brand-dark leading-heading tracking-safari">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-brand-dark/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if($safaris->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($safaris as $safari)
            <a href="{{ route('safaris.show', ['locale' => app()->getLocale(), 'slug' => $safari->slug]) }}"
               class="group bg-white rounded-2xl shadow-sm overflow-hidden hover:-translate-y-1.5 hover:shadow-md transition-all duration-300 reveal"
               style="transition-delay: {{ ($loop->index + 1) * 100 }}ms;">
                <div class="overflow-hidden aspect-[4/3] relative">
                    @if($safari->featured_image)
                        <img src="{{ asset('storage/' . $safari->featured_image) }}"
                             alt="{{ $safari->translated('title') }}" loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
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
                <div class="p-6">
                    <h3 class="font-body text-base md:text-lg font-bold text-brand-dark group-hover:text-brand-green transition-colors mb-2 leading-snug">
                        {{ $safari->translated('title') }}
                    </h3>
                        <div class="flex items-center gap-4 text-sm text-brand-dark/50">
                        @if($safari->duration)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $safari->duration }} Days
                            </span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
            <p class="text-center text-gray-400">No safaris available at the moment.</p>
        @endif
    </div>
</section>
