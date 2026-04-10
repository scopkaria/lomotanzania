{{-- Text Block Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale);
    $body = $section->getData('body', $locale);
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        @if($heading)
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-heading tracking-safari mb-8">
                {{ $heading }}
            </h2>
        @endif
        @if($body)
            <div class="prose prose-lg max-w-none text-brand-dark/70 leading-relaxed">
                {!! $body !!}
            </div>
        @endif
    </div>
</section>
