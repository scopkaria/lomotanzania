{{-- Booking CTA / Conversion Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Start Planning Your Dream Safari');
    $subheading = $section->getData('subheading', $locale, 'Let our experts craft a personalized itinerary just for you');
    $body = $section->getData('body', $locale);
    $buttonText = $section->getData('button_text', $locale, 'Plan My Safari');
    $buttonUrl = $section->data['button_url'] ?? route('plan-safari');
    $bgImage = $section->data['bg_image'] ?? null;
    $showBadges = ($section->data['show_badges'] ?? '1') === '1';
    $showTestimonials = ($section->data['show_testimonials'] ?? '1') === '1';

    // WhatsApp: prefer section-level, fallback to site setting
    $whatsapp = $section->data['whatsapp_number'] ?? null;
    if (!$whatsapp && !empty($sectionData['siteSetting'])) {
        $whatsapp = $sectionData['siteSetting']->whatsapp_number ?? null;
    }

    // Pull up to 2 recent testimonials if enabled
    $miniTestimonials = [];
    if ($showTestimonials) {
        $miniTestimonials = \App\Models\Testimonial::approved()
            ->whereNotNull('content')
            ->latest()
            ->limit(2)
            ->get();
    }
@endphp

<section class="relative py-20 md:py-28 lg:py-32 overflow-hidden bg-[#083321]">
    {{-- Background image overlay --}}
    @if($bgImage)
    <div class="absolute inset-0">
        <img src="{{ asset('storage/' . $bgImage) }}" alt="" class="w-full h-full object-cover opacity-15" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-b from-[#083321]/80 via-[#083321]/90 to-[#083321]"></div>
    </div>
    @endif

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        {{-- Heading block --}}
        <div class="text-center mb-12">
            @if($subheading)
            <p class="font-accent text-[#FEBC11] text-lg md:text-xl mb-3">{{ $subheading }}</p>
            @endif
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-heading tracking-safari mb-5">
                {{ $heading }}
            </h2>
            @if($body)
            <p class="text-white/80 text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
                {{ $body }}
            </p>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-14">
            <a href="{{ $buttonUrl }}"
               class="inline-flex items-center gap-2 px-10 py-4 bg-[#FEBC11] text-[#131414] font-bold uppercase tracking-wider text-sm rounded hover:scale-105 hover:brightness-90 transition-all duration-300 shadow-lg shadow-[#FEBC11]/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $buttonText }}
            </a>

            @if($whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-8 py-4 border-2 border-white/30 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-white hover:text-[#131414] transition-all duration-300">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.725-1.394A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.308-.724-5.993-1.953l-.42-.307-3.063.904.842-3.132-.327-.44A9.964 9.964 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                </svg>
                WhatsApp Us
            </a>
            @endif
        </div>

        {{-- Trust badges --}}
        @if($showBadges)
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 mb-14">
            <div class="flex items-center gap-3 text-white/70">
                <div class="w-10 h-10 rounded-full bg-[#FEBC11]/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-white text-sm font-semibold">Licensed & Insured</span>
                    <span class="block text-xs text-white/50">TALA Certified</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-white/70">
                <div class="w-10 h-10 rounded-full bg-[#FEBC11]/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#FEBC11]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-white text-sm font-semibold">5-Star Rated</span>
                    <span class="block text-xs text-white/50">TripAdvisor</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-white/70">
                <div class="w-10 h-10 rounded-full bg-[#FEBC11]/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-white text-sm font-semibold">500+ Safaris</span>
                    <span class="block text-xs text-white/50">Happy Travellers</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-white/70">
                <div class="w-10 h-10 rounded-full bg-[#FEBC11]/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-white text-sm font-semibold">Local Experts</span>
                    <span class="block text-xs text-white/50">Tanzania Born</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Mini testimonials --}}
        @if($showTestimonials && $miniTestimonials->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
            @foreach($miniTestimonials as $testimonial)
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                    <svg class="w-4 h-4 text-[#FEBC11]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-white/80 text-sm leading-relaxed italic mb-3">
                    "{{ \Illuminate\Support\Str::limit($testimonial->content, 120) }}"
                </p>
                <p class="text-[#FEBC11] text-xs font-semibold uppercase tracking-wider">
                    {{ $testimonial->name }}
                    @if($testimonial->country)
                    <span class="text-white/40"> — {{ $testimonial->country }}</span>
                    @endif
                </p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
