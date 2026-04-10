{{-- Image + Text Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale);
    $body = $section->getData('body', $locale);
    $image = $section->getData('image', null);
    $layout = $section->data['layout'] ?? 'image_left';
    $imgLeft = $layout === 'image_left';
@endphp

<section class="py-16 md:py-24 bg-[#F9F7F3]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="{{ $imgLeft ? 'order-1' : 'order-1 md:order-2' }}">
                @if($image)
                    <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}"
                         alt="{{ $heading }}" loading="lazy"
                         class="rounded-xl shadow-lg w-full h-auto object-cover">
                @endif
            </div>
            <div class="{{ $imgLeft ? 'order-2' : 'order-2 md:order-1' }}">
                @if($heading)
                    <h2 class="font-heading text-3xl md:text-4xl font-bold text-brand-dark leading-heading tracking-safari mb-6">
                        {{ $heading }}
                    </h2>
                @endif
                @if($body)
                    <div class="prose prose-lg max-w-none text-brand-dark/70 leading-relaxed">
                        {!! $body !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
