<?php

namespace Tests\Feature;

use App\Models\Accommodation;
use App\Models\Itinerary;
use App\Models\SafariPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SafariContentSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_for_guests(): void
    {
        SafariPackage::create([
            'title' => 'Homepage Featured Safari',
            'slug' => 'homepage-featured-safari',
            'status' => 'published',
            'featured' => true,
            'short_description' => 'Featured safari on the homepage.',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Featured Safari Journeys');
        $response->assertSeeText('Homepage Featured Safari');
    }

    public function test_safari_pdf_download_returns_a_pdf_attachment(): void
    {
        $safari = SafariPackage::create([
            'title' => 'Northern Tanzania Escape',
            'slug' => 'northern-tanzania-escape',
            'status' => 'published',
            'currency' => 'USD',
            'short_description' => 'A short luxury safari overview.',
            'description' => '<p>An elegant safari brochure body.</p>',
            'seasonal_pricing' => [
                ['season' => 'High Season', 'pax_2' => 5100, 'pax_4' => 4300, 'pax_6' => 3900],
            ],
            'highlights' => ['Private guide', 'Luxury lodges'],
            'included' => ['Park fees'],
            'excluded' => ['Flights'],
        ]);

        $country = \App\Models\Country::query()->create([
            'name' => 'Tanzania',
            'slug' => 'tanzania',
        ]);

        $destination = \App\Models\Destination::query()->create([
            'country_id' => $country->id,
            'name' => 'Arusha',
            'slug' => 'arusha',
            'latitude' => -3.369683,
            'longitude' => 36.688079,
        ]);

        Itinerary::create([
            'safari_package_id' => $safari->id,
            'destination_id' => $destination->id,
            'day_number' => 1,
            'title' => 'Arrival',
            'description' => 'Arrival and briefing.',
        ]);

        $response = $this->get(route('safaris.pdf', $safari->slug));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition', 'attachment; filename=safari-itinerary.pdf');
    }

    public function test_safari_show_page_renders_highlights_and_inclusions_sections(): void
    {
        $safari = SafariPackage::create([
            'title' => 'Migration Luxury Safari',
            'slug' => 'migration-luxury-safari',
            'status' => 'published',
            'currency' => 'USD',
            'short_description' => 'This short description should only appear on cards.',
            'overview_title' => 'Your Journey',
            'description' => '<p>A premium migration experience with a <a href="https://example.com" target="_blank" rel="noopener noreferrer">private planning link</a>.</p>',
            'price' => 4200,
            'seasonal_pricing' => [
                'low' => ['pax_2' => 4200, 'pax_4' => 3600, 'pax_6' => 3200],
                'mid' => ['pax_2' => 4600, 'pax_4' => 3900, 'pax_6' => 3450],
                'high' => ['pax_2' => 5200, 'pax_4' => 4450, 'pax_6' => 3890],
            ],
            'highlights_title' => 'Safari Highlights',
            'highlights_intro' => 'A quick overview of the moments that define this trip.',
            'highlights' => ['Big Five safari', 'Luxury lodges', 'Serengeti migration'],
            'inclusions_title' => 'What Is Included And Excluded',
            'inclusions_intro' => 'Everything that is covered and what you should budget for separately.',
            'included' => ['Park fees', 'Accommodation', 'Game drives'],
            'excluded' => ['International flights', 'Travel insurance'],
        ]);

        $country = \App\Models\Country::query()->create([
            'name' => 'Tanzania',
            'slug' => 'tanzania',
        ]);

        $destination = \App\Models\Destination::query()->create([
            'country_id' => $country->id,
            'name' => 'Serengeti',
            'slug' => 'serengeti',
            'latitude' => -2.3333,
            'longitude' => 34.8333,
        ]);

        $accommodation = Accommodation::create([
            'name' => 'Serengeti River Lodge',
            'description' => 'Luxury tented lodge overlooking the plains.',
            'category' => 'luxury',
            'country_id' => $country->id,
            'destination_id' => $destination->id,
        ]);

        $accommodation->images()->create(['image_path' => 'accommodations/test-lodge.jpg']);

        Itinerary::create([
            'safari_package_id' => $safari->id,
            'destination_id' => $accommodation->destination_id,
            'accommodation_id' => $accommodation->id,
            'day_number' => 1,
            'title' => 'Arrival in Serengeti',
            'description' => 'Check-in and evening game drive.',
        ]);

        Itinerary::create([
            'safari_package_id' => $safari->id,
            'destination_id' => $accommodation->destination_id,
            'accommodation_id' => $accommodation->id,
            'day_number' => 2,
            'title' => 'Full Safari Day',
            'description' => 'Morning and afternoon drives.',
        ]);

        $response = $this->get(route('safaris.show', $safari->slug));

        $response->assertOk();
        $response->assertSeeText('Your Journey');
        $response->assertSeeText('Safari Highlights');
        $response->assertSeeText('A quick overview of the moments that define this trip.');
        $response->assertSeeText('Big Five safari');
        $response->assertSeeText('Luxury lodges');
        $response->assertSee('href="https://example.com"', false);
        $response->assertSeeText('Itinerary');
        $response->assertSeeText('Low Season');
        $response->assertSeeText('High Season');
        $response->assertSeeText('USD 4,200');
        $response->assertSeeText('What Is Included And Excluded');
        $response->assertSeeText('Everything that is covered and what you should budget for separately.');
        $response->assertSeeText('Journey Map');
        $response->assertSeeText('Route Overview');
        $response->assertSeeText('2 Stops');
        $response->assertSeeText('1 Destination: Serengeti');
        $response->assertSeeText('Route Summary');
        $response->assertSeeText('Itinerary Stops');
        $response->assertSeeText('Day 1–2 — Serengeti');
        $response->assertSeeText('Where You Will Stay');
        $response->assertSeeText('Serengeti River Lodge');
        $response->assertSeeText('Park fees');
        $response->assertSeeText('International flights');
        $response->assertSeeText('Ready to Plan Your Journey?');
        $response->assertSeeText('Download Itinerary');
        $response->assertSeeText('Speak to an Expert');
        $response->assertDontSeeText('Curated Safari Moments');
        $response->assertDontSeeText('Book This Safari');
        $response->assertDontSeeText('Inquire Now');
        $response->assertDontSeeText('This short description should only appear on cards.');
    }

    public function test_safari_show_page_renders_related_tours_from_shared_itinerary_destinations(): void
    {
        $country = \App\Models\Country::query()->create([
            'name' => 'Tanzania',
            'slug' => 'tanzania',
        ]);

        $sharedDestination = \App\Models\Destination::query()->create([
            'country_id' => $country->id,
            'name' => 'Tarangire National Park',
            'slug' => 'tarangire-national-park',
            'latitude' => -4.1626,
            'longitude' => 36.0898,
        ]);

        $otherDestination = \App\Models\Destination::query()->create([
            'country_id' => $country->id,
            'name' => 'Lake Manyara',
            'slug' => 'lake-manyara',
            'latitude' => -3.6076,
            'longitude' => 35.7576,
        ]);

        $primarySafari = SafariPackage::create([
            'title' => 'Classic Northern Circuit',
            'slug' => 'classic-northern-circuit',
            'status' => 'published',
            'short_description' => 'Main safari short description.',
        ]);

        $relatedSafari = SafariPackage::create([
            'title' => 'Tarangire Discovery',
            'slug' => 'tarangire-discovery',
            'status' => 'published',
            'short_description' => 'Related safari through the same destination.',
        ]);

        $unrelatedSafari = SafariPackage::create([
            'title' => 'Manyara Escape',
            'slug' => 'manyara-escape',
            'status' => 'published',
            'short_description' => 'Unrelated safari using a different destination.',
        ]);

        Itinerary::create([
            'safari_package_id' => $primarySafari->id,
            'destination_id' => $sharedDestination->id,
            'day_number' => 1,
            'title' => 'Arrival in Tarangire',
            'description' => 'Welcome to the park.',
        ]);

        Itinerary::create([
            'safari_package_id' => $relatedSafari->id,
            'destination_id' => $sharedDestination->id,
            'day_number' => 1,
            'title' => 'Tarangire Game Drive',
            'description' => 'Explore the park.',
        ]);

        Itinerary::create([
            'safari_package_id' => $unrelatedSafari->id,
            'destination_id' => $otherDestination->id,
            'day_number' => 1,
            'title' => 'Manyara Arrival',
            'description' => 'A different route.',
        ]);

        $response = $this->get(route('safaris.show', $primarySafari->slug));

        $response->assertOk();
        $response->assertSeeText('Related Tours');
        $response->assertSeeText('Tarangire Discovery');
        $response->assertSeeText('Related safari through the same destination.');
        $response->assertDontSeeText('Manyara Escape');
    }
}