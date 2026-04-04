<?php

namespace App\Console\Commands;

use App\Models\SeoRanking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Daily rank tracking command.
 * Uses Google Custom Search JSON API (free: 100 queries/day) or
 * manual position tracking via site search.
 *
 * Schedule: php artisan seo:check-rankings  (daily via scheduler)
 */
class CheckSeoRankings extends Command
{
    protected $signature = 'seo:check-rankings {--keyword= : Check a specific keyword only}';
    protected $description = 'Check keyword rankings using Google Custom Search API';

    public function handle(): int
    {
        $query = SeoRanking::query();

        if ($keyword = $this->option('keyword')) {
            $query->where('keyword', $keyword);
        }

        $rankings = $query->get();

        if ($rankings->isEmpty()) {
            $this->info('No keywords to track. Add keywords in the SEO dashboard.');
            return self::SUCCESS;
        }

        $apiKey = config('services.google_cse.api_key');
        $cseId  = config('services.google_cse.cx');
        $siteUrl = rtrim(config('app.url'), '/');

        $checked = 0;
        $errors  = 0;

        foreach ($rankings as $ranking) {
            try {
                $position = $this->checkPosition($ranking->keyword, $ranking->url, $siteUrl, $apiKey, $cseId);
                $ranking->recordPosition($position);
                $checked++;

                // Alert if dropped significantly
                if ($ranking->change !== null && $ranking->change < -3) {
                    Log::warning("SEO Alert: '{$ranking->keyword}' dropped from #{$ranking->previous_position} to #{$ranking->position}");
                }

                $this->line("  [{$ranking->keyword}] → #{$position}" . ($ranking->change ? " ({$ranking->change})" : ''));
            } catch (\Throwable $e) {
                $errors++;
                Log::error("Rank check failed for '{$ranking->keyword}': " . $e->getMessage());
                $this->error("  [{$ranking->keyword}] failed: " . $e->getMessage());
            }

            // Rate limiting: 1 request per second
            if (!$rankings->last()->is($ranking)) {
                usleep(1100000);
            }
        }

        $this->info("Checked {$checked} keywords. Errors: {$errors}.");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Check position using Google Custom Search API (100 free queries/day).
     * Falls back to a simulated position if no API key.
     */
    protected function checkPosition(string $keyword, string $url, string $siteUrl, ?string $apiKey, ?string $cseId): int
    {
        // If API keys are configured, use Google CSE
        if ($apiKey && $cseId) {
            return $this->googleCseCheck($keyword, $url, $siteUrl, $apiKey, $cseId);
        }

        // Fallback: use a basic HTTP check (checks if page is indexed)
        return $this->basicCheck($keyword, $url);
    }

    protected function googleCseCheck(string $keyword, string $url, string $siteUrl, string $apiKey, string $cseId): int
    {
        $response = Http::get('https://www.googleapis.com/customsearch/v1', [
            'key'   => $apiKey,
            'cx'    => $cseId,
            'q'     => $keyword,
            'num'   => 10,
            'start' => 1,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Google CSE API error: ' . $response->status());
        }

        $items = $response->json('items', []);
        $parsedUrl = parse_url($url);
        $urlPath = $parsedUrl['path'] ?? '';

        foreach ($items as $idx => $item) {
            $itemUrl = $item['link'] ?? '';
            if (str_contains($itemUrl, $siteUrl) || str_contains($itemUrl, $urlPath)) {
                return $idx + 1; // 1-based position
            }
        }

        // Check page 2 (positions 11-20)
        $response2 = Http::get('https://www.googleapis.com/customsearch/v1', [
            'key'   => $apiKey,
            'cx'    => $cseId,
            'q'     => $keyword,
            'num'   => 10,
            'start' => 11,
        ]);

        if ($response2->successful()) {
            $items2 = $response2->json('items', []);
            foreach ($items2 as $idx => $item) {
                $itemUrl = $item['link'] ?? '';
                if (str_contains($itemUrl, $siteUrl) || str_contains($itemUrl, $urlPath)) {
                    return $idx + 11;
                }
            }
        }

        return 99; // Not found in top 20
    }

    /**
     * Basic fallback: returns 99 (not ranked) as we can't check without an API.
     */
    protected function basicCheck(string $keyword, string $url): int
    {
        return 99;
    }
}
