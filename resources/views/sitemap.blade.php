{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">

    {{-- Homepage --}}
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale) }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale) }}"/>
        @endforeach
    </url>
    @endforeach

    {{-- Safaris listing --}}
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/safaris') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/safaris') }}"/>
        @endforeach
    </url>
    @endforeach

    {{-- Safari detail pages --}}
    @foreach($safaris as $safari)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/safaris/' . $safari->slug) }}</loc>
        <lastmod>{{ $safari->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/safaris/' . $safari->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

    {{-- Destination pages --}}
    @foreach($destinations as $destination)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/destinations/' . $destination->slug) }}</loc>
        <lastmod>{{ $destination->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/destinations/' . $destination->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

    {{-- Country pages --}}
    @foreach($countries as $country)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/countries/' . $country->slug) }}</loc>
        <lastmod>{{ $country->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/countries/' . $country->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

    {{-- Blog posts --}}
    @foreach($posts as $post)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/blog/' . $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/blog/' . $post->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

    {{-- CMS pages --}}
    @foreach($pages as $page)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/page/' . $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/page/' . $page->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

    {{-- Blog index --}}
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/blog') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Programmatic SEO Pages --}}
    @foreach($seoPages as $seoPage)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/safaris/g/' . $seoPage->slug) }}</loc>
        <lastmod>{{ $seoPage->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/safaris/g/' . $seoPage->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

    {{-- GEO Market Pages --}}
    @foreach($seoMarkets as $market)
    @foreach($locales as $locale)
    <url>
        <loc>{{ url('/' . $locale . '/safaris/market/' . $market->slug) }}</loc>
        <lastmod>{{ $market->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        @foreach($locales as $altLocale)
        <xhtml:link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url('/' . $altLocale . '/safaris/market/' . $market->slug) }}"/>
        @endforeach
    </url>
    @endforeach
    @endforeach

</urlset>
