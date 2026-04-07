<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\MenuItem;
use App\Models\SafariPackage;
use App\Models\Setting;
use App\Models\TourType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (! isset($view->getData()['siteSetting'])) {
                $setting = Setting::first();
                $view->with('siteSetting', $setting);
                $view->with('siteName', optional($setting)->site_name ?: 'Lomo Tanzania Safari');
                $view->with('siteTagline', optional($setting)->tagline ?: 'Less On Ourselves, More On Others');
            }
        });

        // Navigation data — shared only with the public layout
        View::composer('layouts.app', function ($view) {
            $tanzania = Country::with(['destinations' => fn ($query) => $query->orderBy('name')])
                ->where('slug', 'tanzania')
                ->first()
                ?? Country::with(['destinations' => fn ($query) => $query->orderBy('name')])->orderBy('name')->first();

            $tanzaniaDestinations = $tanzania?->destinations?->take(6)?->values() ?? collect();

            $featuredDestination = Destination::query()
                ->when($tanzania, fn ($query) => $query->where('country_id', $tanzania->id))
                ->whereNotNull('featured_image')
                ->inRandomOrder()
                ->first()
                ?? Destination::whereNotNull('featured_image')->inRandomOrder()->first();

            $featuredSafari = SafariPackage::query()
                ->whereNotNull('featured_image')
                ->where('status', 'published')
                ->when(
                    $tanzania,
                    fn ($query) => $query->whereHas('countries', fn ($countryQuery) => $countryQuery->where('countries.id', $tanzania->id))
                )
                ->inRandomOrder()
                ->first();

            $navMenuItems = Schema::hasTable('menu_items')
                ? MenuItem::enabled()->ordered()->get()
                : collect();

            $view->with([
                'navCountries' => $tanzania ? collect([$tanzania]) : collect(),
                'navTanzania' => $tanzania,
                'navTanzaniaDestinations' => $tanzaniaDestinations,
                'navFeaturedDestination' => $featuredDestination,
                'navFeaturedSafari' => $featuredSafari,
                'navMenuItems' => $navMenuItems,
                'navCategories' => Category::orderBy('name')->get(),
                'navTourTypes' => TourType::orderBy('name')->get(),
                'navSafaris' => SafariPackage::whereNotNull('featured_image')->where('status', 'published')->orderByDesc('is_popular')->inRandomOrder()->take(4)->get(),
            ]);
        });

        // Share supported locales for use in views
        View::share('supportedLocales', SetLocale::SUPPORTED);

        // Set a global URL default for the locale parameter so that
        // route() calls from admin/agent views (outside the locale group)
        // can still generate locale-prefixed URLs.  The SetLocale
        // middleware will override this at runtime for public pages.
        URL::defaults(['locale' => SetLocale::DEFAULT]);
    }
}
