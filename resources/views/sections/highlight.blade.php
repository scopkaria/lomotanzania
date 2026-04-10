{{-- Highlight Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, '');
    $subheading = $section->getData('subheading', $locale);
    $body = $section->getData('body', $locale);
    $items = $section->data['items'] ?? [];
    $bgColor = $section->data['bg_color'] ?? '#083321';
    $bgImage = $section->getData('bg_image', null);
@endphp

<section class="relative py-20 md:py-28 overflow-hidden" style="background-color: {{ $bgColor }};">
    @if($bgImage)
        <img src="{{ str_starts_with($bgImage, 'http') ? $bgImage : asset('storage/' . $bgImage) }}"
             alt=- loading="lazy"
             class="absolute inset-0 w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>
    @endif

    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        @if($heading)
            <p class="font-accent text-2xl md:text-3xl text-[#FEBC11] mb-2 reveal">Our Meaning</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-heading tracking-safari mb-6 reveal">
                {{ $heading }}
            </h2>
        @endif

        @if($subheading)
            <p class="font-heading text-2xl md:text-3xl text-[#FEBC11] italic mb-6 reveal">
                &ldquo;{{ $subheading }}&rdquo;
            </p>
        @endif

        @if($body)
            <div class="prose prose-lg prose-invert max-w-2xl mx-auto text-white/90 leading-relaxed mb-10 reveal">
                {!! $body !!}
            </div>
        @endif

        @if(count($items))
            <div class="flex flex-wrap justify-center gap-4 mt-8 reveal">
                @foreach($items as $item)
                    @php
                        $label = is_array($item['title'] ?? '') ? ($item['title'][$locale] ?? $item['title']['en'] ?? '') : ($item['title'] ?? '');
                    @endphp
                    <span class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-white/20 text-white/80 text-sm font-medium backdrop-blur-sm">
                        <svg class="w-4 h-4 text-[#FEBC11]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        {{ $label }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>
</section>
