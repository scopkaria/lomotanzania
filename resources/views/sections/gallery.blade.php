{{-- Gallery Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Gallery');
    $images = $section->data['images'] ?? [];
    if (is_string($images)) {
        $images = array_filter(array_map('trim', explode(',', $images)));
    }
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        @if($heading)
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-[#131414] mb-10 text-center">
                {{ $heading }}
            </h2>
        @endif

        @if(count($images))
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($images as $img)
                    @if(!empty($img))
                        <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}"
                             alt="" loading="lazy"
                             class="rounded-lg w-full h-56 object-cover hover:opacity-90 transition-opacity duration-300">
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
