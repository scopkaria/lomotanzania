{{-- Reviews Slider Section Widget — reusable on any page --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'What Travelers Say');
    $subheading = $section->getData('subheading', $locale);
    $reviews = $sectionData['tripadvisorReviews'] ?? collect();
    $bgColor = $section->getData('bg_color', null, '#083321');
    $tripadvisorUrl = $section->getData('tripadvisor_url', null, '');
@endphp

<section class="py-20 md:py-28 relative overflow-hidden" style="background-color: {{ $bgColor }};"
         x-data="{ current: 0, total: {{ $reviews->count() }} }"
         x-init="setInterval(() => { current = (current + 1) % total }, 6000)">
    <div class="max-w-5xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-[#FEBC11] mb-3">Reviews</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-white/80 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if($reviews->count())
        <div class="relative" style="min-height: 280px;">
            @foreach($reviews as $i => $review)
            <div x-show="current === {{ $i }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 text-center"
                 @if($i > 0) style="display: none;" @endif>

                {{-- Stars --}}
                @if($review->rating)
                <div class="flex items-center justify-center gap-1 mb-6">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="w-5 h-5 {{ $s < $review->rating ? 'text-[#FEBC11]' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                @endif

                {{-- Review title --}}
                @if($review->title)
                <h3 class="text-lg md:text-xl font-semibold text-[#FEBC11] mb-3">&ldquo;{{ $review->title }}&rdquo;</h3>
                @endif

                {{-- Review text --}}
                <blockquote class="text-xl md:text-2xl text-white/90 leading-relaxed mb-8 max-w-3xl mx-auto font-light italic">
                    &ldquo;{{ Str::limit($review->review_text, 300) }}&rdquo;
                </blockquote>

                {{-- Reviewer info --}}
                <div>
                    <p class="text-white font-semibold text-lg">{{ $review->reviewer_name }}</p>
                    <div class="flex items-center justify-center gap-2 text-sm text-white/60 mt-1">
                        @if($review->reviewer_location)
                            <span>{{ $review->reviewer_location }}</span>
                        @endif
                        @if($review->trip_type)
                            <span>&middot;</span>
                            <span>{{ $review->trip_type }}</span>
                        @endif
                        @if($review->review_date)
                            <span>&middot;</span>
                            <span>{{ $review->review_date->format('M Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Navigation --}}
        @if($reviews->count() > 1)
        <div class="flex items-center justify-center gap-4 mt-10">
            <button @click="current = current > 0 ? current - 1 : total - 1"
                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/85 hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex gap-2">
                @foreach($reviews as $i => $r)
                    <button @click="current = {{ $i }}"
                            :class="current === {{ $i }} ? 'bg-[#FEBC11]' : 'bg-white/20'"
                            class="w-2.5 h-2.5 rounded-full transition-colors duration-300"></button>
                @endforeach
            </div>
            <button @click="current = current < total - 1 ? current + 1 : 0"
                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/85 hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        @endif

        {{-- Optional link --}}
        @if($tripadvisorUrl)
        <div class="text-center mt-8">
            <a href="{{ $tripadvisorUrl }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white/70 text-xs font-medium hover:bg-white/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                View all reviews on TripAdvisor
            </a>
        </div>
        @endif
        @else
            <p class="text-center text-white/90">No reviews available yet.</p>
        @endif
    </div>
</section>
