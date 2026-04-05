{{-- CTA Banner Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Ready for Your Safari Adventure?');
    $subheading = $section->getData('subheading', $locale);
    $buttonText = $section->getData('button_text', $locale, 'Start Planning');
    $buttonUrl = $section->getData('button_url', null, '#');
    $button2Text = $section->getData('button2_text', $locale);
    $button2Url = $section->getData('button2_url', null);
    $bgColor = $section->data['bg_color'] ?? '#083321';
    $bgImage = $section->getData('bg_image', null);
@endphp

<section class="relative py-20 md:py-28 overflow-hidden"
         style="background-color: {{ $bgColor }};">
    @if($bgImage)
        <img src="{{ str_starts_with($bgImage, 'http') ? $bgImage : asset('storage/' . $bgImage) }}"
             alt=- loading="lazy"
             class="absolute inset-0 w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/30"></div>
    @endif

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        @if($heading)
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">
                {{ $heading }}
            </h2>
        @endif
        @if($subheading)
            <p class="text-lg text-white/90 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $subheading }}
            </p>
        @endif
        <div class="flex flex-wrap justify-center gap-4">
            @if($buttonText && $buttonUrl)
                <a href="{{ $buttonUrl }}"
                   class="inline-block px-10 py-4 bg-[#FEBC11] text-[#131414] font-bold uppercase tracking-wider text-sm rounded hover:bg-white transition-all duration-300 shadow-lg hover:shadow-xl">
                    {{ $buttonText }}
                </a>
            @endif
            @if($button2Text && $button2Url)
                <a href="{{ $button2Url }}"
                   class="inline-block px-10 py-4 border-2 border-white text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-white hover:text-[#131414] transition-all duration-300">
                    {{ $button2Text }}
                </a>
            @endif
        </div>
    </div>
</section>
