{{-- Latest Blog Section --}}
@php
    $locale = app()->getLocale();
    $heading = $section->getData('heading', $locale, 'From Our Blog');
    $subheading = $section->getData('subheading', $locale);
    $posts = $sectionData['posts'] ?? collect();
@endphp

<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14 reveal">
            <p class="text-xs font-semibold tracking-[0.3em] uppercase text-[#FEBC11] mb-3">Blog</p>
            <h2 class="font-heading text-3xl md:text-4xl lg:text-5xl font-bold text-[#131414] leading-tight">
                {{ $heading }}
            </h2>
            @if($subheading)
            <p class="mt-4 text-base text-[#131414]/50 max-w-lg mx-auto leading-relaxed">{{ $subheading }}</p>
            @endif
        </div>

        @if($posts->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            @php
                $postTitle = is_array($post->title) ? ($post->title[$locale] ?? $post->title['en'] ?? '') : $post->title;
                $postExcerpt = is_array($post->excerpt) ? ($post->excerpt[$locale] ?? $post->excerpt['en'] ?? '') : ($post->excerpt ?? '');
            @endphp
            <a href="{{ route('blog.show', $post->slug) }}"
               class="group bg-white rounded-xl overflow-hidden shadow-sm hover:-translate-y-1.5 hover:shadow-md transition-all duration-300 reveal"
               style="transition-delay: {{ ($loop->index + 1) * 100 }}ms;">
                <div class="overflow-hidden">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                             alt="{{ $postTitle }}" loading="lazy"
                             class="w-full h-48 object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-[#083321]/80 to-[#083321]/40 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5"/></svg>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->published_at)
                    <p class="text-xs text-[#131414]/40 mb-2">{{ $post->published_at->format('M d, Y') }}</p>
                    @endif
                    <h3 class="font-heading text-lg font-bold text-[#131414] mb-2 group-hover:text-[#083321] transition-colors">
                        {{ $postTitle }}
                    </h3>
                    @if($postExcerpt)
                    <p class="text-sm text-[#131414]/50 leading-relaxed">{{ Str::limit($postExcerpt, 120) }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-10 reveal">
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-[#083321] hover:text-[#FEBC11] transition-colors">
                View All Posts
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</section>
