{{-- Map Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale);
    $embedUrl = $section->data['embed_url'] ?? '';
    $lat = $section->data['latitude'] ?? '';
    $lng = $section->data['longitude'] ?? '';
    $zoom = $section->data['zoom'] ?? 10;
@endphp

<section class="py-16 md:py-24 bg-[#F9F7F3]">
    <div class="max-w-7xl mx-auto px-6">
        @if($heading)
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-[#131414] mb-10 text-center">
                {{ $heading }}
            </h2>
        @endif

        <div class="rounded-xl overflow-hidden shadow-lg aspect-video">
            @if($embedUrl)
                <iframe src="{{ $embedUrl }}"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
            @elseif($lat && $lng)
                <iframe src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&z={{ $zoom }}&output=embed"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                    <p>Map location not configured</p>
                </div>
            @endif
        </div>
    </div>
</section>
