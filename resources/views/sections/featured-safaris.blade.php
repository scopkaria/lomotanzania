{{-- Featured Safaris Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Featured Safari Journeys');
    $subheading = $section->getData('subheading', $locale);
    $safaris = $sectionData['safaris'] ?? collect();
@endphp

<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="font-accent text-2xl md:text-3xl text-brand-gold mb-2">{{ __('messages.explore_itineraries') }}</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-brand-dark leading-heading tracking-safari">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-brand-dark/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($safaris as $safari)
            <div class="group bg-white rounded-xl shadow-sm overflow-hidden hover:-translate-y-1.5 hover:shadow-md transition-all duration-300 reveal" style="transition-delay: {{ ($loop->index + 1) * 100 }}ms;">
                <div class="relative overflow-hidden">
                    @if($safari->featured_image)
                        <img src="{{ asset('storage/' . $safari->featured_image) }}"
                             alt="{{ $safari->translated('title') }}" loading="lazy"
                             class="w-full h-56 object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                    @else
                        <div class="w-full h-56 bg-gradient-to-br from-[#083321] to-[#083321]/70 flex items-center justify-center">
                            <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
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
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs font-semibold uppercase tracking-wider text-brand-dark/40">{{ $safari->duration ?? __('messages.multi_day') }}</span>
                    </div>
                    <h3 class="font-body text-base md:text-lg font-bold text-brand-dark mb-2 leading-snug">
                        <a href="{{ route('safaris.show', $safari->slug) }}" class="hover:text-brand-green transition-colors">
                            {{ $safari->translated('title') }}
                        </a>
                    </h3>
                    <p class="text-sm text-brand-dark/50 leading-relaxed mb-5">{{ Str::limit($safari->translated('short_description'), 140) }}</p>
                    <a href="{{ route('safaris.show', $safari->slug) }}"
                       class="inline-block px-5 py-2.5 border-2 border-[#FEBC11] text-brand-dark text-xs font-bold uppercase tracking-wider rounded hover:bg-[#FEBC11] hover:text-brand-dark transition-all duration-300">
                        {{ __('messages.view_safari') }}
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-brand-dark/40 text-sm">{{ __('messages.featured_coming_soon') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
