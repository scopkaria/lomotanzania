@extends('layouts.app')

@section('title', $post->translatedMeta('meta_title') ?: $post->translatedTitle())

@push('jsonld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Article",
    "headline": "{{ $post->translatedTitle() }}",
    "description": "{{ Str::limit(strip_tags($post->translatedExcerpt() ?: $post->translatedContent()), 200) }}",
    "url": "{{ url()->current() }}",
    @if($post->featured_image)
    "image": {
        "@@type": "ImageObject",
        "url": "{{ asset('storage/' . $post->featured_image) }}",
        "width": 1200,
        "height": 630
    },
    @endif
    @if($post->published_at)
    "datePublished": "{{ $post->published_at->toIso8601String() }}",
    @endif
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    @if($post->author)
    "author": {
        "@@type": "Person",
        "name": "{{ $post->author->name }}"
    },
    @endif
    "publisher": {
        "@@type": "Organization",
        "name": "{{ $siteName ?? 'Lomo Tanzania Safari' }}",
        "url": "{{ url('/') }}"
    },
    "mainEntityOfPage": {
        "@@type": "WebPage",
        "@@id": "{{ url()->current() }}"
    },
    "wordCount": {{ str_word_count(strip_tags($post->translatedContent())) }},
    @if($post->category)
    "articleSection": "{{ $post->category->translatedName() }}",
    @endif
    @if($post->meta_keywords)
    "keywords": "{{ $post->meta_keywords }}",
    @endif
    "inLanguage": "{{ app()->getLocale() }}"
}
</script>

{{-- BreadcrumbList Schema --}}
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
            "name": "Blog",
            "item": "{{ route('blog.index') }}"
        },
        @if($post->category)
        {
            "@@type": "ListItem",
            "position": 3,
            "name": "{{ $post->category->translatedName() }}",
            "item": "{{ route('blog.index', ['category' => $post->category->slug]) }}"
        },
        {
            "@@type": "ListItem",
            "position": 4,
            "name": "{{ $post->translatedTitle() }}"
        }
        @else
        {
            "@@type": "ListItem",
            "position": 3,
            "name": "{{ $post->translatedTitle() }}"
        }
        @endif
    ]
}
</script>
@endpush

