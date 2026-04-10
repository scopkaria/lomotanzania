{{-- UPDATED: Experience Grid — cinematic full-width stacking scroll --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Our Experiences');
    $subheading = $section->getData('subheading', $locale);
    $tourTypes = $sectionData['tourTypes'] ?? collect();
    $staticItems = $section->data['items'] ?? [];

    // Merge dynamic + static into one collection for rendering
    $slides = collect();
    if ($tourTypes->count()) {
        foreach ($tourTypes as $tt) {
            $slides->push([
                'title'  => $tt->translated('name'),
                'desc'   => $tt->translated('description'),
                'image'  => $tt->featured_image ? asset('storage/' . $tt->featured_image) : null,
                'url'    => route('tour-types.show', ['locale' => $locale, 'slug' => $tt->slug]),
                'count'  => $tt->safari_packages_count ?? 0,
            ]);
        }
    } elseif (count($staticItems)) {
        foreach ($staticItems as $item) {
            $title = is_array($item['title'] ?? '') ? ($item['title'][$locale] ?? $item['title']['en'] ?? '') : ($item['title'] ?? '');
            $desc  = is_array($item['description'] ?? '') ? ($item['description'][$locale] ?? $item['description']['en'] ?? '') : ($item['description'] ?? '');
            $slides->push([
                'title' => $title,
                'desc'  => $desc,
                'image' => isset($item['image']) ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image'])) : null,
                'url'   => $item['link'] ?? null,
                'count' => 0,
            ]);
        }
    }
@endphp

@if($slides->count())
{{-- CINEMATIC SCROLL: full-width stacking section --}}
<section class="exp-cinema-section relative bg-brand-dark" id="experience-stack-section">

    {{-- Heading overlay — sits on top of the stack --}}
    <div class="exp-cinema-heading sticky top-0 z-[50] flex items-center justify-center h-screen pointer-events-none">
        <div class="text-center px-6 exp-heading-inner">
            <p class="font-accent text-2xl md:text-3xl text-brand-gold mb-3 opacity-0 exp-head-fade" style="animation: expHeadUp 1s .2s ease both;">Experiences</p>
            <h2 class="font-heading text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] tracking-tight opacity-0 exp-head-fade" style="animation: expHeadUp 1s .45s ease both;">
                {{ $heading }}
            </h2>
            @if($subheading)
                <p class="mt-5 text-base md:text-lg text-white/50 max-w-2xl mx-auto opacity-0 exp-head-fade" style="animation: expHeadUp 1s .65s ease both;">{{ $subheading }}</p>
            @endif
        </div>
    </div>

    {{-- Stacking slides --}}
    <div class="exp-cinema-track relative" style="margin-top: -100vh;">
        @foreach($slides as $i => $slide)
        <div class="exp-cinema-card sticky top-0 w-full h-screen overflow-hidden"
             style="z-index: {{ 60 + $i }};"
             data-exp-index="{{ $i }}">

            {{-- FULL WIDTH LAYOUT: image + text split --}}
            <div class="relative w-full h-full flex flex-col lg:flex-row">

                {{-- Image panel — 60% on desktop, full height --}}
                <div class="relative {{ $i % 2 !== 0 ? 'lg:order-2' : '' }} w-full lg:w-[60%] h-[45vh] lg:h-full overflow-hidden">
                    @if($slide['image'])
                        <img src="{{ $slide['image'] }}"
                             alt="{{ $slide['title'] }}" loading="lazy"
                             class="exp-cinema-img absolute inset-0 w-full h-full object-cover will-change-transform"
                             style="transform: scale(1.08);">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-dark to-brand-green"></div>
                    @endif
                    {{-- Subtle gradient overlay on image edge --}}
                    <div class="absolute inset-0 {{ $i % 2 !== 0 ? 'bg-gradient-to-l' : 'bg-gradient-to-r' }} from-transparent via-transparent to-brand-dark/30 hidden lg:block"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/20 to-transparent lg:hidden"></div>
                </div>

                {{-- Text panel — 40% on desktop --}}
                <div class="relative {{ $i % 2 !== 0 ? 'lg:order-1' : '' }} w-full lg:w-[40%] h-[55vh] lg:h-full bg-brand-dark flex items-center">
                    <div class="px-8 md:px-12 lg:px-16 xl:px-20 py-10 lg:py-0 max-w-xl exp-cinema-text" data-exp-text>

                        {{-- Safari count badge --}}
                        @if($slide['count'] > 0)
                            <span class="inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-[.2em] text-brand-gold mb-6 exp-txt-item" style="--exp-d:0;">
                                <span class="w-8 h-px bg-brand-gold/40"></span>
                                {{ $slide['count'] }} {{ Str::plural('Safari', $slide['count']) }}
                            </span>
                        @endif

                        {{-- Slide number --}}
                        <span class="block font-heading text-7xl lg:text-8xl font-bold text-white/[0.04] absolute top-6 right-8 lg:top-10 lg:right-12 select-none pointer-events-none">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        {{-- Title --}}
                        <h3 class="font-heading text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-white leading-[1.1] mb-5 exp-txt-item" style="--exp-d:1;">
                            {{ $slide['title'] }}
                        </h3>

                        {{-- Description --}}
                        @if($slide['desc'])
                            <p class="text-[15px] md:text-base text-white/55 leading-relaxed mb-8 line-clamp-4 exp-txt-item" style="--exp-d:2;">
                                {{ Str::limit(strip_tags($slide['desc']), 220) }}
                            </p>
                        @endif

                        {{-- CTA --}}
                        @if($slide['url'])
                            <a href="{{ $slide['url'] }}"
                               class="group inline-flex items-center gap-3 exp-txt-item pointer-events-auto" style="--exp-d:3;">
                                <span class="text-sm font-bold uppercase tracking-[.15em] text-white group-hover:text-brand-gold transition-colors duration-500">
                                    Explore
                                </span>
                                <span class="flex items-center justify-center w-10 h-10 rounded-full border border-white/20 group-hover:border-brand-gold group-hover:bg-brand-gold/10 transition-all duration-500">
                                    <svg class="w-4 h-4 text-white group-hover:text-brand-gold transition-all duration-500 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- CINEMATIC SCROLL: IntersectionObserver-based animations --}}
<script>
(function(){
    if (window.innerWidth < 768) return;          // mobile: no heavy animations

    const cards = document.querySelectorAll('.exp-cinema-card');
    const heading = document.querySelector('.exp-cinema-heading');
    if (!cards.length) return;

    /* Heading fade-out on first card arrival */
    const headObs = new IntersectionObserver(([e]) => {
        if (heading) heading.style.opacity = e.isIntersecting ? '0' : '1';
    }, { threshold: 0.3 });
    if (cards[0]) headObs.observe(cards[0]);

    /* Per-card: image zoom + text reveal */
    const cardObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            const img  = e.target.querySelector('.exp-cinema-img');
            const txts = e.target.querySelectorAll('.exp-txt-item');
            if (e.isIntersecting) {
                if (img) img.style.transform = 'scale(1)';
                txts.forEach(t => { t.classList.add('exp-visible'); });
            } else {
                if (img) img.style.transform = 'scale(1.08)';
                txts.forEach(t => { t.classList.remove('exp-visible'); });
            }
        });
    }, { threshold: 0.45 });

    cards.forEach(c => cardObs.observe(c));
})();
</script>
@endif
