<?php

namespace App\Http\Controllers;

use App\Models\SeoMarket;
use App\Models\SeoPage;
use App\Traits\HasSeoData;

class SeoPageController extends Controller
{
    use HasSeoData;

    /**
     * Show a programmatic SEO page.
     */
    public function show(string $locale, string $slug)
    {
        $page = SeoPage::where('slug', $slug)->published()->firstOrFail();
        $page->increment('views');

        $safaris = $page->getMatchingSafaris(12);

        $seoData = [
            'seoTitle' => $page->meta_title ?: $page->translated('title'),
            'seoDescription' => $page->meta_description,
            'seoKeywords' => $page->meta_keywords,
            'seoOgImage' => $page->featured_image ? asset('storage/' . $page->featured_image) : null,
        ];

        return view('seo-pages.show', compact('page', 'safaris') + $seoData);
    }

    /**
     * Show a GEO market page.
     */
    public function market(string $locale, string $slug)
    {
        $market = SeoMarket::where('slug', $slug)->published()->firstOrFail();

        $safaris = $market->getSafaris(12);

        $seoData = [
            'seoTitle' => $market->meta_title ?: $market->translated('title'),
            'seoDescription' => $market->meta_description,
            'seoOgImage' => $market->featured_image ? asset('storage/' . $market->featured_image) : null,
        ];

        return view('seo-pages.market', compact('market', 'safaris') + $seoData);
    }
}
