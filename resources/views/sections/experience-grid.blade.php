{{-- Experience Grid Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'Our Experiences');
    $subheading = $section->getData('subheading', $locale);
    $items = $section->data['items'] ?? [];
@endphp

<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-[#FEBC11] mb-3">Experiences</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-[#131414] leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
                <p class="mt-4 text-base text-[#131414]/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if(count($items))
        <div class="space-y-8">
            @foreach($items as $i => $item)
            @php
                $title = is_array($item['title'] ?? '') ? ($item['title'][$locale] ?? $item['title']['en'] ?? '') : ($item['title'] ?? '');
                $desc  = is_array($item['description'] ?? '') ? ($item['description'][$locale] ?? $item['description']['en'] ?? '') : ($item['description'] ?? '');
                $image = $item['image'] ?? null;
                $link  = $item['link'] ?? null;
                $icon  = $item['icon'] ?? 'compass';
                $reversed = $i % 2 !== 0;
            @endphp
            <div class="group grid grid-cols-1 md:grid-cols-2 gap-6 items-center rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm hover:shadow-md transition reveal"
                 style="transition-delay: {{ ($i + 1) * 100 }}ms;">

                {{-- Image side --}}
                <div class="{{ $reversed ? 'md:order-2' : '' }}">
                    @if($image)
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}"
                                 alt="{{ $title }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        </div>
                    @else
                        <div class="aspect-[4/3] bg-gradient-to-br from-[#083321] to-[#083321]/60 flex items-center justify-center">
                            <svg class="w-16 h-16 text-[#FEBC11]/40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Text side --}}
                <div class="p-6 md:p-8 {{ $reversed ? 'md:order-1' : '' }}">
                    <h3 class="font-heading text-xl md:text-2xl font-bold text-[#131414] mb-3">{{ $title }}</h3>
                    @if($desc)
                        <p class="text-sm text-[#131414]/60 leading-relaxed mb-4">{{ $desc }}</p>
                    @endif
                    @if($link)
                        <a href="{{ $link }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#083321] hover:text-[#FEBC11] transition">
                            Explore →
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
