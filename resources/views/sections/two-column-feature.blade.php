{{-- Two Column Feature Section --}}
@php
    $locale = app()->getLocale();
    $sectionHeading = $section->getData('heading', $locale);
    $columns = $section->data['columns'] ?? [];
    $bgColor = $section->data['bg_color'] ?? '#F9F7F3';
@endphp

<section class="py-20 md:py-28" style="background-color: {{ $bgColor }};">
    <div class="max-w-7xl mx-auto px-6">

        @if($sectionHeading)
        <div class="text-center mb-14 reveal">
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-brand-dark leading-heading tracking-safari">
                {{ $sectionHeading }}
            </h2>
        </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            @foreach($columns as $i => $col)
            @php
                $title = is_array($col['title'] ?? '') ? ($col['title'][$locale] ?? $col['title']['en'] ?? '') : ($col['title'] ?? '');
                $body  = is_array($col['body'] ?? '')  ? ($col['body'][$locale] ?? $col['body']['en'] ?? '')  : ($col['body'] ?? '');
                $icon  = $col['icon'] ?? 'star';
            @endphp
            <div class="relative p-10 md:p-12 rounded-2xl bg-white border border-gray-100 hover:border-[#FEBC11]/30 hover:shadow-xl transition-all duration-500 reveal"
                 style="transition-delay: {{ ($i + 1) * 150 }}ms;">

                {{-- Decorative accent --}}
                <div class="absolute top-0 left-8 w-12 h-1 bg-[#FEBC11] rounded-b"></div>

                <div class="w-14 h-14 mb-6 rounded-xl bg-[#083321]/5 flex items-center justify-center">
                    @if($icon === 'target')
                        <svg class="w-6 h-6 text-[#083321]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75H6A2.25 2.25 0 003.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0120.25 6v1.5m0 9V18A2.25 2.25 0 0118 20.25h-1.5m-9 0H6A2.25 2.25 0 013.75 18v-1.5M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    @elseif($icon === 'eye')
                        <svg class="w-6 h-6 text-[#083321]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    @else
                        <svg class="w-6 h-6 text-[#083321]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    @endif
                </div>

                @if($title)
                    <h3 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark mb-4">{{ $title }}</h3>
                @endif
                @if($body)
                    <div class="prose prose-base max-w-none text-brand-dark/60 leading-relaxed">
                        {!! $body !!}
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
