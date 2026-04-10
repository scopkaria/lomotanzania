{{-- Call to Action Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Ready for Your Adventure?');
    $subheading = $section->getData('subheading', $locale);
    $buttonText = $section->getData('button_text', $locale, 'Plan Your Safari');
    $buttonUrl = $section->data['button_url'] ?? route('plan-safari');
    $bgColor = $section->data['bg_color'] ?? '#083321';
    $bgImage = $section->data['bg_image'] ?? null;
@endphp

<section class="relative py-24 md:py-32 overflow-hidden" style="background-color: {{ $bgColor }};">
    @if($bgImage)
    <div class="absolute inset-0">
        <img src="{{ asset('storage/' . $bgImage) }}" alt=- class="w-full h-full object-cover opacity-20" loading="lazy">
    </div>
    @endif

    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-heading tracking-safari mb-5 reveal">
            {{ $heading }}
        </h2>
        @if($subheading)
        <p class="text-base md:text-lg text-white/85 leading-relaxed mb-10 max-w-xl mx-auto reveal">
            {{ $subheading }}
        </p>
        @endif
        <div class="reveal">
            <a href="{{ $buttonUrl }}"
               class="inline-block px-8 py-4 bg-[#FEBC11] text-[#131414] text-sm font-bold uppercase tracking-wider rounded hover:scale-105 hover:brightness-90 transition-all duration-300 shadow-lg shadow-[#FEBC11]/20">
                {{ $buttonText }}
            </a>
        </div>
    </div>
</section>
