{{-- Dynamic Luxury Hero "” Video background + featured safari slider --}}
@php
    $heroSafaris  = $sectionData['heroSafaris'] ?? collect();
    $heroSettings = $sectionData['heroSettings'] ?? null;
    $locale       = app()->getLocale();

    // Fall back to HeroSlide-based hero if no featured safaris loaded
    $slides = $section->heroSlides ?? collect();

    $bgVideo      = $heroSettings?->background_video;
    $videoPoster   = $heroSettings?->video_poster;
    $overlay       = $heroSettings?->overlay_opacity ?? 0.50;
    $autoplay      = $heroSettings?->autoplay ?? true;
    $speed         = $heroSettings?->transition_speed ?? 5000;

    $btnText       = $heroSettings?->button_text ?? [];
    $btnLink       = $heroSettings?->button_link;
    $bgVideoMime   = match (strtolower(pathinfo((string) $bgVideo, PATHINFO_EXTENSION))) {
        'webm' => 'video/webm',
        'mov' => 'video/quicktime',
        default => 'video/mp4',
    };
@endphp

@if($heroSafaris->count())
{{-- ============================================ --}}
{{-- VIDEO HERO "” Featured Safari Slider          --}}
{{-- ============================================ --}}
<section
    x-data="luxuryHero({{ $heroSafaris->count() }}, {{ $autoplay ? 'true' : 'false' }}, {{ $speed }})"
    x-init="start()"
    @keydown.arrow-right.window="next()"
    @keydown.arrow-left.window="prev()"
    class="relative h-[100svh] min-h-[600px] max-h-[900px] overflow-hidden bg-[#131414]"
    x-ref="hero"
>
    {{-- Background Video --}}
    @if($bgVideo)
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline
               poster="{{ $videoPoster ? asset('storage/' . $videoPoster) : '' }}"
               class="w-full h-full object-cover">
            <source src="{{ asset('storage/' . $bgVideo) }}" type="{{ $bgVideoMime }}">
        </video>
    </div>
    @elseif($videoPoster)
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('storage/' . $videoPoster) }}" alt=- class="w-full h-full object-cover">
    </div>
    @endif

    {{-- Dark Overlay --}}
    <div class="absolute inset-0 z-[1]" style="background: rgba(0,0,0,{{ $overlay }});"></div>

    {{-- Slide Content --}}
    <div class="relative z-10 h-full flex flex-col">
        <div class="flex-1 flex flex-col lg:flex-row w-full max-w-7xl mx-auto px-6 lg:px-12">

            {{-- Left: Text Content --}}
            <div class="flex-1 flex flex-col justify-center py-24 lg:py-32 lg:pr-16 relative">
                @foreach($heroSafaris as $i => $safari)
                <div x-show="active === {{ $i }}" x-cloak
                     class="{{ $i === 0 ? '' : 'absolute inset-0 flex flex-col justify-center py-24 lg:py-32 lg:pr-16' }}">

                    {{-- Label --}}
                    @if($safari->featured_label)
                    <p class="text-kicker tracking-kicker uppercase text-[#FEBC11] mb-5"
                       x-show="active === {{ $i }}"
                       x-transition:enter="transition duration-700 delay-200"
                       x-transition:enter-start="opacity-0 -translate-y-3"
                       x-transition:enter-end="opacity-100 translate-y-0">
                        {{ $safari->featured_label }}
                    </p>
                    @endif

                    {{-- Title --}}
                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.08] tracking-[0.01em] mb-6"
                        x-show="active === {{ $i }}"
                        x-transition:enter="transition duration-700 delay-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        {{ $safari->translated('title', $locale) }}
                    </h1>

                    {{-- Subtitle / Short Description --}}
                    @if($safari->translated('short_description', $locale))
                    <p class="text-base md:text-lg text-white/80 leading-relaxed mb-10 max-w-md tracking-wide"
                       x-show="active === {{ $i }}"
                       x-transition:enter="transition duration-700 delay-[400ms]"
                       x-transition:enter-start="opacity-0 translate-y-4"
                       x-transition:enter-end="opacity-100 translate-y-0">
                        {{ Str::limit(strip_tags($safari->translated('short_description', $locale)), 140) }}
                    </p>
                    @endif

                    {{-- CTA Button --}}
                    <div x-show="active === {{ $i }}"
                         x-transition:enter="transition duration-700 delay-500"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        @php
                            $safariUrl = route('safaris.show', ['locale' => $locale, 'slug' => $safari->slug]);
                            $heroUrl   = $btnLink ?: $safariUrl;
                            $heroLabel = $btnText[$locale] ?? $btnText['en'] ?? __('messages.explore_safari') ?: 'Explore Safari';
                        @endphp
                        <a href="{{ $heroUrl }}"
                           class="inline-block px-8 py-4 bg-[#FEBC11] text-[#131414] text-sm font-bold uppercase tracking-[0.15em] rounded hover:scale-105 hover:brightness-90 transition-all duration-300 shadow-lg shadow-[#FEBC11]/20">
                            {{ $heroLabel }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Right: Featured Image (Portrait) --}}
            <div class="hidden lg:flex items-center justify-end flex-1 py-16">
                @foreach($heroSafaris as $i => $safari)
                @if($safari->featured_image)
                <div x-show="active === {{ $i }}" x-cloak
                     x-transition:enter="transition duration-700 delay-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-12 xl:right-24 top-1/2 -translate-y-1/2 w-full max-w-sm xl:max-w-md aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10">
                    <img src="{{ asset('storage/' . $safari->featured_image) }}"
                         alt="{{ $safari->translated('title', $locale) }}"
                         class="w-full h-full object-cover"
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Bottom Bar: Next Up + Navigation --}}
        @if($heroSafaris->count() > 1)
        <div class="relative z-20 w-full max-w-7xl mx-auto px-6 lg:px-12 pb-8 flex items-end justify-between">
            {{-- Left: Next Up Preview --}}
            <div class="hidden sm:block">
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-white/85 mb-1">Next Up</p>
                @foreach($heroSafaris as $i => $safari)
                    @php $nextIdx = ($i + 1) % $heroSafaris->count(); @endphp
                    <p x-show="active === {{ $i }}" x-cloak class="text-sm text-white/80 transition-all duration-300">
                        {{ $heroSafaris[$nextIdx]->translated('title', $locale) }}
                    </p>
                @endforeach
            </div>

            {{-- Center: Progress Bar --}}
            <div class="hidden md:flex items-center gap-2 absolute left-1/2 -translate-x-1/2 bottom-8">
                @foreach($heroSafaris as $i => $safari)
                <button @click="goTo({{ $i }})" class="group relative h-1 rounded-full overflow-hidden transition-all duration-300"
                        :class="active === {{ $i }} ? 'w-12 bg-white/20' : 'w-6 bg-white/10 hover:bg-white/20'">
                    <div class="absolute inset-y-0 left-0 bg-[#FEBC11] rounded-full transition-all"
                         :style="active === {{ $i }} ? 'width:' + progress + '%' : (active > {{ $i }} ? 'width:100%' : 'width:0%')"></div>
                </button>
                @endforeach
            </div>

            {{-- Right: Nav Arrows + Counter --}}
            <div class="flex items-center gap-3 ml-auto">
                <span class="text-xs text-white/90 font-mono mr-2">
                    <span x-text="String(active + 1).padStart(2, '0')"></span>
                    / {{ str_pad($heroSafaris->count(), 2, '0', STR_PAD_LEFT) }}
                </span>
                <button @click="prev()"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/85 hover:text-white hover:border-white/40 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </button>
                <button @click="next()"
                        class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/85 hover:text-white hover:border-white/40 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
            </div>
        </div>
        @endif
    </div>

    {{-- Mobile Featured Image (decorative, bottom-right) --}}
    <div class="lg:hidden absolute inset-0 z-[2] pointer-events-none">
        @foreach($heroSafaris as $i => $safari)
        @if($safari->featured_image)
        <div x-show="active === {{ $i }}" x-cloak
             class="absolute bottom-24 right-4 w-32 h-44 sm:w-40 sm:h-56 rounded-xl overflow-hidden shadow-xl ring-1 ring-white/10 opacity-40">
            <img src="{{ asset('storage/' . $safari->featured_image) }}"
                 alt="{{ $safari->translated('title', $locale) }}"
                 class="w-full h-full object-cover"
                 loading="lazy">
        </div>
        @endif
        @endforeach
    </div>
