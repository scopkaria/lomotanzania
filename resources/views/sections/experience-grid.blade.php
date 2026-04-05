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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $i => $item)
            @php
                $title = is_array($item['title'] ?? '') ? ($item['title'][$locale] ?? $item['title']['en'] ?? '') : ($item['title'] ?? '');
                $desc  = is_array($item['description'] ?? '') ? ($item['description'][$locale] ?? $item['description']['en'] ?? '') : ($item['description'] ?? '');
                $image = $item['image'] ?? null;
                $link  = $item['link'] ?? null;
                $icon  = $item['icon'] ?? 'compass';
            @endphp
            <div class="group relative rounded-2xl overflow-hidden aspect-[4/3] reveal"
                 style="transition-delay: {{ ($i + 1) * 100 }}ms;">

                @if($image)
                    <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}"
                         alt="{{ $title }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-[#083321] to-[#083321]/60"></div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent transition-colors duration-300 group-hover:from-black/90"></div>

                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                    <div class="w-10 h-10 mb-3 rounded-lg bg-[#FEBC11]/20 flex items-center justify-center backdrop-blur-sm">
                        @switch($icon)
                            @case('diamond')
                                <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z"/></svg>
                                @break
                            @case('heart')
                                <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                                @break
                            @case('users')
                                <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                @break
                            @case('globe')
                                <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582"/></svg>
                                @break
                            @case('mountain')
                                <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747"/></svg>
                        @endswitch
                    </div>
                    <h3 class="font-heading text-xl font-bold text-white mb-2">{{ $title }}</h3>
                    @if($desc)
                        <p class="text-sm text-white/85 leading-relaxed">{{ $desc }}</p>
                    @endif
                </div>

                @if($link)
                    <a href="{{ $link }}" class="absolute inset-0 z-10" aria-label="{{ $title }}"></a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
