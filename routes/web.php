<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\AgentController as AdminAgentController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\SafariRequestController as AdminSafariRequestController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\PlannerSettingController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SafariController;
use App\Http\Controllers\Admin\SafariPlanController as AdminSafariPlanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TourTypeController;
use App\Http\Controllers\Agent\Auth\AgentSessionController;
use App\Http\Controllers\Agent\Auth\RegisteredAgentController;
use App\Http\Controllers\Agent\BookingController as AgentBookingController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Agent\SafariRequestController as AgentSafariRequestController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\HeroSettingController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CustomTourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlanSafariController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeoPageController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocale;
use App\Models\Destination;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Root → redirect to default locale
Route::get('/', fn () => redirect('/en'));

// Sitemap (no locale prefix)
Route::get('sitemap.xml', [SitemapController::class, 'index']);

// Locale-prefixed public routes
Route::prefix('{locale}')
    ->where(['locale' => 'en|fr|de|es'])
    ->middleware(SetLocale::class)
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/countries/{slug}', [HomeController::class, 'country'])->name('countries.show');
        Route::get('/types/{slug}', [HomeController::class, 'tourType'])->name('tour-types.show');
        Route::get('/categories/{slug}', [HomeController::class, 'category'])->name('categories.show');
        Route::get('/destinations/{slug}', [HomeController::class, 'destination'])->name('destinations.show');
        Route::get('/safaris', [HomeController::class, 'safariIndex'])->name('safaris.index');
        Route::get('/safaris/{slug}/download-pdf', [HomeController::class, 'downloadPdf'])->name('safaris.pdf');
        Route::get('/safaris/{slug}', [HomeController::class, 'show'])->name('safaris.show');

        Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

        Route::get('/custom-tour', [CustomTourController::class, 'create'])->name('custom-tour');
        Route::post('/custom-tour', [CustomTourController::class, 'store'])->name('custom-tour.store');

        Route::get('/plan-safari', [PlanSafariController::class, 'create'])->name('plan-safari');
        Route::post('/plan-safari/submit', [PlanSafariController::class, 'store'])->name('plan-safari.store');

        Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

        // Programmatic SEO pages & GEO market pages
        Route::get('/safaris/market/{slug}', [SeoPageController::class, 'market'])->name('seo.market');
        Route::get('/safaris/g/{slug}', [SeoPageController::class, 'show'])->name('seo.page');
    });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // Hero settings (under Pages)
    Route::get('pages/hero-settings', [HeroSettingController::class, 'edit'])->name('pages.hero-settings.edit');
    Route::put('pages/hero-settings', [HeroSettingController::class, 'update'])->name('pages.hero-settings.update');

    // Bulk action routes (must be before resource routes)
    Route::post('safaris/bulk-action', [SafariController::class, 'bulkAction'])->name('safaris.bulk-action');
    Route::post('accommodations/bulk-action', [AccommodationController::class, 'bulkAction'])->name('accommodations.bulk-action');
    Route::post('tour-types/bulk-action', [TourTypeController::class, 'bulkAction'])->name('tour-types.bulk-action');
    Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    Route::post('countries/bulk-action', [CountryController::class, 'bulkAction'])->name('countries.bulk-action');
    Route::post('destinations/bulk-action', [DestinationController::class, 'bulkAction'])->name('destinations.bulk-action');
    Route::post('testimonials/bulk-action', [TestimonialController::class, 'bulkAction'])->name('testimonials.bulk-action');
    Route::post('pages/bulk-action', [AdminPageController::class, 'bulkAction'])->name('pages.bulk-action');
    Route::post('posts/bulk-action', [PostController::class, 'bulkAction'])->name('posts.bulk-action');
    Route::post('blog-categories/bulk-action', [BlogCategoryController::class, 'bulkAction'])->name('blog-categories.bulk-action');
    Route::post('inquiries/bulk-action', [AdminInquiryController::class, 'bulkAction'])->name('inquiries.bulk-action');
    Route::post('bookings/bulk-action', [AdminBookingController::class, 'bulkAction'])->name('bookings.bulk-action');
    Route::post('languages/bulk-action', [LanguageController::class, 'bulkAction'])->name('languages.bulk-action');

    Route::resource('safaris', SafariController::class)->except(['show']);
    Route::resource('accommodations', AccommodationController::class)->except(['show']);
    Route::resource('tour-types', TourTypeController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('countries', CountryController::class)->except(['show']);
    Route::resource('destinations', DestinationController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::post('pages/upload-image', [AdminPageController::class, 'uploadImage'])->name('pages.upload-image');

    Route::resource('posts', PostController::class)->except(['show']);
    Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::put('media/{medium}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('media/bulk-destroy', [MediaController::class, 'bulkDestroy'])->name('media.bulk-destroy');
    Route::get('media/json', [MediaController::class, 'json'])->name('media.json');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('planner-settings', [PlannerSettingController::class, 'edit'])->name('planner-settings.edit');
    Route::put('planner-settings', [PlannerSettingController::class, 'update'])->name('planner-settings.update');

    Route::get('safari-plans', [AdminSafariPlanController::class, 'index'])->name('safari-plans.index');
    Route::get('safari-plans/{safariPlan}', [AdminSafariPlanController::class, 'show'])->name('safari-plans.show');
    Route::delete('safari-plans/{safariPlan}', [AdminSafariPlanController::class, 'destroy'])->name('safari-plans.destroy');

    Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('inquiries/{inquiry}', [AdminInquiryController::class, 'update'])->name('inquiries.update');
    Route::delete('inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Agents
    Route::get('agents', [AdminAgentController::class, 'index'])->name('agents.index');
    Route::get('agents/{agent}', [AdminAgentController::class, 'show'])->name('agents.show');
    Route::patch('agents/{agent}', [AdminAgentController::class, 'update'])->name('agents.update');
    Route::post('agents/{agent}/approve', [AdminAgentController::class, 'approve'])->name('agents.approve');
    Route::post('agents/{agent}/suspend', [AdminAgentController::class, 'suspend'])->name('agents.suspend');
    Route::post('agents/{agent}/ban', [AdminAgentController::class, 'ban'])->name('agents.ban');
    Route::post('agents/{agent}/restore', [AdminAgentController::class, 'restore'])->name('agents.restore');
    Route::post('agents/{agent}/reject', [AdminAgentController::class, 'reject'])->name('agents.reject');
    Route::delete('agents/{agent}', [AdminAgentController::class, 'destroy'])->name('agents.destroy');

    // Safari Requests
    Route::get('safari-requests', [AdminSafariRequestController::class, 'index'])->name('safari-requests.index');
    Route::get('safari-requests/{safariRequest}', [AdminSafariRequestController::class, 'show'])->name('safari-requests.show');
    Route::post('safari-requests/{safariRequest}/respond', [AdminSafariRequestController::class, 'respond'])->name('safari-requests.respond');

    // Bookings
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');

    Route::get('api/destinations', function (Request $request) {
        $query = Destination::orderBy('name');
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->integer('country_id'));
        }
        return $query->get(['id', 'name', 'country_id', 'latitude', 'longitude']);
    })->name('api.destinations');

    Route::get('api/accommodations', function (Request $request) {
        $query = Accommodation::orderBy('name');
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->integer('destination_id'));
        }
        return $query->get(['id', 'name', 'destination_id', 'category']);
    })->name('api.accommodations');

    // SEO Dashboard & Analysis
    Route::get('seo', [SeoController::class, 'dashboard'])->name('seo.dashboard');
    Route::post('seo/analyze', [SeoController::class, 'analyze'])->name('seo.analyze');
    Route::post('seo/analyze-all', [SeoController::class, 'analyzeAll'])->name('seo.analyze-all');
    Route::post('seo/optimize', [SeoController::class, 'optimize'])->name('seo.optimize');
    Route::post('seo/save-analysis', [SeoController::class, 'saveAnalysis'])->name('seo.save-analysis');
    Route::get('seo/rankings', [SeoController::class, 'rankings'])->name('seo.rankings');
    Route::post('seo/keywords-track', [SeoController::class, 'addKeyword'])->name('seo.keywords.add');
    Route::delete('seo/keywords-track/{ranking}', [SeoController::class, 'removeKeyword'])->name('seo.keywords.remove');

    // Keyword Strategy
    Route::get('seo/keywords', [SeoController::class, 'keywords'])->name('seo.keywords');
    Route::post('seo/keywords', [SeoController::class, 'storeKeyword'])->name('seo.keywords.store');
    Route::delete('seo/keywords/{keyword}', [SeoController::class, 'destroyKeyword'])->name('seo.keywords.destroy');

    // Internal Link Rules
    Route::get('seo/link-rules', [SeoController::class, 'linkRules'])->name('seo.link-rules');
    Route::post('seo/link-rules', [SeoController::class, 'storeLinkRule'])->name('seo.link-rules.store');
    Route::delete('seo/link-rules/{rule}', [SeoController::class, 'destroyLinkRule'])->name('seo.link-rules.destroy');
    Route::post('seo/link-rules/sync', [SeoController::class, 'syncLinkRules'])->name('seo.link-rules.sync');

    // Programmatic SEO Pages
    Route::get('seo/pages', [SeoController::class, 'pages'])->name('seo.pages');
    Route::get('seo/pages/{page}/edit', [SeoController::class, 'editPage'])->name('seo.pages.edit');
    Route::put('seo/pages/{page}', [SeoController::class, 'updatePage'])->name('seo.pages.update');
    Route::delete('seo/pages/{page}', [SeoController::class, 'destroyPage'])->name('seo.pages.destroy');
    Route::post('seo/pages/bulk-destroy', [SeoController::class, 'bulkDestroyPages'])->name('seo.pages.bulk-destroy');
    Route::post('seo/pages/generate', [SeoController::class, 'generatePages'])->name('seo.pages.generate');

    // GEO Market Pages
    Route::get('seo/markets', [SeoController::class, 'markets'])->name('seo.markets');
    Route::get('seo/markets/{market}/edit', [SeoController::class, 'editMarket'])->name('seo.markets.edit');
    Route::put('seo/markets/{market}', [SeoController::class, 'updateMarket'])->name('seo.markets.update');
    Route::delete('seo/markets/{market}', [SeoController::class, 'destroyMarket'])->name('seo.markets.destroy');
    Route::post('seo/markets/bulk-destroy', [SeoController::class, 'bulkDestroyMarkets'])->name('seo.markets.bulk-destroy');
    Route::post('seo/markets/generate', [SeoController::class, 'generateMarkets'])->name('seo.markets.generate');

    // Image SEO
    Route::get('seo/image-seo', [SeoController::class, 'imageSeo'])->name('seo.image-seo');
    Route::post('seo/image-seo/process', [SeoController::class, 'processImages'])->name('seo.image-seo.process');
    Route::put('seo/image-seo/{image}', [SeoController::class, 'updateImageMeta'])->name('seo.image-seo.update');

    // Rank Alerts
    Route::get('seo/alerts', [SeoController::class, 'alerts'])->name('seo.alerts');
    Route::post('seo/alerts/{alert}/read', [SeoController::class, 'markAlertRead'])->name('seo.alerts.read');
    Route::post('seo/alerts/read-all', [SeoController::class, 'markAllAlertsRead'])->name('seo.alerts.read-all');

    // Author Profiles (E-E-A-T)
    Route::get('seo/authors', [SeoController::class, 'authors'])->name('seo.authors');
    Route::post('seo/authors', [SeoController::class, 'storeAuthor'])->name('seo.authors.store');
    Route::delete('seo/authors/{author}', [SeoController::class, 'destroyAuthor'])->name('seo.authors.destroy');

    // AI Content Generator
    Route::post('seo/generate-content', [SeoController::class, 'generateContent'])->name('seo.generate-content');

    // Language Management
    Route::resource('languages', LanguageController::class)->except(['show']);
});

