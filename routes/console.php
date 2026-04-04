<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily SEO rank tracking at 6 AM
Schedule::command('seo:check-rankings')->dailyAt('06:00');

// Weekly full SEO analysis on Sundays at 3 AM
Schedule::command('seo:analyze-all')->weeklyOn(0, '03:00');
