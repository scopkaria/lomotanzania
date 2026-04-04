{{-- Image Gallery Section (Lightbox) --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Photo Gallery');
    $subheading = $section->getData('subheading', $locale);
    $images = $section->data['images'] ?? [];
    if (is_string($images)) {
        $images = array_filter(array_map('trim', explode(',', $images)));
    }
    $columns = (int) ($section->data['columns'] ?? 3);
    $columns = max(2, min($columns, 4));
@endphp

<section class="py-20 md:py-28 bg-white" x-data="{ lightbox: false, current: 0 }">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-[#FEBC11] mb-3">Gallery</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-[#131414] leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-[#131414]/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if(count($images))
        <div class="grid grid-cols-2 md:grid-cols-{{ $columns }} gap-4">
            @foreach($images as $i => $img)
                @if(!empty($img))
                <div class="cursor-pointer overflow-hidden rounded-xl reveal"
                     style="transition-delay: {{ ($i + 1) * 50 }}ms;"
                     @click="lightbox = true; current = {{ $i }}">
                    <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}"
                         alt="" loading="lazy"
                         class="w-full h-56 md:h-64 object-cover hover:scale-105 transition-transform duration-500">
                </div>
                @endif
            @endforeach
        </div>

        {{-- Lightbox --}}
        <div x-show="lightbox" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
             @keydown.escape.window="lightbox = false"
             @click.self="lightbox = false">

            <button @click="lightbox = false" class="absolute top-6 right-6 text-white/70 hover:text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            @if(count($images) > 1)
            <button @click="current = current > 0 ? current - 1 : {{ count($images) - 1 }}"
                    class="absolute left-4 text-white/70 hover:text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="current = current < {{ count($images) - 1 }} ? current + 1 : 0"
                    class="absolute right-4 text-white/70 hover:text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            @endif

            @foreach($images as $i => $img)
                <img x-show="current === {{ $i }}"
                     src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}"
                     alt="" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg">
            @endforeach
        </div>
        @endif
    </div>
</section>
