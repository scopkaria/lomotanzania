{{-- Testimonial Slider Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Guest Reviews');
    $subheading = $section->getData('subheading', $locale);
    $testimonials = $sectionData['testimonials'] ?? collect();
@endphp

<section class="py-20 md:py-28 bg-[#083321]" x-data="{ current: 0, total: {{ $testimonials->count() }} }">
    <div class="max-w-5xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-[#FEBC11] mb-3">Reviews</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-white/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if($testimonials->count())
        <div class="relative overflow-hidden">
            @foreach($testimonials as $i => $testimonial)
            <div x-show="current === {{ $i }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 -translate-x-8"
                 class="text-center">
                {{-- Stars --}}
                @if($testimonial->rating)
                <div class="flex items-center justify-center gap-1 mb-6">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="w-5 h-5 {{ $s < $testimonial->rating ? 'text-[#FEBC11]' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                @endif

                <blockquote class="text-xl md:text-2xl text-white/90 leading-relaxed mb-8 max-w-3xl mx-auto font-light italic">
                    &ldquo;{{ $testimonial->content }}&rdquo;
                </blockquote>

                <div>
                    <p class="text-white font-semibold text-lg">{{ $testimonial->name }}</p>
                    @if($testimonial->safariPackage)
                        <p class="text-white/50 text-sm mt-1">{{ $testimonial->safariPackage->translated('title') }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Navigation --}}
        @if($testimonials->count() > 1)
        <div class="flex items-center justify-center gap-4 mt-10">
            <button @click="current = current > 0 ? current - 1 : total - 1"
                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/60 hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex gap-2">
                @foreach($testimonials as $i => $t)
                    <button @click="current = {{ $i }}"
                            :class="current === {{ $i }} ? 'bg-[#FEBC11]' : 'bg-white/20'"
                            class="w-2.5 h-2.5 rounded-full transition-colors duration-300"></button>
                @endforeach
            </div>
            <button @click="current = current < total - 1 ? current + 1 : 0"
                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white/60 hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
        @endif
        @else
            <p class="text-center text-white/40">No reviews available yet.</p>
        @endif
    </div>
</section>
