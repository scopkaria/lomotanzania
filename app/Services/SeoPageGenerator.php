<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\SeoPage;
use App\Models\TourType;
use Illuminate\Support\Str;

/**
 * Programmatic SEO Page Generator — auto-creates landing pages
 * from combinations of countries, destinations, tour types, durations.
 */
class SeoPageGenerator
{
    /**
     * Generate all programmatic pages.
     */
    public function generateAll(): array
    {
        $stats = ['created' => 0, 'skipped' => 0];

        $this->generateCountryPages($stats);
        $this->generateDestinationPages($stats);
        $this->generateComboPages($stats);
        $this->generateDurationPages($stats);

        return $stats;
    }

    /**
     * Country pages: /safaris/tanzania, /safaris/kenya
     */
    protected function generateCountryPages(array &$stats): void
    {
        foreach (Country::all() as $country) {
            $slug = Str::slug($country->name) . '-safari';
            $this->createPage($slug, 'country', [
                'title' => "{$country->name} Safari Packages",
                'meta_title' => "Best {$country->name} Safari Packages & Tours " . date('Y'),
                'meta_description' => "Discover incredible safari experiences in {$country->name}. Browse curated packages, luxury lodges, and wildlife adventures. Book your dream {$country->name} safari today.",
                'intro_content' => "Explore the wild beauty of {$country->name} with our hand-picked safari packages. From the vast plains teeming with wildlife to luxurious lodges under starlit skies, every journey is crafted for an unforgettable experience.",
                'filter_criteria' => ['country_slug' => $country->slug],
            ], $stats);
        }
    }

    /**
     * Destination pages: /safaris/serengeti, /safaris/ngorongoro
     */
    protected function generateDestinationPages(array &$stats): void
    {
        foreach (Destination::with('country')->get() as $dest) {
            $slug = Str::slug($dest->name) . '-safari';
            $countryName = $dest->country?->name ?? 'East Africa';
            $this->createPage($slug, 'destination', [
                'title' => "{$dest->name} Safari Experiences",
                'meta_title' => "Best {$dest->name} Safari Tours & Packages " . date('Y'),
                'meta_description' => "Plan your {$dest->name} safari in {$countryName}. Expert-guided tours, premium accommodations, and unmatched wildlife encounters await you.",
                'intro_content' => "{$dest->name} is one of {$countryName}'s most spectacular safari destinations. Known for its incredible biodiversity and breathtaking landscapes, a safari here promises encounters with Africa's most iconic wildlife in their natural habitat.",
                'filter_criteria' => ['destination_slug' => $dest->slug],
            ], $stats);
        }
    }

    /**
     * Combo pages: Country + Type, e.g. "luxury-tanzania-safari"
     */
    protected function generateComboPages(array &$stats): void
    {
        $countries = Country::all();
        $tourTypes = TourType::all();
        $categories = Category::all();

        // Country + Tour Type
        foreach ($countries as $country) {
            foreach ($tourTypes as $type) {
                $slug = Str::slug("{$type->name} {$country->name} safari");
                $this->createPage($slug, 'combo', [
                    'title' => "{$type->name} {$country->name} Safari",
                    'meta_title' => "Best {$type->name} {$country->name} Safari Packages " . date('Y'),
                    'meta_description' => "Experience the ultimate {$type->name} safari in {$country->name}. Handcrafted itineraries, exclusive lodges, and expert guides for an unforgettable African adventure.",
                    'intro_content' => "Discover {$country->name} through the lens of a {$type->name} safari. Our carefully curated itineraries combine the best wildlife viewing with premium comfort and personalized service.",
                    'filter_criteria' => [
                        'country_slug' => $country->slug,
                        'tour_type_slug' => $type->slug,
                    ],
                ], $stats);
            }

            // Country + Category
            foreach ($categories as $cat) {
                $slug = Str::slug("{$country->name} {$cat->name} safari");
                $this->createPage($slug, 'combo', [
                    'title' => "{$country->name} {$cat->name} Safari",
                    'meta_title' => "{$country->name} {$cat->name} Safari Tours & Packages",
                    'meta_description' => "Book a {$cat->name} safari in {$country->name}. Explore wildlife, nature, and adventure with our expert-led tours and premium accommodations.",
                    'intro_content' => "For those drawn to {$cat->name} experiences, {$country->name} offers some of the world's finest safari opportunities. Our packages are designed to deliver authentic encounters with nature's most spectacular displays.",
                    'filter_criteria' => [
                        'country_slug' => $country->slug,
                        'category_slug' => $cat->slug,
                    ],
                ], $stats);
            }
        }

        // Destination + Tour Type (top destinations only)
        foreach (Destination::with('country')->get() as $dest) {
            foreach ($tourTypes as $type) {
                $slug = Str::slug("{$type->name} {$dest->name} safari");
                $this->createPage($slug, 'combo', [
                    'title' => "{$type->name} {$dest->name} Safari",
                    'meta_title' => "{$type->name} Safari in {$dest->name} | " . ($dest->country?->name ?? 'East Africa'),
                    'meta_description' => "Experience a {$type->name} safari in {$dest->name}. Expert guides, premium accommodations, and unforgettable wildlife viewing.",
                    'intro_content' => "{$dest->name} is the perfect setting for a {$type->name} safari experience. Let our expert team craft your ideal journey through one of Africa's most stunning destinations.",
                    'filter_criteria' => [
                        'destination_slug' => $dest->slug,
                        'tour_type_slug' => $type->slug,
                    ],
                ], $stats);
            }
        }
    }

    /**
     * Duration pages: /safaris/5-day-serengeti-safari
     */
    protected function generateDurationPages(array &$stats): void
    {
        $durations = [
            ['min' => 1, 'max' => 3, 'label' => 'Short'],
            ['min' => 4, 'max' => 7, 'label' => '5 Day'],
            ['min' => 8, 'max' => 12, 'label' => '10 Day'],
            ['min' => 13, 'max' => 30, 'label' => 'Extended'],
        ];

        foreach (Destination::with('country')->get() as $dest) {
            foreach ($durations as $dur) {
                $slug = Str::slug("{$dur['label']} {$dest->name} safari");
                $countryName = $dest->country?->name ?? 'Tanzania';
                $this->createPage($slug, 'duration', [
                    'title' => "{$dur['label']} {$dest->name} Safari",
                    'meta_title' => "{$dur['label']} {$dest->name} Safari - {$countryName} " . date('Y'),
                    'meta_description' => "Find the perfect {$dur['label']} safari in {$dest->name}, {$countryName}. {$dur['min']}-{$dur['max']} day itineraries with expert guides and luxury lodges.",
                    'intro_content' => "Looking for a {$dur['label']} safari in {$dest->name}? Our {$dur['min']} to {$dur['max']} day itineraries are perfectly paced to showcase the best of this incredible destination without rushing.",
                    'filter_criteria' => [
                        'destination_slug' => $dest->slug,
                        'duration_min' => $dur['min'],
                        'duration_max' => $dur['max'],
                    ],
                ], $stats);
            }
        }
    }

    /**
     * Create a page if it doesn't already exist.
     */
    protected function createPage(string $slug, string $type, array $data, array &$stats): void
    {
        if (SeoPage::where('slug', $slug)->exists()) {
            $stats['skipped']++;
            return;
        }

        SeoPage::create(array_merge($data, [
            'slug' => $slug,
            'type' => $type,
            'is_auto_generated' => true,
            'is_published' => true,
        ]));
        $stats['created']++;
    }
}
