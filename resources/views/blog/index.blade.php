@extends('layouts.app')

@section('title', __('messages.blog') . ' - ' . ($siteName ?? 'Lomo Tanzania Safari'))

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ url('/') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "Blog"
        }
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "{{ __('messages.blog') }}",
    "description": "Safari stories, travel guides, and Tanzania tips",
    "url": "{{ route('blog.index') }}",
    "isPartOf": {
        "@@type": "WebSite",
        "name": "{{ $siteName ?? 'Lomo Tanzania Safari' }}",
        "url": "{{ url('/') }}"
    }
}
</script>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative bg-brand-dark py-20 md:py-28 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <img src="https://images.unsplash.com/photo-1535941339077-2dd1c7963098?w=1600&h=500&fit=crop&q=60" alt=- class="w-full h-full object-cover">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/60 to-brand-dark/90"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <p class="text-xs font-semibold tracking-[0.3em] uppercase text-brand-gold mb-4">{{ __('messages.our_blog') }}</p>
        <h1 class="font-heading text-4xl md:text-5xl font-bold text-white leading-tight mb-4">{{ __('messages.stories_travel_tips') }}</h1>
        <p class="text-lg text-white/85 max-w-xl mx-auto mb-8">{{ __('messages.blog_subtitle') }}</p>

        {{-- Search bar --}}
        <form action="{{ route('blog.index') }}" method="GET" class="max-w-lg mx-auto relative">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-brand-dark/30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search articles..."
                   class="w-full pl-12 pr-4 py-3.5 rounded-lg bg-white/95 backdrop-blur text-sm text-brand-dark placeholder-brand-dark/40 border-0 focus:ring-2 focus:ring-brand-gold shadow-lg">
        </form>
    </div>
</section>

@if(isset($sections) && $sections->count())
    @include('partials.render-page-sections', ['sections' => $sections, 'sectionDataMap' => $sectionDataMap ?? []])
@endif

<section class="py-16 md:py-24 bg-brand-light">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Category filter pills --}}
        @if($categories->count())
            <div class="flex flex-wrap items-center gap-2 mb-8">
                <a href="{{ route('blog.index', request('search') ? ['search' => request('search')] : []) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-brand-green text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    {{ __('messages.all') }}
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('category') === $cat->slug ? 'bg-brand-green text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                        {{ $cat->translatedName() }}
                        <span class="ml-1 text-xs opacity-60">({{ $cat->posts_count }})</span>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Search results indicator --}}
        @if(request('search'))
            <div class="flex items-center gap-3 mb-8">
                <p class="text-sm text-brand-dark/70">
                    Showing results for "<span class="font-semibold text-brand-dark">{{ request('search') }}</span>"
                    <span class="text-brand-dark/40">({{ $posts->total() }} {{ Str::plural('article', $posts->total()) }})</span>
                </p>
                <a href="{{ route('blog.index', request('category') ? ['category' => request('category')] : []) }}"
                   class="text-xs text-brand-gold hover:underline">Clear search</a>
            </div>
        @endif

        {{-- Posts grid --}}
        @if($posts->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-300">
                        <a href="{{ route('blog.show', ['slug' => $post->slug]) }}" class="block">
                            <div class="relative aspect-[16/10] overflow-hidden">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                         alt="{{ $post->translatedTitle() }}" loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-green/20 to-brand-gold/20 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-brand-green/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
                                    </div>
                                @endif
                                @if($post->category)
                                    <span class="absolute top-3 left-3 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-white/90 text-brand-green rounded-full backdrop-blur-sm">
                                        {{ $post->category->translatedName() }}
                                    </span>
                                @endif
                            </div>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-gray-400 mb-3">
                                @if($post->published_at)
                                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M d, Y') }}</time>
                                @endif
                                <span>&bull;</span>
                                <span>{{ $post->readingTime() }} {{ __('messages.min_read') }}</span>
                            </div>
                            <a href="{{ route('blog.show', ['slug' => $post->slug]) }}" class="block">
                                <h2 class="font-heading text-xl font-bold text-brand-dark leading-snug mb-2 group-hover:text-brand-green transition-colors">
                                    {{ $post->translatedTitle() }}
                                </h2>
                            </a>
                            @if($post->translatedExcerpt())
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">{{ $post->translatedExcerpt() }}</p>
                            @endif
                            <div class="mt-4 flex items-center gap-3">
                                @if($post->author)
                                    <div class="w-7 h-7 rounded-full bg-brand-green/10 flex items-center justify-center text-[10px] font-bold text-brand-green">
                                        {{ strtoupper(substr($post->author->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $post->author->name }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mt-12 flex justify-center">{{ $posts->links() }}</div>
            @endif
        @else
            <div class="text-center py-20">
                <p class="text-gray-400 text-lg">{{ __('messages.no_posts_found') }}</p>
            </div>
        @endif
    </div>
</section>

@endsection
