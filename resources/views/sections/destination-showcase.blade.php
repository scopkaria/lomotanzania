{{-- Destination Showcase Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Top Destinations');
    $subheading = $section->getData('subheading', $locale);
    $destinations = $sectionData['destinations'] ?? collect();
@endphp

<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-[#FEBC11] mb-3">Destinations</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-[#131414] leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-[#131414]/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if($destinations->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($destinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}"
               class="group relative rounded-2xl overflow-hidden aspect-[4/3] block reveal shadow-sm hover:shadow-xl transition-all duration-300"
               style="transition-delay: {{ ($loop->index + 1) * 100 }}ms;">
                @if($destination->featured_image)
                    <img src="{{ asset('storage/' . $destination->featured_image) }}"
                         alt="{{ $destination->translated('name') }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <h3 class="font-heading text-xl font-bold mb-1">{{ $destination->translated('name') }}</h3>
                    @if($destination->country)
                        <p class="text-sm text-white/90">{{ $destination->country->name }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
            <p class="text-center text-gray-400">No destinations available.</p>
        @endif
    </div>
</section>