</section>

<script>
function luxuryHero(total, autoplay, interval) {
    return {
        active: 0,
        total,
        autoplay,
        interval,
        timer: null,
        progress: 0,
        progressTimer: null,
        touchStartX: 0,
        touchEndX: 0,

        start() {
            if (this.total <= 1) return;
            this.startProgress();
            if (this.autoplay) {
                this.timer = setInterval(() => this.next(), this.interval);
            }

            // Swipe support
            const el = this.$refs.hero;
            if (el) {
                el.addEventListener('touchstart', (e) => { this.touchStartX = e.changedTouches[0].screenX; }, { passive: true });
                el.addEventListener('touchend', (e) => {
                    this.touchEndX = e.changedTouches[0].screenX;
                    const diff = this.touchStartX - this.touchEndX;
                    if (Math.abs(diff) > 50) {
                        diff > 0 ? this.next() : this.prev();
                    }
                }, { passive: true });
            }
        },

        next() {
            this.active = (this.active + 1) % this.total;
            this.resetTimer();
        },

        prev() {
            this.active = (this.active - 1 + this.total) % this.total;
            this.resetTimer();
        },

        goTo(idx) {
            this.active = idx;
            this.resetTimer();
        },

        resetTimer() {
            if (this.timer) clearInterval(this.timer);
            this.resetProgress();
            if (this.autoplay && this.total > 1) {
                this.timer = setInterval(() => this.next(), this.interval);
            }
            this.startProgress();
        },

        startProgress() {
            this.progress = 0;
            if (this.progressTimer) cancelAnimationFrame(this.progressTimer);
            const startTime = performance.now();
            const duration = this.interval;
            const tick = (now) => {
                const elapsed = now - startTime;
                this.progress = Math.min((elapsed / duration) * 100, 100);
                if (elapsed < duration) {
                    this.progressTimer = requestAnimationFrame(tick);
                }
            };
            this.progressTimer = requestAnimationFrame(tick);
        },

        resetProgress() {
            if (this.progressTimer) cancelAnimationFrame(this.progressTimer);
            this.progress = 0;
        },
    };
}
</script>

