<?php

namespace App\Services;

use App\Models\Country;
use App\Models\SeoMarket;
use Illuminate\Support\Str;

/**
 * GEO SEO Market Page Generator — creates market-targeting pages
 * like "Tanzania Safari from UK", "Tanzania Safari from Germany".
 */
class GeoMarketGenerator
{
    protected array $markets = [
        'UK' => [
            'flights' => 'Direct flights from London Heathrow to Kilimanjaro International Airport (JRO) take approximately 10-11 hours. Airlines like KLM, Ethiopian Airlines, and Turkish Airlines offer excellent connections.',
            'visa' => 'UK citizens need a visa to visit Tanzania. You can apply for an e-visa online at eservices.immigration.go.tz. The tourist visa costs $50 and is valid for 90 days.',
            'tips' => 'The best time to visit is during the dry season (June-October) for optimal wildlife viewing. Pack layers for early morning game drives, and bring a good camera with a zoom lens.',
            'routes' => 'Most UK travelers fly into Kilimanjaro Airport (JRO) for northern circuit safaris or Dar es Salaam (DAR) for southern circuit. Charter flights connect to airstrips in the Serengeti and other parks.',
            'pricing' => 'Safari packages from the UK typically range from $2,000-$8,000 per person depending on duration, accommodation level, and season. Luxury options start from $5,000+.',
        ],
        'USA' => [
            'flights' => 'There are no direct flights from the US to Tanzania. Best connections are via Doha (Qatar Airways), Amsterdam (KLM), Istanbul (Turkish Airlines), or Addis Ababa (Ethiopian Airlines). Total travel time is 16-22 hours.',
            'visa' => 'US citizens require a visa. Apply for an e-visa at eservices.immigration.go.tz. The tourist visa costs $100 for US passport holders and is valid for a single entry.',
            'tips' => 'Consider arriving a day early to acclimatize. Tanzania is in the EAT timezone (UTC+3). Travel insurance with medical evacuation is highly recommended.',
            'routes' => 'Fly into Kilimanjaro International Airport for Northern Circuit (Serengeti, Ngorongoro, Tarangire) or Julius Nyerere Airport in Dar es Salaam for Southern Circuit (Selous, Ruaha).',
            'pricing' => 'Safari costs from the US range from $2,500-$10,000 per person excluding international flights. Budget 4-7 days for a well-rounded experience.',
        ],
        'Germany' => [
            'flights' => 'Condor offers seasonal direct flights from Frankfurt to Kilimanjaro. KLM via Amsterdam and Turkish Airlines via Istanbul are popular year-round options. Flight time is 9-12 hours.',
            'visa' => 'Deutsche Staatsbürger benötigen ein Visum. Das e-Visum kann online beantragt werden. Tourist visa costs $50 and is valid for 90 days.',
            'tips' => 'German-speaking guides are available on request. The Serengeti and Ngorongoro are the most popular destinations for German travelers.',
            'routes' => 'Most German travelers enter via Kilimanjaro Airport. The northern circuit (Serengeti-Ngorongoro-Tarangire) is the most popular itinerary.',
            'pricing' => 'Safari packages range from €2,000-€8,000 per person. Early booking discounts are often available for the green season (March-May).',
        ],
        'France' => [
            'flights' => 'No direct flights — connect via Amsterdam (KLM), Istanbul (Turkish), or Doha (Qatar). Total travel time from Paris is 11-14 hours.',
            'visa' => 'Les citoyens français ont besoin d\'un visa. E-visa available online for $50. Valid for a 90-day tourist stay.',
            'tips' => 'French-speaking guides can be arranged. The wildebeest migration in the Serengeti (June-October) is a highlight.',
            'routes' => 'Kilimanjaro Airport is the main gateway. Zanzibar can be combined with a mainland safari for a beach extension.',
            'pricing' => 'Expect €2,500-€9,000 per person depending on season and luxury level. Include flights, which typically cost €500-€900 return.',
        ],
        'Australia' => [
            'flights' => 'Fly via Dubai (Emirates), Doha (Qatar), or Singapore/Nairobi. Total travel time from Sydney is 20-24 hours.',
            'visa' => 'Australian passport holders need a visa. E-visa costs $50 and can be obtained online within 48 hours.',
            'tips' => 'The time difference is 7-9 hours behind AEST. Allow 1-2 days for jet lag recovery before starting your safari.',
            'routes' => 'Combine a Tanzania safari with Zanzibar beach or even a Kenya/Tanzania cross-border trip for the ultimate African adventure.',
            'pricing' => 'Budget AUD $4,000-$15,000 per person including international flights. The dry season (June-October) offers the best value.',
        ],
        'Canada' => [
            'flights' => 'Connect via European hubs (Amsterdam, Istanbul, Frankfurt) or Middle East (Doha, Dubai). Total travel time from Toronto is 16-22 hours.',
            'visa' => 'Canadian citizens need a tourist visa ($100 CAD equivalent). Apply online for the e-visa.',
            'tips' => 'Canadian dollars can be exchanged in Arusha or Dar es Salaam. US dollars are widely accepted for safari payments.',
            'routes' => 'The classic northern circuit (Arusha → Tarangire → Serengeti → Ngorongoro) is 5-7 days and the most popular choice.',
            'pricing' => 'Safari packages range from CAD $3,000-$12,000 per person depending on duration and comfort level.',
        ],
    ];

    /**
     * Generate all market pages.
     */
    public function generateAll(): array
    {
        $stats = ['created' => 0, 'skipped' => 0];

        foreach (Country::all() as $country) {
            foreach ($this->markets as $market => $info) {
                $slug = Str::slug("{$country->name}-from-{$market}");

                if (SeoMarket::where('slug', $slug)->exists()) {
                    $stats['skipped']++;
                    continue;
                }

                SeoMarket::create([
                    'slug' => $slug,
                    'target_country' => $country->name,
                    'source_market' => $market,
                    'title' => "{$country->name} Safari from {$market}",
                    'meta_title' => "{$country->name} Safari from {$market} — Best Packages & Prices " . date('Y'),
                    'meta_description' => "Plan your {$country->name} safari from {$market}. Expert local guides, premium lodges, and tailor-made itineraries. Includes flight tips, visa info, and pricing for {$market} travelers.",
                    'intro_content' => "Dreaming of a {$country->name} safari? We specialize in crafting unforgettable safari experiences for travelers from {$market}. From the moment you land to your final game drive, every detail is taken care of by our local expert team.",
                    'flights_info' => $info['flights'],
                    'visa_info' => $info['visa'],
                    'travel_tips' => $info['tips'],
                    'best_routes' => $info['routes'],
                    'pricing_info' => $info['pricing'],
                    'is_published' => true,
                ]);
                $stats['created']++;
            }
        }

        return $stats;
    }
}