@push('styles')
<style>
    .blog-content { font-family: 'Inter', sans-serif; }
    .blog-content h2, .blog-content h3 { scroll-margin-top: 120px; }
    .blog-content h2 { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; margin: 2rem 0 1rem; color: #131414; }
    .blog-content h3 { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 600; margin: 1.75rem 0 0.75rem; color: #131414; }
    .blog-content p { margin-bottom: 1.25rem; line-height: 1.8; color: rgba(19,20,20,0.7); }
    .blog-content ul, .blog-content ol { margin-bottom: 1.25rem; padding-left: 1.5rem; color: rgba(19,20,20,0.7); }
    .blog-content li { margin-bottom: 0.5rem; line-height: 1.7; }
    .blog-content ul li { list-style: disc; }
    .blog-content ol li { list-style: decimal; }
    .blog-content blockquote { border-left: 4px solid #FEBC11; padding: 1rem 1.5rem; margin: 1.5rem 0; background: rgba(254,188,17,0.05); font-style: italic; color: rgba(19,20,20,0.6); }
    .blog-content img { display: block; width: 100%; max-width: 100%; height: auto; object-fit: cover; border-radius: 0.9rem; margin: 1.5rem 0; }
    .blog-content a { color: #083321; text-decoration: underline; text-underline-offset: 3px; }
    .blog-content a:hover { color: #FEBC11; }
    .toc-link { display: block; border-left: 2px solid transparent; padding: 0.35rem 0 0.35rem 0.75rem; color: rgba(19,20,20,0.75); transition: all 160ms ease; }
    .toc-link:hover { border-left-color: #FEBC11; color: #083321; background: rgba(8,51,33,0.04); }
    .toc-link-sub { margin-left: 0.75rem; font-size: 0.875rem; }
</style>
@endpush

@section('content')

{{-- â”€â”€ Hero / Featured Image â”€â”€ --}}
<section class="relative bg-brand-dark">
    <div class="relative aspect-[21/9] max-h-[480px] overflow-hidden">
        @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->translatedTitle() }}"
                 class="w-full h-full object-cover opacity-60">
        @else
            <div class="w-full h-full bg-gradient-to-br from-brand-green to-brand-dark"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-brand-dark/40 to-transparent"></div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 z-10 pb-12 pt-20 bg-gradient-to-t from-brand-dark to-transparent">
        <div class="max-w-4xl mx-auto px-6 text-center">
            @if($post->category)
                <span class="inline-block px-4 py-1.5 mb-5 text-[10px] font-bold uppercase tracking-[0.2em] bg-brand-gold text-brand-dark rounded-full">
                    {{ $post->category->translatedName() }}
                </span>
            @endif
            <h1 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5">
                {{ $post->translatedTitle() }}
            </h1>
            <div class="flex items-center justify-center gap-4 text-sm text-white/80">
                @if($post->author)
                    <span>{{ __('messages.by') }} {{ $post->author->name }}</span>
                    <span>&bull;</span>
                @endif
                @if($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('F j, Y') }}</time>
                    <span>&bull;</span>
                @endif
                <span>{{ $post->readingTime() }} {{ __('messages.min_read') }}</span>
            </div>

            <ol class="mt-5 flex flex-wrap items-center justify-center gap-1.5 text-xs text-white/70">
                <li><a href="{{ url('/') }}" class="hover:text-white transition">{{ __('messages.home') }}</a></li>
                <li>/</li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">{{ __('messages.blog') }}</a></li>
                @if($post->category)
                    <li>/</li>
                    <li><a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="hover:text-white transition">{{ $post->category->translatedName() }}</a></li>
                @endif
                <li>/</li>
                <li class="max-w-[220px] truncate text-white">{{ $post->translatedTitle() }}</li>
            </ol>
        </div>
    </div>
</section>

{{-- â”€â”€ Article Body â”€â”€ --}}
<article class="py-16 md:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid gap-10 lg:grid-cols-12">
            <aside class="lg:col-span-4">
                <div class="space-y-4 lg:sticky lg:top-28">
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-green transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        {{ __('messages.all_posts') }}
                    </a>

                    <div class="rounded-2xl border border-gray-200 bg-[#F9F7F3] p-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-gray-500">On this page</p>
                        <nav id="blog-toc" class="mt-3 space-y-1"></nav>
                        <p id="blog-toc-empty" class="mt-3 text-sm text-gray-400">Table of contents will appear when the article has headings.</p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5" x-data="shareLinks()">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-gray-500">{{ __('messages.share') }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <a :href="fbUrl" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-blue-100 flex items-center justify-center transition" title="{{ __('messages.share_facebook') }}">
                                <svg class="w-4 h-4 text-gray-500 hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <a :href="twUrl" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-sky-100 flex items-center justify-center transition" title="{{ __('messages.share_x') }}">
                                <svg class="w-4 h-4 text-gray-500 hover:text-sky-600" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a :href="waUrl" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-green-100 flex items-center justify-center transition" title="{{ __('messages.share_whatsapp') }}">
                                <svg class="w-4 h-4 text-gray-500 hover:text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                            <button @click="copyLink()" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-brand-gold/20 flex items-center justify-center transition" title="{{ __('messages.copy_link') }}">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-8">
                <div id="blog-article-content" class="blog-content text-base md:text-[17px]">
                    {!! $post->translatedContent() !!}
                </div>

                @if($post->author)
                    <div class="mt-14 pt-8 border-t border-gray-100 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-brand-green/10 flex items-center justify-center text-lg font-bold text-brand-green">
                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $post->author->name }}</p>
                            <p class="text-sm text-gray-400">{{ __('messages.author') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</article>

{{-- â”€â”€ Related Posts â”€â”€ --}}
@if($related->count())
<section class="py-16 md:py-20 bg-brand-light">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="font-heading text-2xl md:text-3xl font-bold text-brand-dark text-center mb-10">{{ __('messages.related_articles') }}</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($related as $rel)
                <article class="bg-white rounded-xl shadow-sm overflow-hidden group hover:shadow-md transition-shadow duration-300">
                    <a href="{{ route('blog.show', ['slug' => $rel->slug]) }}" class="block">
                        <div class="relative aspect-[16/10] overflow-hidden">
                            @if($rel->featured_image)
                                <img src="{{ asset('storage/' . $rel->featured_image) }}" alt="{{ $rel->translatedTitle() }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-brand-green/20 to-brand-gold/20"></div>
                            @endif
                            @if($rel->category)
                                <span class="absolute top-3 left-3 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.15em] bg-brand-gold text-brand-dark rounded-full">
                                    {{ $rel->category->translatedName() }}
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="p-5">
                        <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                            @if($rel->published_at)
                                <time datetime="{{ $rel->published_at->toDateString() }}">{{ $rel->published_at->format('M d, Y') }}</time>
                            @endif
                            <span>&bull; {{ $rel->readingTime() }} min</span>
                        </div>
                        <a href="{{ route('blog.show', ['slug' => $rel->slug]) }}">
                            <h3 class="font-heading text-lg font-bold text-brand-dark leading-snug group-hover:text-brand-green transition-colors">
                                {{ $rel->translatedTitle() }}
                            </h3>
                        </a>
                        @if($rel->translatedExcerpt())
                            <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $rel->translatedExcerpt() }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- â”€â”€ CTA â”€â”€ --}}
<section class="py-16 md:py-20 bg-brand-green text-center">
    <div class="max-w-2xl mx-auto px-6">
        <h2 class="font-heading text-2xl md:text-3xl font-bold text-white mb-4">{{ __('messages.ready_for_adventure') }}</h2>
        <p class="text-white/85 mb-8">{{ __('messages.adventure_subtitle') }}</p>
        <a href="{{ route('plan-safari') }}"
           class="inline-block px-8 py-4 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wider rounded-sm hover:bg-white transition-all duration-300">
            {{ __('messages.start_planning') }}
        </a>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const article = document.getElementById('blog-article-content');
    const toc = document.getElementById('blog-toc');
    const emptyState = document.getElementById('blog-toc-empty');

    if (!article || !toc) return;

    const headings = Array.from(article.querySelectorAll('h2, h3')).filter((heading) => heading.textContent.trim().length > 0);

    if (!headings.length) return;

    emptyState?.classList.add('hidden');

    headings.forEach((heading, index) => {
        if (!heading.id) {
            heading.id = `article-section-${index + 1}`;
        }

        const link = document.createElement('a');
        link.href = `#${heading.id}`;
        link.textContent = heading.textContent.trim();
        link.className = `toc-link ${heading.tagName === 'H3' ? 'toc-link-sub' : ''}`;
        toc.appendChild(link);
    });
});

function shareLinks() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    return {
        fbUrl: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        twUrl: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
        waUrl: `https://wa.me/?text=${title}%20${url}`,
        copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert({!! json_encode(__('messages.link_copied')) !!});
            });
        }
    };
}
</script>

@endsection