// Agent root redirect
Route::get('agent', function () {
    if (auth()->check() && auth()->user()->isAgent() && auth()->user()->agent?->isActive()) {
        return redirect()->route('agent.dashboard');
    }
    return redirect()->route('agent.login');
});

// Agent Auth (guest)
Route::middleware('guest')->prefix('agent')->name('agent.')->group(function () {
    Route::get('login', [AgentSessionController::class, 'create'])->name('login');
    Route::post('login', [AgentSessionController::class, 'store'])->name('login.store');
    Route::get('register', [RegisteredAgentController::class, 'create'])->name('register');
    Route::post('register', [RegisteredAgentController::class, 'store'])->name('register.store');
});

// Agent Portal (authenticated + active agents)
Route::middleware(['auth', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::post('logout', [AgentSessionController::class, 'destroy'])->name('logout');
    Route::get('dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::get('bookings', [AgentBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AgentBookingController::class, 'show'])->name('bookings.show');
    Route::get('earnings', [AgentBookingController::class, 'earnings'])->name('earnings');

    // Safari Requests
    Route::get('requests', [AgentSafariRequestController::class, 'index'])->name('requests.index');
    Route::get('requests/new', [AgentSafariRequestController::class, 'create'])->name('requests.create');
    Route::post('requests', [AgentSafariRequestController::class, 'store'])->name('requests.store');

    // Responses / Proposals
    Route::get('responses', [AgentSafariRequestController::class, 'responses'])->name('responses.index');
    Route::post('responses/{safariRequest}/accept', [AgentSafariRequestController::class, 'acceptResponse'])->name('responses.accept');
    Route::post('responses/{safariRequest}/decline', [AgentSafariRequestController::class, 'declineResponse'])->name('responses.decline');

    // Profile
    Route::get('profile', [AgentProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AgentProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
