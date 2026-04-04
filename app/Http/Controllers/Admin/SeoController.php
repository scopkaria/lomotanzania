<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorProfile;
use App\Models\SeoImageMeta;
use App\Models\SeoKeyword;
use App\Models\SeoLinkRule;
use App\Models\SeoMarket;
use App\Models\SeoMeta;
use App\Models\SeoPage;
use App\Models\SeoRankAlert;
use App\Models\SeoRanking;
use App\Models\Accommodation;
use App\Models\Category;
use App\Models\SafariPackage;
use App\Models\Destination;
use App\Models\Country;
use App\Models\Page;
use App\Models\Post;
use App\Models\TourType;
use App\Services\ContentOptimizer;
use App\Services\GeoMarketGenerator;
use App\Services\ImageSeoService;
use App\Services\InternalLinker;
use App\Services\SeoAnalyzer;
use App\Services\SeoPageGenerator;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    /**
     * SEO Dashboard — overview of all content scores and rankings.
     */
    public function dashboard()
    {
        // Content scores overview
        $models = [
            'Safaris'        => SafariPackage::class,
            'Destinations'   => Destination::class,
            'Countries'      => Country::class,
            'Pages'          => Page::class,
            'Blog Posts'     => Post::class,
            'Tour Types'     => TourType::class,
            'Categories'     => Category::class,
            'Accommodations' => Accommodation::class,
        ];

        $scoreOverview = [];
        foreach ($models as $label => $class) {
            $seoMetas = SeoMeta::where('seoable_type', $class)->where('locale', 'en')->get();
            $scoreOverview[$label] = [
                'total'      => $class::count(),
                'analyzed'   => $seoMetas->count(),
                'avg_score'  => round($seoMetas->avg('seo_score') ?? 0),
                'good'       => $seoMetas->where('seo_score', '>=', 71)->count(),
                'needs_work' => $seoMetas->whereBetween('seo_score', [41, 70])->count(),
                'poor'       => $seoMetas->where('seo_score', '<', 41)->count(),
            ];
        }

        // Rank tracking
        $rankings = SeoRanking::orderBy('position')->get();
        $topKeywords = $rankings->where('position', '<=', 10);
        $droppingKeywords = $rankings->filter(fn($r) => $r->trend === 'dropped');
        $improvingKeywords = $rankings->filter(fn($r) => $r->trend === 'improved');

        // Items needing attention (lowest scores)
        $lowScoreItems = SeoMeta::where('locale', 'en')
            ->where('seo_score', '<', 50)
            ->with('seoable')
            ->orderBy('seo_score')
            ->limit(10)
            ->get();

        return view('admin.seo.dashboard', compact(
            'scoreOverview', 'rankings', 'topKeywords',
            'droppingKeywords', 'improvingKeywords', 'lowScoreItems'
        ));
    }

    /**
     * Analyze all content models and update SEO scores.
     */
    public function analyzeAll(SeoAnalyzer $analyzer)
    {
        $models = [
            'safari'        => SafariPackage::class,
            'destination'   => Destination::class,
            'country'       => Country::class,
            'page'          => Page::class,
            'post'          => Post::class,
            'tour_type'     => TourType::class,
            'category'      => Category::class,
            'accommodation' => Accommodation::class,
        ];

        $totalAnalyzed = 0;

        foreach ($models as $type => $class) {
            foreach ($class::all() as $item) {
                $data = $this->extractAnalysisData($item, $type);
                $result = $analyzer->analyze($data);

                $item->seoMetas()->updateOrCreate(
                    ['locale' => 'en'],
                    [
                        'seo_score'         => $result['score'],
                        'readability_score'  => $result['readability_score'] ?? null,
                        'analysis_data'      => $result,
                        'last_analyzed_at'   => now(),
                    ]
                );

                $totalAnalyzed++;
            }
        }

        return redirect()->route('admin.seo.dashboard')
            ->with('success', "Analyzed {$totalAnalyzed} items successfully.");
    }

    /**
     * Extract SEO-relevant data from a model instance.
     */
    private function extractAnalysisData($item, string $type): array
    {
        $existingMeta = $item->seoMetas()->where('locale', 'en')->first();

        $title = $existingMeta->meta_title ?? $item->title ?? $item->name ?? '';
        if (is_array($title)) {
            $title = $title['en'] ?? '';
        }

        $content = $item->description ?? '';
        if (is_array($content)) {
            $content = $content['en'] ?? '';
        }
        if ($type === 'safari' && !empty($item->full_description)) {
            $full = is_array($item->full_description) ? ($item->full_description['en'] ?? '') : $item->full_description;
            $content .= ' ' . $full;
        }

        return [
            'title'            => $title,
            'meta_title'       => $existingMeta->meta_title ?? $title,
            'meta_description' => $existingMeta->meta_description ?? '',
            'content'          => $content,
            'slug'             => $item->slug ?? '',
            'focus_keyword'    => $existingMeta->focus_keyword ?? '',
        ];
    }

    /**
     * AJAX: Analyze content and return SEO score.
     */
    public function analyze(Request $request, SeoAnalyzer $analyzer)
    {
        $data = $request->validate([
            'title'            => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'slug'             => 'nullable|string|max:255',
            'focus_keyword'    => 'nullable|string|max:255',
        ]);

        $result = $analyzer->analyze($data);

        return response()->json($result);
    }

    /**
     * AJAX: Optimize content and return suggestions.
     */
    public function optimize(Request $request, ContentOptimizer $optimizer)
    {
        $data = $request->validate([
            'title'         => 'nullable|string|max:500',
            'content'       => 'nullable|string',
            'focus_keyword' => 'nullable|string|max:255',
        ]);

        $result = $optimizer->optimize($data);

        return response()->json($result);
    }

    /**
     * Save SEO analysis results for a model.
     */
    public function saveAnalysis(Request $request)
    {
        $data = $request->validate([
            'model_type'    => 'required|string',
            'model_id'      => 'required|integer',
            'locale'        => 'nullable|string|max:5',
            'focus_keyword' => 'nullable|string|max:255',
            'seo_score'     => 'nullable|integer|min:0|max:100',
            'readability_score' => 'nullable|integer|min:0|max:100',
        ]);

        // Resolve model class from short name
        $modelMap = [
            'safari'        => SafariPackage::class,
            'destination'   => Destination::class,
            'country'       => Country::class,
            'page'          => Page::class,
            'post'          => Post::class,
            'tour_type'     => TourType::class,
            'category'      => Category::class,
            'accommodation' => Accommodation::class,
        ];

        $modelClass = $modelMap[$data['model_type']] ?? null;
        if (!$modelClass) {
            return response()->json(['error' => 'Invalid model type'], 422);
        }

        $seo = SeoMeta::updateOrCreate(
            [
                'seoable_type' => $modelClass,
                'seoable_id'   => $data['model_id'],
                'locale'       => $data['locale'] ?? 'en',
            ],
            [
                'focus_keyword'     => $data['focus_keyword'] ?? null,
                'seo_score'         => $data['seo_score'] ?? 0,
                'readability_score' => $data['readability_score'] ?? 0,
                'last_analyzed_at'  => now(),
            ]
        );

        return response()->json(['success' => true, 'seo_meta' => $seo]);
    }

    /**
     * Rankings management.
     */
    public function rankings()
    {
        $rankings = SeoRanking::orderBy('position')->get();
        return view('admin.seo.rankings', compact('rankings'));
    }

    /**
     * Add a keyword to track.
     */
    public function addKeyword(Request $request)
    {
        $data = $request->validate([
            'keyword' => 'required|string|max:255',
            'url'     => 'required|string|max:500',
            'locale'  => 'nullable|string|max:5',
        ]);

        $ranking = SeoRanking::create([
            'keyword'  => $data['keyword'],
            'url'      => $data['url'],
            'locale'   => $data['locale'] ?? 'en',
            'position' => null,
            'history'  => [],
        ]);

        return redirect()->route('admin.seo.rankings')
            ->with('success', "Tracking keyword: \"{$data['keyword']}\"");
    }

    /**
     * Remove a tracked keyword.
     */
    public function removeKeyword(SeoRanking $ranking)
    {
        $ranking->delete();
        return redirect()->route('admin.seo.rankings')
            ->with('success', 'Keyword removed from tracking.');
    }

    // ═══════════════════════════════════════════
    // KEYWORD STRATEGY ENGINE
    // ═══════════════════════════════════════════

    public function keywords()
    {
        $keywords = SeoKeyword::orderByDesc('priority')->paginate(50);
        $intents = ['informational', 'transactional', 'local', 'navigational'];
        return view('admin.seo.keywords', compact('keywords', 'intents'));
    }

    public function storeKeyword(Request $request)
    {
        $data = $request->validate([
            'keyword'    => 'required|string|max:255',
            'intent'     => 'required|in:informational,transactional,local,navigational',
            'volume'     => 'nullable|integer|min:0',
            'difficulty' => 'nullable|integer|min:0|max:100',
            'target_url' => 'nullable|string|max:500',
            'country'    => 'nullable|string|max:50',
            'group'      => 'nullable|string|max:100',
            'priority'   => 'nullable|integer|min:0|max:100',
        ]);

        SeoKeyword::create($data);

        return redirect()->route('admin.seo.keywords')
            ->with('success', "Keyword \"{$data['keyword']}\" added.");
    }

    public function destroyKeyword(SeoKeyword $keyword)
    {
        $keyword->delete();
        return redirect()->route('admin.seo.keywords')
            ->with('success', 'Keyword removed.');
    }

    // ═══════════════════════════════════════════
    // INTERNAL LINKING
    // ═══════════════════════════════════════════

    public function linkRules()
    {
        $rules = SeoLinkRule::orderByDesc('priority')->paginate(50);
        return view('admin.seo.link-rules', compact('rules'));
    }

    public function storeLinkRule(Request $request)
    {
        $data = $request->validate([
            'keyword'     => 'required|string|max:255',
            'url'         => 'required|string|max:500',
            'anchor_text' => 'nullable|string|max:255',
            'priority'    => 'nullable|integer|min:0|max:100',
        ]);

        SeoLinkRule::create($data + ['is_active' => true]);

        return redirect()->route('admin.seo.link-rules')
            ->with('success', 'Link rule created.');
    }

    public function destroyLinkRule(SeoLinkRule $rule)
    {
        $rule->delete();
        return redirect()->route('admin.seo.link-rules')
            ->with('success', 'Link rule removed.');
    }

    public function syncLinkRules()
    {
        $count = InternalLinker::syncFromContent();
        return redirect()->route('admin.seo.link-rules')
            ->with('success', "{$count} link rules synced from destinations & countries.");
    }

    // ═══════════════════════════════════════════
    // PROGRAMMATIC PAGES
    // ═══════════════════════════════════════════

    public function pages(Request $request)
    {
        $query = SeoPage::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $pages = $query->orderByDesc('views')->paginate(50)->withQueryString();
        $types = SeoPage::select('type')->distinct()->pluck('type');
        return view('admin.seo.pages', compact('pages', 'types'));
    }

    public function editPage(SeoPage $page)
    {
        return view('admin.seo.page-edit', compact('page'));
    }

    public function updatePage(Request $request, SeoPage $page)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'intro_content'    => 'nullable|string',
            'body_content'     => 'nullable|string',
            'is_published'     => 'boolean',
        ]);

        $page->update($data);

        return redirect()->route('admin.seo.pages.edit', $page)
            ->with('success', 'Page updated.');
    }

    public function destroyPage(SeoPage $page)
    {
        $page->delete();
        return redirect()->route('admin.seo.pages')
            ->with('success', 'Page deleted.');
    }

    public function bulkDestroyPages(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        $count = SeoPage::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.seo.pages')
            ->with('success', $count . ' page(s) deleted.');
    }

    public function generatePages()
    {
        $stats = (new SeoPageGenerator())->generateAll();
        return redirect()->route('admin.seo.pages')
            ->with('success', "Generated {$stats['created']} new pages ({$stats['skipped']} skipped).");
    }

    // ═══════════════════════════════════════════
    // GEO MARKET PAGES
    // ═══════════════════════════════════════════

    public function markets(Request $request)
    {
        $query = SeoMarket::query();

        if ($request->filled('source')) {
            $query->where('source_market', $request->source);
        }
        if ($request->filled('target')) {
            $query->where('target_country', $request->target);
        }
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $markets = $query->orderBy('target_country')->orderBy('source_market')->paginate(50)->withQueryString();
        $sources = SeoMarket::select('source_market')->distinct()->pluck('source_market');
        $targets = SeoMarket::select('target_country')->distinct()->pluck('target_country');
        return view('admin.seo.markets', compact('markets', 'sources', 'targets'));
    }

    public function editMarket(SeoMarket $market)
    {
        return view('admin.seo.market-edit', compact('market'));
    }

    public function updateMarket(Request $request, SeoMarket $market)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'intro_content'    => 'nullable|string',
            'flights_info'     => 'nullable|string',
            'visa_info'        => 'nullable|string',
            'travel_tips'      => 'nullable|string',
            'best_routes'      => 'nullable|string',
            'pricing_info'     => 'nullable|string',
            'is_published'     => 'boolean',
        ]);

        $market->update($data);

        return redirect()->route('admin.seo.markets.edit', $market)
            ->with('success', 'Market page updated.');
    }

    public function destroyMarket(SeoMarket $market)
    {
        $market->delete();
        return redirect()->route('admin.seo.markets')
            ->with('success', 'Market page deleted.');
    }

    public function bulkDestroyMarkets(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        $count = SeoMarket::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.seo.markets')
            ->with('success', $count . ' market page(s) deleted.');
    }

    public function generateMarkets()
    {
        $stats = (new GeoMarketGenerator())->generateAll();
        return redirect()->route('admin.seo.markets')
            ->with('success', "Generated {$stats['created']} market pages ({$stats['skipped']} skipped).");
    }

    // ═══════════════════════════════════════════
    // IMAGE SEO
    // ═══════════════════════════════════════════

    public function imageSeo()
    {
        $images = SeoImageMeta::orderByDesc('updated_at')->paginate(50);
        $untaggedCount = $this->countUntaggedImages();
        return view('admin.seo.image-seo', compact('images', 'untaggedCount'));
    }

    public function processImages()
    {
        $count = (new ImageSeoService())->processUntagged();
        return redirect()->route('admin.seo.image-seo')
            ->with('success', "{$count} images processed.");
    }

    public function updateImageMeta(Request $request, SeoImageMeta $image)
    {
        $data = $request->validate([
            'alt_text' => 'nullable|string|max:500',
            'caption'  => 'nullable|string|max:255',
        ]);

        $image->update($data);

        return redirect()->route('admin.seo.image-seo')
            ->with('success', 'Image metadata updated.');
    }

    protected function countUntaggedImages(): int
    {
        $storagePath = storage_path('app/public');
        if (!is_dir($storagePath)) {
            return 0;
        }

        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $total = 0;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath));
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), $extensions)) {
                $total++;
            }
        }

        return max(0, $total - SeoImageMeta::count());
    }

    // ═══════════════════════════════════════════
    // RANK ALERTS
    // ═══════════════════════════════════════════

    public function alerts()
    {
        $alerts = SeoRankAlert::with('ranking')
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.seo.alerts', compact('alerts'));
    }

    public function markAlertRead(SeoRankAlert $alert)
    {
        $alert->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Alert marked as read.');
    }

    public function markAllAlertsRead()
    {
        SeoRankAlert::unread()->update(['is_read' => true]);
        return redirect()->back()->with('success', 'All alerts marked as read.');
    }

    // ═══════════════════════════════════════════
    // AUTHOR PROFILES (E-E-A-T)
    // ═══════════════════════════════════════════

    public function authors()
    {
        $authors = AuthorProfile::withCount('posts')->orderBy('name')->paginate(20);
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.seo.authors', compact('authors', 'users'));
    }

    public function storeAuthor(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'title'            => 'nullable|string|max:255',
            'bio'              => 'nullable|string',
            'linkedin_url'     => 'nullable|url|max:255',
            'twitter_url'      => 'nullable|url|max:255',
            'expertise'        => 'nullable|string|max:255',
            'years_experience' => 'nullable|integer|min:0',
        ]);

        AuthorProfile::create($data + ['is_active' => true]);

        return redirect()->route('admin.seo.authors')
            ->with('success', 'Author profile created.');
    }

    public function destroyAuthor(AuthorProfile $author)
    {
        $author->delete();
        return redirect()->route('admin.seo.authors')
            ->with('success', 'Author profile removed.');
    }

    // ═══════════════════════════════════════════
    // AI CONTENT GENERATOR (AJAX)
    // ═══════════════════════════════════════════

    /**
     * Generate SEO content suggestions.
     * This uses template-based generation (no external AI API required).
     */
    public function generateContent(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:intro,description,faq,blog_topics',
            'context' => 'required|string|max:500',
            'keyword' => 'nullable|string|max:255',
        ]);

        $context = $data['context'];
        $keyword = $data['keyword'] ?? $context;

        $result = match ($data['type']) {
            'intro' => $this->generateIntro($context, $keyword),
            'description' => $this->generateDescription($context, $keyword),
            'faq' => $this->generateFaqs($context, $keyword),
            'blog_topics' => $this->generateBlogTopics($context, $keyword),
        };

        return response()->json($result);
    }

    protected function generateIntro(string $context, string $keyword): array
    {
        $templates = [
            "Discover the magic of {$context} with our expertly crafted safari experiences. From breathtaking wildlife encounters to luxurious lodges nestled in the heart of the wilderness, every moment promises to be unforgettable. Our {$keyword} packages are designed by local experts who know the land, the seasons, and the hidden gems that make each journey extraordinary.",
            "Embark on the adventure of a lifetime with a {$context} experience that goes beyond the ordinary. Our curated itineraries blend world-class wildlife viewing with authentic cultural encounters and premium comfort. Whether you dream of witnessing the Great Migration or tracking the Big Five, our {$keyword} packages deliver moments that will stay with you forever.",
            "There's nothing quite like a {$context}. The golden light of dawn across the savanna, the rumble of elephants at a watering hole, the thrill of spotting a leopard in the acacia trees. Our {$keyword} experiences are thoughtfully designed to immerse you in the raw beauty of East Africa while ensuring your comfort at every turn.",
        ];

        return ['content' => $templates[array_rand($templates)]];
    }

    protected function generateDescription(string $context, string $keyword): array
    {
        $templates = [
            "Experience the ultimate {$context}. Expert local guides, premium accommodations, and unforgettable wildlife encounters in the heart of East Africa. Book your {$keyword} adventure today.",
            "Discover hand-crafted {$context} packages featuring exclusive lodges, professional safari guides, and personalized itineraries. Your dream {$keyword} experience starts here.",
            "Plan your perfect {$context} with our award-winning team. From luxury tented camps to thrilling game drives, we craft bespoke {$keyword} journeys that exceed expectations.",
        ];

        return ['content' => $templates[array_rand($templates)]];
    }

    protected function generateFaqs(string $context, string $keyword): array
    {
        return ['faqs' => [
            ['q' => "What is the best time to visit for a {$context}?", 'a' => "The dry season (June-October) offers the best wildlife viewing for a {$context}. The wet season (November-May) brings lush green landscapes, fewer crowds, and lower prices."],
            ['q' => "How much does a {$keyword} cost?", 'a' => "Prices for a {$keyword} typically range from $2,000 to $8,000 per person depending on duration, accommodation level, and season. Contact us for a custom quote."],
            ['q' => "What animals will I see on a {$context}?", 'a' => "You can expect to see the Big Five (lion, leopard, elephant, buffalo, rhino), cheetahs, zebras, wildebeest, giraffes, hippos, and over 500 bird species."],
            ['q' => "Is a {$keyword} safe?", 'a' => "Yes, our safaris are led by professionally trained guides in well-maintained vehicles. Tanzania is one of Africa's safest destinations for wildlife tourism."],
            ['q' => "What should I pack for a {$context}?", 'a' => "Pack layers (mornings are cool), neutral-colored clothing, comfortable walking shoes, sunscreen, insect repellent, binoculars, and a camera with a good zoom lens."],
            ['q' => "Can I customize my {$keyword} itinerary?", 'a' => "Absolutely! All our safaris can be fully customized to match your preferences, budget, and travel dates. Contact our planning team to build your dream itinerary."],
        ]];
    }

    protected function generateBlogTopics(string $context, string $keyword): array
    {
        return ['topics' => [
            "Best Time to Visit {$context}: Month-by-Month Guide",
            "{$keyword} Cost Breakdown: What to Expect in " . date('Y'),
            "Top 10 Things to See on a {$context}",
            "First-Timer's Guide to {$keyword}: Everything You Need to Know",
            "{$context} Packing List: The Ultimate Checklist",
            "Luxury vs Budget {$keyword}: Which is Right for You?",
            "The Complete {$context} Photography Guide",
            "Family-Friendly {$keyword}: Tips for Traveling with Kids",
            "Best Lodges and Camps for Your {$context}",
            "{$keyword} vs Other African Safaris: A Comparison",
        ]];
    }
}
