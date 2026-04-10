{{-- Destinations Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Explore Most Breathtaking Destinations');
    $subheading = $section->getData('subheading', $locale);
    $destinations = ($sectionData['destinations'] ?? collect())->take(3);
@endphp

<section class="py-20 md:py-28 bg-[#F9F7F3]">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="font-accent text-2xl md:text-3xl text-brand-gold mb-2">Destinations</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-brand-dark leading-heading tracking-safari">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-brand-dark/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($destinations as $destination)
            <a href="{{ route('destinations.index') }}"
               class="group relative rounded-xl overflow-hidden aspect-[2/3] block reveal"
               style="transition-delay: {{ ($loop->index + 1) * 100 }}ms;">
                @if($destination->featured_image)
                    <img src="{{ asset('storage/' . $destination->featured_image) }}"
                         alt="{{ $destination->translated('name') }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-[#083321] to-[#083321]/60"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent transition-colors duration-300 group-hover:from-black/90"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <h3 class="font-heading text-2xl font-bold text-white uppercase tracking-wide mb-1.5">{{ $destination->translated('name') }}</h3>
                    @if($destination->country)
                    <p class="text-sm text-white/85">{{ $destination->country->name }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-10 reveal">
            <a href="{{ route('destinations.index') }}"
               class="inline-flex items-center gap-2 px-8 py-3 bg-brand-dark text-white text-xs font-semibold uppercase tracking-wider hover:bg-[#FEBC11] hover:text-brand-dark transition-all duration-200">
                View All Destinations
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