@elseif($slides->count())
{{-- ============================================ --}}
{{-- FALLBACK: Original HeroSlide-based hero      --}}
{{-- ============================================ --}}
@php
    $fbAutoplay = $section->getData('slider_autoplay', null, '1');
    $fbInterval = (int) $section->getData('slider_interval', null, 5000);
@endphp
<section x-data="heroSlider({{ $slides->count() }}, {{ $fbAutoplay ? 'true' : 'false' }}, {{ $fbInterval }})"
         x-init="start()" class="relative min-h-screen overflow-hidden">

    @foreach($slides as $i => $slide)
    <div x-show="active === {{ $i }}"
         x-transition:enter="transition-opacity duration-700 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-500 ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 flex"
         style="background-color: {{ $slide->bg_color ?: '#083321' }};">

        @if($slide->bg_image)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $slide->bg_image) }}" alt=- class="w-full h-full object-cover opacity-20" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
        </div>
        @endif

        <div class="relative z-10 flex flex-col lg:flex-row w-full max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex-1 flex flex-col justify-center py-24 lg:py-32 lg:pr-12">
                @if($slide->translated('label', $locale))
                <p class="text-kicker tracking-kicker uppercase text-[#FEBC11] mb-5"
                   x-show="active === {{ $i }}" x-transition:enter="transition duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    {{ $slide->translated('label', $locale) }}
                </p>
                @endif

                <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.08] tracking-[0.01em] mb-6"
                    x-show="active === {{ $i }}" x-transition:enter="transition duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    {{ $slide->translated('title', $locale) }}
                </h1>

                @if($slide->translated('subtitle', $locale))
                <p class="text-base md:text-lg text-white/80 leading-relaxed mb-10 max-w-md tracking-wide"
                   x-show="active === {{ $i }}" x-transition:enter="transition duration-700 delay-400" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    {{ $slide->translated('subtitle', $locale) }}
                </p>
                @endif

                @if($slide->translated('button_text', $locale) && $slide->button_link)
                <div x-show="active === {{ $i }}" x-transition:enter="transition duration-700 delay-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <a href="{{ $slide->button_link }}"
                       class="inline-block px-8 py-4 bg-[#FEBC11] text-[#131414] text-sm font-bold uppercase tracking-[0.15em] rounded hover:scale-105 hover:brightness-90 transition-all duration-300 shadow-lg shadow-[#FEBC11]/20">
                        {{ $slide->translated('button_text', $locale) }}
                    </a>
                </div>
                @endif

                @if($slide->translated('next_up_text', $locale))
                <div class="mt-auto pt-12">
                    <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-white/85 mb-1">Next Up</p>
                    <p class="text-sm text-white/80">{{ $slide->translated('next_up_text', $locale) }}</p>
                </div>
                @endif
            </div>

            @if($slide->image)
            <div class="hidden lg:flex items-center justify-end flex-1 py-16">
                <div class="relative w-full max-w-md xl:max-w-lg aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl"
                     x-show="active === {{ $i }}" x-transition:enter="transition duration-700 delay-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <img src="{{ asset('storage/' . $slide->image) }}"
                         alt="{{ $slide->image_alt ?: $slide->translated('title', $locale) }}"
                         class="w-full h-full object-cover"
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach

    @if($slides->count() > 1)
    <div class="absolute bottom-8 right-8 z-20 flex items-center gap-3">
        <span class="text-xs text-white/90 font-mono mr-2">
            <span x-text="String(active + 1).padStart(2, '0')"></span>
            / {{ str_pad($slides->count(), 2, '0', STR_PAD_LEFT) }}
        </span>
        <button @click="prev()" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/85 hover:text-white hover:border-white/40 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        </button>
        <button @click="next()" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/85 hover:text-white hover:border-white/40 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </button>
    </div>

    <div class="absolute bottom-8 left-8 z-20 flex items-center gap-2">
        @foreach($slides as $i => $slide)
        <button @click="goTo({{ $i }})"
                :class="active === {{ $i }} ? 'bg-[#FEBC11] w-8' : 'bg-white/30 w-2 hover:bg-white/50'"
                class="h-2 rounded-full transition-all duration-300"></button>
        @endforeach
    </div>
    @endif
</section>

<script>
function heroSlider(total, autoplay, interval) {
    return {
        active: 0,
        total,
        autoplay,
        interval,
        timer: null,
        start() {
            if (this.autoplay && this.total > 1) {
                this.timer = setInterval(() => this.next(), this.interval);
            }
        },
        next() {
            this.active = (this.active + 1) % this.total;
            this.resetTimer();
        },
        prev() {
            this.active = (this.active - 1 + this.total) % this.total;
            this.resetTimer();
        },
        goTo(idx) {
            this.active = idx;
            this.resetTimer();
        },
        resetTimer() {
            if (this.timer) clearInterval(this.timer);
            if (this.autoplay && this.total > 1) {
                this.timer = setInterval(() => this.next(), this.interval);
            }
        },
    };
}
</script>
@endif
