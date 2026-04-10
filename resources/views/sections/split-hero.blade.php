{{-- Split Hero Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Discover Tanzania');
    $subheading = $section->getData('subheading', $locale);
    $body = $section->getData('body', $locale);
    $image = $section->getData('image', null);
    $buttonText = $section->getData('button_text', $locale);
    $buttonUrl = $section->getData('button_url', null);
    $layout = $section->data['layout'] ?? 'image_right';
    $imgRight = $layout === 'image_right';
@endphp

<section class="relative min-h-[70vh] flex items-center bg-[#083321] overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 w-full">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            {{-- Text Side --}}
            <div class="{{ $imgRight ? 'order-1' : 'order-1 md:order-2' }} py-16 md:py-24 reveal-left">
                @if($heading)
                    <h2 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        {{ $heading }}
                    </h2>
                @endif
                @if($subheading)
                    {{-- UPDATED: secondary color for motto/tagline --}}
                    <p class="text-lg md:text-xl text-[#FEBC11] leading-relaxed mb-6 max-w-lg font-semibold italic">
                        {{ $subheading }}
                    </p>
                @endif
                @if($body)
                    {{-- FIXED: white text on green bg for full contrast --}}
                    <div class="prose prose-lg prose-invert max-w-none text-white mb-8 [&_p]:text-white [&_strong]:text-[#FEBC11]">
                        {!! $body !!}
                    </div>
                @endif
                @if($buttonText && $buttonUrl)
                    <a href="{{ $buttonUrl }}"
                       class="inline-block px-8 py-4 bg-[#FEBC11] text-[#131414] font-bold uppercase tracking-wider text-sm rounded hover:bg-white transition-all duration-300">
                        {{ $buttonText }}
                    </a>
                @endif
            </div>

            {{-- Image Side --}}
            <div class="{{ $imgRight ? 'order-2' : 'order-2 md:order-1' }} relative reveal-right">
                @if($image)
                    <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}"
                         alt="{{ $heading }}" loading="lazy"
                         class="rounded-2xl shadow-2xl w-full h-[50vh] md:h-[60vh] object-cover">
                @else
                    <div class="w-full h-[50vh] md:h-[60vh] rounded-2xl bg-[#083321]/50"></div>
                @endif
            </div>
        </div>
    </div>
</section>
