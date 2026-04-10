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
    .blog-content { font-family: 'Lato', sans-serif; font-size: 1.0625rem; line-height: 1.85; color: rgba(45,45,45,0.85); }
    .blog-content h2, .blog-content h3 { scroll-margin-top: 120px; }
    .blog-content h2 { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.75rem; font-weight: 700; margin: 2.5rem 0 1rem; color: #131414; line-height: 1.2; letter-spacing: 0.01em; }
    .blog-content h3 { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.375rem; font-weight: 600; margin: 2rem 0 0.75rem; color: #131414; line-height: 1.25; letter-spacing: 0.01em; }
    .blog-content p { margin-bottom: 1.5rem; line-height: 1.85; color: rgba(45,45,45,0.82); }
    .blog-content ul, .blog-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; color: rgba(45,45,45,0.82); }
    .blog-content li { margin-bottom: 0.5rem; line-height: 1.75; }
    .blog-content ul li { list-style: disc; }
    .blog-content ol li { list-style: decimal; }
    .blog-content blockquote { border-left: 4px solid #FEBC11; padding: 1.25rem 1.5rem; margin: 2rem 0; background: rgba(254,188,17,0.04); font-style: italic; color: rgba(45,45,45,0.65); font-size: 1.125rem; line-height: 1.7; }
    .blog-content img { display: block; width: 100%; max-width: 100%; height: auto; object-fit: cover; border-radius: 0.9rem; margin: 2rem 0; }
    .blog-content a { color: #083321; text-decoration: underline; text-underline-offset: 3px; }
    .blog-content a:hover { color: #FEBC11; }
    @media (min-width: 768px) {
        .blog-content { font-size: 1.125rem; }
        .blog-content h2 { font-size: 2.125rem; }
        .blog-content h3 { font-size: 1.5rem; }
    }
    .toc-link { display: block; border-left: 2px solid transparent; padding: 0.35rem 0 0.35rem 0.75rem; color: rgba(45,45,45,0.65); font-size: 0.9375rem; transition: all 160ms ease; }
    .toc-link:hover { border-left-color: #FEBC11; color: #083321; background: rgba(8,51,33,0.04); }
    .toc-link-sub { margin-left: 0.75rem; font-size: 0.875rem; }
</style>
@endpush

@section('content')

{{-- â”€â”€ Hero / Featured Image â”€â”€ --}}
<section class="relative bg-brand-dark">
    <div class="relative aspect-[16/7] min-h-[400px] md:min-h-[480px] lg:min-h-[540px] overflow-hidden">
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
                        <p class="text-kicker tracking-kicker uppercase text-gray-500">{{ __('messages.on_this_page') }}</p>
                        <nav id="blog-toc" class="mt-3 space-y-1"></nav>
                        <p id="blog-toc-empty" class="mt-3 text-sm text-gray-400">{{ __('messages.toc_empty') }}</p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5" x-data="shareLinks()">
                        <p class="text-kicker tracking-kicker uppercase text-gray-500">{{ __('messages.share') }}</p>
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

                {{-- ── Comments Section ── --}}
                <div class="mt-14 pt-8 border-t border-gray-100" id="comments">
                    <h3 class="font-heading text-xl font-bold text-brand-dark mb-6">
                        {{ __('messages.comments') }}
                        @if(isset($comments) && $comments->count())
                            <span class="text-base font-normal text-gray-400">({{ $comments->count() }})</span>
                        @endif
                    </h3>

                    {{-- Approved Comments List --}}
                    @if(isset($comments) && $comments->count())
                        <div class="space-y-6 mb-10">
                            @foreach($comments as $comment)
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center text-sm font-bold text-brand-dark shrink-0">
                                        {{ strtoupper(substr($comment->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline gap-2">
                                            <span class="font-semibold text-gray-900 text-sm">{{ $comment->name }}</span>
                                            <time class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</time>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 leading-relaxed">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 mb-8">{{ __('messages.no_comments') }}</p>
                    @endif

                    {{-- Leave a Comment Form --}}
                    @if(session('comment_success'))
                        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                            {{ session('comment_success') }}
                        </div>
                    @endif

                    <div class="rounded-2xl border border-gray-200 bg-[#F9F7F3] p-6">
                        <h4 class="font-heading text-lg font-bold text-brand-dark mb-4">{{ __('messages.leave_comment') }}</h4>
                        <form method="POST" action="{{ route('blog.comment.store', ['slug' => $post->slug]) }}">
                            @csrf
                            {{-- Honeypot (hidden from real users) --}}
                            <div style="position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden;" aria-hidden="true">
                                <label for="website_url">Leave this empty</label>
                                <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="comment_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.full_name') }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="comment_name" value="{{ old('name') }}" required maxlength="100"
                                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-[#083321] focus:ring-[#083321]">
                                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comment_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="comment_email" value="{{ old('email') }}" required maxlength="150"
                                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-[#083321] focus:ring-[#083321]">
                                    <p class="text-xs text-gray-400 mt-1">{{ __('messages.email_not_published') }}</p>
                                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="comment_body" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.comments') }} <span class="text-red-500">*</span></label>
                                <textarea name="body" id="comment_body" rows="4" required maxlength="2000"
                                          class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-[#083321] focus:ring-[#083321]">{{ old('body') }}</textarea>
                                @error('body') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-brand-green text-white text-sm font-semibold rounded-lg hover:bg-brand-dark transition-colors duration-200">
                                {{ __('messages.submit_comment') }}
                            </button>
                        </form>
                    </div>
                </div>
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
                if (window.showLomoToast) {
                    window.showLomoToast({!! json_encode(__('messages.link_copied')) !!}, 'success', 2000);
                }
            });
        }
    };
}
</script>

@endsection
