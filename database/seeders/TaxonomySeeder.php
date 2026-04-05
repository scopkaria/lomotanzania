<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\TourType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        // ─── Countries ───
        $countries = [
            ['name' => 'Tanzania', 'description' => 'Home to the Serengeti, Kilimanjaro, and some of Africa\'s most iconic wildlife destinations.'],
            ['name' => 'Kenya', 'description' => 'Famous for the Maasai Mara, world-class game drives, and the Great Migration.'],
            ['name' => 'Uganda', 'description' => 'The Pearl of Africa — mountain gorilla trekking, Bwindi, and lush equatorial landscapes.'],
            ['name' => 'Rwanda', 'description' => 'Land of a Thousand Hills, renowned for gorilla encounters and Volcanoes National Park.'],
            ['name' => 'Zanzibar', 'description' => 'Spice Island paradise with turquoise waters, white sand, and Stone Town heritage.'],
            ['name' => 'Botswana', 'description' => 'Pristine wilderness — the Okavango Delta, Chobe elephants, and exclusive safari camps.'],
            ['name' => 'South Africa', 'description' => 'Kruger National Park, Cape Town, the Garden Route, and the Big Five.'],
            ['name' => 'Namibia', 'description' => 'Surreal desert landscapes, Etosha salt pans, and Sossusvlei dunes.'],
            ['name' => 'Mozambique', 'description' => 'Indian Ocean coastline with pristine beaches, diving, and marine life.'],
            ['name' => 'Ethiopia', 'description' => 'Ancient history, Simien Mountains, and the Danakil Depression.'],
        ];

        $countryModels = [];
        foreach ($countries as $c) {
            $countryModels[$c['name']] = Country::firstOrCreate(
                ['slug' => Str::slug($c['name'])],
                ['name' => $c['name'], 'description' => $c['description']]
            );
        }

        // ─── Destinations ───
        $destinations = [
            ['name' => 'Serengeti National Park', 'country' => 'Tanzania', 'description' => 'Endless plains of golden savanna, the Great Migration, and unparalleled big cat sightings.'],
            ['name' => 'Ngorongoro Crater', 'country' => 'Tanzania', 'description' => 'The world\'s largest unflooded volcanic caldera — a natural amphitheatre teeming with wildlife.'],
            ['name' => 'Mount Kilimanjaro', 'country' => 'Tanzania', 'description' => 'Africa\'s highest peak at 5,895m. A bucket-list trek through five distinct climate zones.'],
            ['name' => 'Tarangire National Park', 'country' => 'Tanzania', 'description' => 'Ancient baobab groves, massive elephant herds, and bird-rich swamplands.'],
            ['name' => 'Lake Manyara National Park', 'country' => 'Tanzania', 'description' => 'Famous for tree-climbing lions, flamingo-lined shores, and diverse habitats.'],
            ['name' => 'Selous Game Reserve', 'country' => 'Tanzania', 'description' => 'One of Africa\'s largest protected areas — wild dogs, boat safaris, and remote wilderness.'],
            ['name' => 'Ruaha National Park', 'country' => 'Tanzania', 'description' => 'Tanzania\'s largest national park — rugged landscapes and incredible predator density.'],
            ['name' => 'Maasai Mara', 'country' => 'Kenya', 'description' => 'Kenya\'s premier game reserve and a front-row seat to the Great Migration river crossings.'],
            ['name' => 'Amboseli National Park', 'country' => 'Kenya', 'description' => 'Stunning Kilimanjaro backdrops and large elephant herds in open savanna.'],
            ['name' => 'Bwindi Impenetrable Forest', 'country' => 'Uganda', 'description' => 'UNESCO World Heritage Site and home to nearly half of the world\'s mountain gorillas.'],
            ['name' => 'Volcanoes National Park', 'country' => 'Rwanda', 'description' => 'Dramatic volcanic landscapes and intimate gorilla trekking experiences.'],
            ['name' => 'Okavango Delta', 'country' => 'Botswana', 'description' => 'A vast inland delta — mokoro canoe safaris, water-based game viewing, and untouched wilderness.'],
            ['name' => 'Kruger National Park', 'country' => 'South Africa', 'description' => 'South Africa\'s flagship reserve — Big Five, diverse ecosystems, and accessible safari lodges.'],
            ['name' => 'Sossusvlei', 'country' => 'Namibia', 'description' => 'Towering red sand dunes, dead vleis, and surreal desert photography opportunities.'],
            ['name' => 'Stone Town', 'country' => 'Zanzibar', 'description' => 'A labyrinth of narrow streets, carved doorways, spice markets, and Swahili heritage.'],
        ];

        foreach ($destinations as $d) {
            Destination::firstOrCreate(
                ['slug' => Str::slug($d['name'])],
                [
                    'name' => $d['name'],
                    'country_id' => $countryModels[$d['country']]->id,
                    'description' => $d['description'],
                ]
            );
        }

        // ─── Tour Types (Experiences) ───
        $tourTypes = [
            ['name' => 'Wildlife Safari', 'description' => 'Classic game drives through Africa\'s most iconic national parks and reserves.'],
            ['name' => 'Great Migration', 'description' => 'Follow the annual movement of millions of wildebeest and zebra across the Serengeti-Mara ecosystem.'],
            ['name' => 'Luxury Safari', 'description' => 'Five-star lodges, private concessions, and bespoke itineraries for the discerning traveller.'],
            ['name' => 'Honeymoon Safari', 'description' => 'Romantic escapes combining bush and beach with intimate camps and sunset dinners.'],
            ['name' => 'Cultural Immersion', 'description' => 'Authentic encounters with Maasai, Hadzabe, and other indigenous communities.'],
            ['name' => 'Beach & Island', 'description' => 'Zanzibar, Pemba, and coastal retreats — snorkelling, diving, and tropical relaxation.'],
            ['name' => 'Mountain Trekking', 'description' => 'Kilimanjaro, Mount Meru, and East Africa\'s dramatic highland trails.'],
            ['name' => 'Family Safari', 'description' => 'Child-friendly lodges, shorter drives, and educational bush activities for all ages.'],
            ['name' => 'Photography Safari', 'description' => 'Specialised vehicles, expert guides, and golden-hour positioning for stunning wildlife shots.'],
            ['name' => 'Walking Safari', 'description' => 'Guided bush walks through wild terrain — track animals on foot with armed rangers.'],
            ['name' => 'Bird Watching', 'description' => 'Over 1,100 species across Tanzania — from flamingos to fish eagles and rare endemics.'],
            ['name' => 'Gorilla Trekking', 'description' => 'Face-to-face encounters with mountain gorillas in the misty forests of Uganda and Rwanda.'],
        ];

        foreach ($tourTypes as $t) {
            TourType::firstOrCreate(
                ['slug' => Str::slug($t['name'])],
                ['name' => $t['name'], 'description' => $t['description']]
            );
        }

        // ─── Categories (Budget levels) ───
        $categories = [
            ['name' => 'Budget', 'description' => 'Affordable adventures without compromising on wildlife. Camping and basic lodges.'],
            ['name' => 'Mid-Range', 'description' => 'Comfortable lodges with quality service, great food, and prime locations.'],
            ['name' => 'Luxury', 'description' => 'Premium lodges and tented camps with exceptional service and exclusive locations.'],
            ['name' => 'Ultra-Luxury', 'description' => 'The finest safari experiences — private concessions, personal butlers, and helicopter transfers.'],
            ['name' => 'Backpacker', 'description' => 'No-frills group safaris and shared camping for budget-conscious travellers.'],
            ['name' => 'Group Tour', 'description' => 'Scheduled departures with shared vehicles and like-minded travellers.'],
            ['name' => 'Private Safari', 'description' => 'Exclusive use of vehicle and guide — your schedule, your pace.'],
            ['name' => 'Solo Traveller', 'description' => 'Tailored itineraries and room-share options for independent explorers.'],
            ['name' => 'Couple Getaway', 'description' => 'Romantic packages with sunset drives, spa treatments, and intimate dining.'],
            ['name' => 'Premium', 'description' => 'A step above mid-range — boutique lodges, private guides, and curated touches.'],
        ];

        foreach ($categories as $c) {
            Category::firstOrCreate(
                ['slug' => Str::slug($c['name'])],
                ['name' => $c['name'], 'description' => $c['description']]
            );
        }

        // ─── Accommodations ───
        // Lookup destination IDs for linking
        $destMap = Destination::pluck('id', 'slug')->toArray();

        $accommodations = [
            ['name' => 'Serengeti Serena Safari Lodge', 'category' => 'Luxury', 'country' => 'Tanzania', 'destination' => 'serengeti-national-park', 'description' => 'Perched on a ridge overlooking the Serengeti plains with panoramic views of the migration.'],
            ['name' => 'Ngorongoro Crater Lodge', 'category' => 'Ultra-Luxury', 'country' => 'Tanzania', 'destination' => 'ngorongoro-crater', 'description' => 'Opulent Maasai-inspired suites on the crater rim with personal butler service.'],
            ['name' => 'Four Seasons Safari Lodge', 'category' => 'Ultra-Luxury', 'country' => 'Tanzania', 'destination' => 'serengeti-national-park', 'description' => 'Five-star resort in the heart of the Serengeti with infinity pool overlooking a waterhole.'],
            ['name' => 'Tarangire Treetops', 'category' => 'Luxury', 'country' => 'Tanzania', 'destination' => 'tarangire-national-park', 'description' => 'Elevated treehouses among ancient baobabs in Tarangire\'s wildlife corridor.'],
            ['name' => 'Kigali Serena Hotel', 'category' => 'Mid-Range', 'country' => 'Rwanda', 'destination' => 'volcanoes-national-park', 'description' => 'Elegant city hotel in the heart of Kigali — perfect base for gorilla trekking.'],
            ['name' => 'Maasai Mara Basecamp', 'category' => 'Mid-Range', 'country' => 'Kenya', 'destination' => 'maasai-mara', 'description' => 'Eco-friendly tented camp along the Mara River with walking distance to game areas.'],
            ['name' => 'Zanzibar Beach Resort', 'category' => 'Luxury', 'country' => 'Zanzibar', 'destination' => 'stone-town', 'description' => 'Beachfront tropical retreat with direct access to white sand beaches and coral reefs.'],
            ['name' => 'Lake Manyara Tree Lodge', 'category' => 'Luxury', 'country' => 'Tanzania', 'destination' => 'lake-manyara-national-park', 'description' => 'Intimate treehouse-style suites tucked into an ancient mahogany forest.'],
            ['name' => 'Selous Riverside Camp', 'category' => 'Budget', 'country' => 'Tanzania', 'destination' => 'selous-game-reserve', 'description' => 'Simple tented camp on the Rufiji River with boat safari access.'],
            ['name' => 'Kilimanjaro Mountain Hut', 'category' => 'Budget', 'country' => 'Tanzania', 'destination' => 'mount-kilimanjaro', 'description' => 'Basic mountain accommodation along the Marangu route to the summit.'],
            ['name' => 'Bwindi Lodge', 'category' => 'Luxury', 'country' => 'Uganda', 'destination' => 'bwindi-impenetrable-forest', 'description' => 'Award-winning eco-lodge on the edge of Bwindi Impenetrable Forest.'],
            ['name' => 'Okavango Bush Camp', 'category' => 'Mid-Range', 'country' => 'Botswana', 'destination' => 'okavango-delta', 'description' => 'Remote island camp accessible only by light aircraft — mokoro and walking safaris.'],
        ];

        foreach ($accommodations as $a) {
            Accommodation::firstOrCreate(
                ['slug' => Str::slug($a['name'])],
                [
                    'name' => $a['name'],
                    'category' => $a['category'],
                    'country_id' => $countryModels[$a['country']]->id,
                    'destination_id' => $destMap[$a['destination']] ?? 0,
                    'description' => $a['description'],
                ]
            );
        }

        $this->command->info('Seeded: ' . count($countries) . ' countries, ' . count($destinations) . ' destinations, ' . count($tourTypes) . ' experiences, ' . count($categories) . ' budget levels, ' . count($accommodations) . ' accommodations.');
    }
}
