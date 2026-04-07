{{-- TripAdvisor Reviews Slider Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'What Travelers Say');
    $subheading = $section->getData('subheading', $locale, 'Real reviews from TripAdvisor');
    $reviews = $sectionData['tripadvisorReviews'] ?? collect();
@endphp

@if($reviews->count())
<section class="py-20 md:py-28 bg-[#083321] relative overflow-hidden">
    {{-- Decorative background --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-64 h-64 rounded-full bg-[#FEBC11] blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-[#34E0A1] blur-3xl"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-6"
         x-data="{
            current: 0,
            total: {{ $reviews->count() }},
            autoplay: null,
            startAutoplay() {
                this.autoplay = setInterval(() => { this.next() }, 6000);
            },
            stopAutoplay() {
                if(this.autoplay) clearInterval(this.autoplay);
            },
            next() {
                this.current = (this.current + 1) % this.total;
            },
            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
            }
         }"
         x-init="startAutoplay()"
         @mouseenter="stopAutoplay()"
         @mouseleave="startAutoplay()">

        {{-- Header --}}
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 mb-4">
                <svg class="w-6 h-6 text-[#34E0A1]" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" opacity="0"/>
                    <circle cx="8.5" cy="12.5" r="2" fill="currentColor"/>
                    <circle cx="15.5" cy="12.5" r="2" fill="currentColor"/>
                    <path d="M12 17.5c-2.33 0-4.3-1.6-5-3.5h1.5c.6 1.2 1.9 2 3.5 2s2.9-.8 3.5-2H17c-.7 1.9-2.67 3.5-5 3.5z" fill="currentColor" opacity="0.6"/>
                </svg>
                <span class="text-xs font-semibold tracking-[0.3em] uppercase text-[#34E0A1]">TripAdvisor</span>
            </div>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-white/70 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        {{-- Slider --}}
        <div class="relative">
            @foreach($reviews as $i => $review)
            <div x-show="current === {{ $i }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="text-center">

                {{-- Stars --}}
                <div class="flex items-center justify-center gap-1.5 mb-6">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="w-6 h-6 {{ $s < $review->rating ? 'text-[#FEBC11]' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>

                {{-- Review title --}}
                @if($review->title)
                <h3 class="text-lg md:text-xl font-semibold text-[#FEBC11] mb-4">&ldquo;{{ $review->title }}&rdquo;</h3>
                @endif

                {{-- Review text --}}
                <blockquote class="text-lg md:text-xl lg:text-2xl text-white/90 leading-relaxed mb-8 max-w-3xl mx-auto font-light italic">
                    &ldquo;{{ Str::limit($review->review_text, 300) }}&rdquo;
                </blockquote>

                {{-- Reviewer info --}}
                <div class="flex items-center justify-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-[#FEBC11]/20 flex items-center justify-center text-[#FEBC11] font-bold text-lg">
                        {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                    </div>
                    <div class="text-left">
                        <p class="text-white font-semibold">{{ $review->reviewer_name }}</p>
                        <div class="flex items-center gap-2 text-sm text-white/50">
                            @if($review->reviewer_location)
                                <span>{{ $review->reviewer_location }}</span>
                                <span>&middot;</span>
                            @endif
                            @if($review->trip_type)
                                <span>{{ $review->trip_type }}</span>
                                <span>&middot;</span>
                            @endif
                            @if($review->review_date)
                                <span>{{ $review->review_date->format('M Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Navigation --}}
        <div class="flex items-center justify-center gap-6 mt-10">
            {{-- Prev --}}
            <button @click="prev()" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/60 hover:text-white hover:border-white/50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </button>

            {{-- Dots --}}
            <div class="flex items-center gap-2">
                @foreach($reviews as $i => $r)
                <button @click="current = {{ $i }}"
                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                        :class="current === {{ $i }} ? 'bg-[#FEBC11] w-6' : 'bg-white/30 hover:bg-white/50'">
                </button>
                @endforeach
            </div>

            {{-- Next --}}
            <button @click="next()" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/60 hover:text-white hover:border-white/50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </button>
        </div>

        {{-- TripAdvisor badge --}}
        <div class="text-center mt-8">
            <a href="{{ App\Models\Setting::first()?->tripadvisor_url ?? '#' }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white/70 text-xs font-medium hover:bg-white/20 transition">
                <svg class="w-4 h-4 text-[#34E0A1]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                View all reviews on TripAdvisor
            </a>
        </div>
    </div>
</section>
@endif
