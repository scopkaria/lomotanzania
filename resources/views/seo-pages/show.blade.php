@extends('layouts.app')

@section('seo_title', $page->meta_title ?: $page->translated('title'))

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "{{ $page->translated('title') }}",
    "description": "{{ $page->meta_description ?: Str::limit(strip_tags($page->translated('intro_content')), 160) }}",
    "url": "{{ url()->current() }}",
    "mainEntity": {
        "@@type": "ItemList",
        "numberOfItems": {{ $safaris->count() }},
        "itemListElement": [
            @foreach($safaris as $safari)
            {
                "@@type": "ListItem",
                "position": {{ $loop->iteration }},
                "url": "{{ route('safaris.show', $safari->slug) }}",
                "name": "{{ $safari->translated('title') }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    },
    "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
            { "@@type": "ListItem", "position": 2, "name": "Safaris", "item": "{{ route('safaris.index') }}" },
            { "@@type": "ListItem", "position": 3, "name": "{{ $page->translated('title') }}" }
        ]
    }
}
</script>
@endpush

@section('content')
{{-- Hero --}}
<section class="relative bg-[#083321] py-20 sm:py-28">
    <div class="absolute inset-0 opacity-20"
         style="background-image: url('{{ $page->featured_image ? asset('storage/' . $page->featured_image) : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=1400&q=80' }}'); background-size: cover; background-position: center;">
    </div>
    <div class="relative mx-auto max-w-5xl px-4 text-center">
        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center justify-center gap-2 text-sm text-white/60">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <span>/</span>
            <a href="{{ route('safaris.index') }}" class="hover:text-white transition">Safaris</a>
            <span>/</span>
            <span class="text-white/80">{{ $page->translated('title') }}</span>
        </nav>

        <h1 class="font-heading text-3xl font-bold text-white sm:text-4xl lg:text-5xl">{{ $page->translated('title') }}</h1>

        @if($page->translated('intro_content'))
            <p class="mx-auto mt-6 max-w-2xl text-lg text-white/75 leading-relaxed">
                {{ $page->translated('intro_content') }}
            </p>
        @endif

        <div class="mt-6 flex items-center justify-center gap-4 text-sm text-white/60">
            <span>{{ $safaris->count() }} {{ Str::plural('safari', $safaris->count()) }} available</span>
        </div>
    </div>
</section>

{{-- Body Content --}}
@if($page->translated('body_content'))
<section class="mx-auto max-w-4xl px-4 py-12">
    <div class="prose prose-lg max-w-none">
        {!! $page->translated('body_content') !!}
    </div>
</section>
@endif

{{-- Safari Listings --}}
<section class="mx-auto max-w-7xl px-4 py-12">
    @if($safaris->isNotEmpty())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($safaris as $safari)
                <a href="{{ route('safaris.show', $safari->slug) }}" class="group overflow-hidden rounded-xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="aspect-[4/3] overflow-hidden">
                        @if($safari->featured_image)
                            <img src="{{ asset('storage/' . $safari->featured_image) }}"
                                 alt="{{ $safari->translated('title') }}"
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gray-100">
                                <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25c0 .828.672 1.5 1.5 1.5z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="font-heading text-lg font-bold text-brand-dark group-hover:text-[#083321] transition">
                            {{ $safari->translated('title') }}
                        </h3>
                        @if($safari->duration)
                            <p class="mt-1 text-sm text-gray-500">{{ $safari->duration }}</p>
                        @endif
                        @if($safari->translated('short_description'))
                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $safari->translated('short_description') }}</p>
                        @endif
                        <div class="mt-4 flex items-center justify-between">
                            @if($safari->price)
                                <span class="text-lg font-bold text-[#083321]">
                                    ${{ number_format($safari->price) }}
                                </span>
                            @endif
                            <span class="text-sm font-medium text-[#FEBC11] group-hover:underline">View Details →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
            <p class="text-gray-500">No safari packages currently match this criteria. Check back soon!</p>
            <a href="{{ route('safaris.index') }}" class="mt-4 inline-block text-[#083321] font-semibold hover:underline">Browse all safaris →</a>
        </div>
    @endif
</section>

{{-- Internal Links --}}
<section class="bg-gray-50 py-12">
    <div class="mx-auto max-w-5xl px-4">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Explore More Safari Experiences</h2>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $relatedPages = \App\Models\SeoPage::published()
                    ->where('id', '!=', $page->id)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();
            @endphp
            @foreach($relatedPages as $rp)
                <a href="{{ route('seo.page', $rp->slug) }}" class="rounded-lg bg-white px-4 py-3 text-sm font-medium text-[#083321] shadow-sm transition hover:shadow-md hover:bg-[#083321] hover:text-white">
                    {{ $rp->translated('title') }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
