{{-- Testimonials Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'What Our Guests Say');
    $subheading = $section->getData('subheading', $locale);
    $testimonials = $sectionData['testimonials'] ?? collect();
@endphp

<section class="py-20 md:py-28 bg-[#F9F7F3]">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="font-accent text-2xl md:text-3xl text-brand-gold mb-2">Testimonials</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-brand-dark leading-heading tracking-safari">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-brand-dark/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if($testimonials->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow duration-300 reveal"
                 style="transition-delay: {{ ($loop->index + 1) * 100 }}ms;">

                {{-- Stars --}}
                @if($testimonial->rating)
                <div class="flex items-center gap-1 mb-4">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-4 h-4 {{ $s <= $testimonial->rating ? 'text-[#FEBC11]' : 'text-gray-200' }}"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                @endif

                {{-- Quote --}}
                <svg class="w-8 h-8 text-[#083321]/10 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>

                <p class="text-sm text-brand-dark/70 leading-relaxed mb-6">{{ $testimonial->message }}</p>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-[#083321]/10 flex items-center justify-center text-[#083321] font-bold text-sm">
                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-brand-dark">{{ $testimonial->name }}</p>
                        @if($testimonial->safariPackage)
                        <p class="text-xs text-brand-dark/40">{{ $testimonial->safariPackage->translated('title') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
