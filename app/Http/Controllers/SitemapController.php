<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Destination;
use App\Models\Page;
use App\Models\Post;
use App\Models\SafariPackage;
use App\Models\SeoMarket;
use App\Models\SeoPage;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $safaris = SafariPackage::where('status', 'published')->select('slug', 'updated_at')->get();
        $destinations = Destination::select('slug', 'updated_at')->get();
        $countries = Country::select('slug', 'updated_at')->get();
        $posts = Post::where('status', 'published')->select('slug', 'updated_at')->get();
        $pages = Page::where('status', 'published')->select('slug', 'updated_at')->get();
        $seoPages = SeoPage::where('is_published', true)->select('slug', 'updated_at')->get();
        $seoMarkets = SeoMarket::where('is_published', true)->select('slug', 'updated_at')->get();

        $locales = ['en', 'fr', 'de', 'es'];

        $content = view('sitemap', compact(
            'safaris', 'destinations', 'countries', 'posts', 'pages',
            'seoPages', 'seoMarkets', 'locales'
        ))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
