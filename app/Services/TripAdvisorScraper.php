<?php

namespace App\Services;

use App\Models\TripadvisorReview;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TripAdvisorScraper
{
    /**
     * Scrape reviews from a TripAdvisor listing page.
     *
     * @return array{new: int, total: int, errors: string[]}
     */
    public function scrape(string $url): array
    {
        $result = ['new' => 0, 'total' => 0, 'errors' => []];

        if (empty($url)) {
            $result['errors'][] = 'No TripAdvisor URL configured.';
            return $result;
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])->timeout(30)->get($url);

            if (!$response->ok()) {
                $result['errors'][] = "Failed to fetch page (HTTP {$response->status()}).";
                return $result;
            }

            $html = $response->body();
            $reviews = $this->parseReviewsFromHtml($html);

            if (empty($reviews)) {
                // Try JSON-LD structured data fallback
                $reviews = $this->parseJsonLd($html);
            }

            $result['total'] = count($reviews);

            foreach ($reviews as $review) {
                $exists = TripadvisorReview::where('tripadvisor_id', $review['tripadvisor_id'])->exists();

                if (!$exists) {
                    TripadvisorReview::create($review);
                    $result['new']++;
                }
            }
        } catch (\Exception $e) {
            Log::error('TripAdvisor scrape failed: ' . $e->getMessage());
            $result['errors'][] = 'Scrape failed: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Parse reviews from TripAdvisor HTML using regex patterns.
     */
    protected function parseReviewsFromHtml(string $html): array
    {
        $reviews = [];

        // TripAdvisor embeds review data in script tags as JSON
        // Look for the "__WEB_CONTEXT__" or similar data block
        if (preg_match_all('/"reviewText"\s*:\s*"([^"]+)"/u', $html, $textMatches) &&
            preg_match_all('/"username"\s*:\s*"([^"]+)"/u', $html, $nameMatches)) {

            $count = min(count($textMatches[1]), count($nameMatches[1]));

            // Extract ratings
            preg_match_all('/"rating"\s*:\s*(\d+)/u', $html, $ratingMatches);
            // Extract titles
            preg_match_all('/"title"\s*:\s*"([^"]+)"/u', $html, $titleMatches);

            for ($i = 0; $i < $count; $i++) {
                $reviewText = $this->decodeUnicode($textMatches[1][$i]);
                $name = $this->decodeUnicode($nameMatches[1][$i]);
                $rating = isset($ratingMatches[1][$i]) ? (int) $ratingMatches[1][$i] : 5;
                $title = isset($titleMatches[1][$i]) ? $this->decodeUnicode($titleMatches[1][$i]) : null;

                // Clamp rating to 1-5 (TripAdvisor sometimes uses 10-50)
                if ($rating > 5) {
                    $rating = (int) round($rating / 10);
                }
                $rating = max(1, min(5, $rating));

                $reviews[] = [
                    'tripadvisor_id'    => 'ta_' . md5($name . $reviewText),
                    'reviewer_name'     => Str::limit($name, 100),
                    'reviewer_location' => null,
                    'reviewer_avatar'   => null,
                    'title'             => $title ? Str::limit($title, 200) : null,
                    'review_text'       => $reviewText,
                    'rating'            => $rating,
                    'review_date'       => null,
                    'trip_type'         => null,
                    'published'         => false,
                    'display_order'     => 0,
                ];
            }
        }

        return $reviews;
    }

    /**
     * Parse reviews from JSON-LD structured data (Schema.org Review).
     */
    protected function parseJsonLd(string $html): array
    {
        $reviews = [];

        // Find JSON-LD script blocks
        if (preg_match_all('/<script[^>]+type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            foreach ($matches[1] as $json) {
                $data = json_decode(trim($json), true);
                if (!$data) continue;

                $reviewItems = $data['review'] ?? [];
                if (isset($data['@type']) && $data['@type'] === 'Review') {
                    $reviewItems = [$data];
                }

                foreach ($reviewItems as $item) {
                    if (!isset($item['reviewBody'])) continue;

                    $name = $item['author']['name'] ?? ($item['author'] ?? 'Guest');
                    if (is_array($name)) $name = $name['name'] ?? 'Guest';

                    $rating = 5;
                    if (isset($item['reviewRating']['ratingValue'])) {
                        $rating = (int) $item['reviewRating']['ratingValue'];
                        if ($rating > 5) $rating = (int) round($rating / 10);
                        $rating = max(1, min(5, $rating));
                    }

                    $reviews[] = [
                        'tripadvisor_id'    => 'ta_' . md5($name . $item['reviewBody']),
                        'reviewer_name'     => Str::limit($name, 100),
                        'reviewer_location' => null,
                        'reviewer_avatar'   => null,
                        'title'             => isset($item['name']) ? Str::limit($item['name'], 200) : null,
                        'review_text'       => $item['reviewBody'],
                        'rating'            => $rating,
                        'review_date'       => isset($item['datePublished']) ? $item['datePublished'] : null,
                        'trip_type'         => null,
                        'published'         => false,
                        'display_order'     => 0,
                    ];
                }
            }
        }

        return $reviews;
    }

    /**
     * Decode unicode escape sequences in scraped strings.
     */
    protected function decodeUnicode(string $str): string
    {
        $str = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($m) {
            return mb_convert_encoding(pack('H*', $m[1]), 'UTF-8', 'UCS-2BE');
        }, $str);

        return html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
