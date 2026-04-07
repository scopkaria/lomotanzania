<?php

namespace App\Console\Commands;

use App\Services\TripAdvisorScraper;
use App\Models\Setting;
use Illuminate\Console\Command;

class PullTripadvisorReviews extends Command
{
    protected $signature = 'tripadvisor:pull';
    protected $description = 'Pull latest reviews from TripAdvisor';

    public function handle(): int
    {
        $settings = Setting::first();
        $url = $settings?->tripadvisor_url;

        if (!$url) {
            $this->error('No TripAdvisor URL configured in Settings.');
            return self::FAILURE;
        }

        $this->info("Pulling reviews from TripAdvisor...");

        $scraper = new TripAdvisorScraper();
        $result = $scraper->scrape($url);

        $this->info("Found {$result['total']} reviews, {$result['new']} new.");

        if (!empty($result['errors'])) {
            foreach ($result['errors'] as $error) {
                $this->warn("Error: {$error}");
            }
        }

        return self::SUCCESS;
    }
}
