<?php

namespace App\Providers;

use App\Http\Middleware\SetLocale;
use App\Models\Category;
use App\Models\Country;
use App\Models\Setting;
use App\Models\TourType;
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
            $view->with('navCountries', Country::with('destinations')->orderBy('name')->get());
            $view->with('navCategories', Category::orderBy('name')->get());
            $view->with('navTourTypes', TourType::orderBy('name')->get());
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
