<?php

/**
 * Import / update accommodations with detailed SEO content.
 * Run: php import_accommodations.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Accommodation;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Support\Str;

$tanzania = Country::where('name', 'Tanzania')->first();
if (! $tanzania) {
    die("Tanzania country not found.\n");
}

// Ensure Karatu and Mto wa Mbu destinations exist
$extraDestinations = [
    [
        'name' => 'Karatu',
        'slug' => 'karatu',
        'description' => '<p>Nestled between the Ngorongoro Conservation Area and Lake Manyara, Karatu is a lush highland town surrounded by coffee plantations, wheat fields, and remnant montane forest. At 1,500 meters above sea level, it enjoys a cool, pleasant climate that makes it the ideal overnight stop on the northern safari circuit. The town serves as a gateway to the Ngorongoro Crater and offers a welcome change of pace — visitors can walk through flowering gardens, visit the Iraqw cultural heritage site, or tour a working coffee estate before heading out on their next game drive.</p>',
        'country_id' => $tanzania->id,
        'meta_title' => 'Karatu, Tanzania — Safari Gateway to Ngorongoro Crater',
        'meta_description' => 'Explore Karatu, a lush highland town between Ngorongoro Crater and Lake Manyara. Coffee farms, cool climate, and mid-range lodges make it the perfect safari stopover.',
        'meta_keywords' => 'Karatu Tanzania, Karatu lodges, Ngorongoro gateway, safari stopover Karatu',
    ],
    [
        'name' => 'Mto wa Mbu',
        'slug' => 'mto-wa-mbu',
        'description' => '<p>Mto wa Mbu — Swahili for "River of Mosquitoes" — is a vibrant, multicultural market town at the base of the Great Rift Valley escarpment. Home to over 120 ethnic groups, it is one of the most linguistically diverse settlements in Africa. The town sits at the entrance to Lake Manyara National Park and is surrounded by banana plantations irrigated by the Mto wa Mbu river. Visitors can explore the lively open-air market, take a guided village walk through rice paddies and palm-wine breweries, or use the town as a base for day trips to Lake Manyara, Ngorongoro, and Tarangire.</p>',
        'country_id' => $tanzania->id,
        'meta_title' => 'Mto wa Mbu, Tanzania — Cultural Village & Lake Manyara Gateway',
        'meta_description' => 'Discover Mto wa Mbu, a multicultural market town at the entrance to Lake Manyara National Park. Village walks, banana plantations, and authentic Tanzanian culture.',
        'meta_keywords' => 'Mto wa Mbu Tanzania, Lake Manyara gateway, village walk Mto wa Mbu, cultural tourism Tanzania',
    ],
];

foreach ($extraDestinations as $dest) {
    $existing = Destination::where('slug', $dest['slug'])->first();
    if (! $existing) {
        Destination::create($dest);
        echo "Created destination: {$dest['name']}\n";
    } else {
        echo "Destination already exists: {$dest['name']}\n";
    }
}

// Reload destinations
$destinations = Destination::pluck('id', 'name')->toArray();

// Accommodation data with detailed, unique SEO content
$accommodations = [
    [
        'name' => 'Africa Safari Karatu',
        'category' => 'mid-range',
        'destination' => 'Karatu',
        'description' => '<p>Africa Safari Karatu is a family-owned lodge set on a working coffee estate in the cool Karatu highlands, just 15 minutes from the Ngorongoro Conservation Area gate. The property features 35 spacious rooms spread across single-storey stone cottages, each with private verandah, en-suite bathroom with hot rain shower, and views over manicured gardens toward the Oldeani volcano.</p>
<p>The lodge\'s farmhouse restaurant serves home-grown organic produce — fresh salads from the kitchen garden, roasted Karatu coffee, and Tanzanian specialties like banana stew and grilled tilapia. After a dusty day on the crater floor, guests unwind at the poolside bar or take a guided walk through the coffee plantation to learn about bean-to-cup processing.</p>
<p>Africa Safari Karatu strikes a balance between comfort and authenticity that bigger chain lodges often miss. The staff, many of whom are from surrounding Iraqw communities, bring genuine warmth and local knowledge to every interaction. It\'s a lodge that feels like staying at a friend\'s country house — one that happens to be 20 minutes from the world\'s largest unflooded caldera.</p>',
        'meta_title' => 'Africa Safari Karatu Lodge — Coffee Estate Stay Near Ngorongoro',
        'meta_description' => 'Stay at Africa Safari Karatu, a mid-range lodge on a working coffee farm just 15 min from Ngorongoro Crater. Stone cottages, organic dining, pool & plantation walks.',
        'meta_keywords' => 'Africa Safari Karatu, Karatu lodge, Ngorongoro accommodation, coffee estate lodge Tanzania, mid-range safari lodge',
        'focus_keyword' => 'Africa Safari Karatu',
    ],
    [
        'name' => 'Ngorongoro Farm House',
        'category' => 'mid-range',
        'destination' => 'Ngorongoro Crater',
        'description' => '<p>Ngorongoro Farm House sits on a 500-acre coffee plantation on the outer slopes of the Ngorongoro highlands, offering a colonial-era farm atmosphere blended with modern comforts. The property features 52 rooms in converted farm buildings and standalone cottages, each decorated with local Tanzanian fabrics, carved headboards, and large windows framing views of the estate\'s coffee rows and flower gardens.</p>
<p>Dining at the Farm House revolves around estate-grown ingredients — freshly roasted coffee at sunrise, garden-picked vegetables at lunch, and candlelit three-course dinners featuring Tanzanian beef tenderloin and homemade passion fruit sorbet. The on-site coffee tour walks guests through the entire cultivation process, from cherry picking to roasting, ending with a cupping session.</p>
<p>What sets Ngorongoro Farm House apart is its working-farm character. Guests hear roosters at dawn, see farmhands tending rows of Arabica seedlings, and can walk freely through orchards of avocado, mango, and macadamia. It\'s not a polished five-star experience — it\'s something better: an honest, characterful Tanzanian farm stay at the doorstep of one of Africa\'s greatest natural wonders.</p>',
        'meta_title' => 'Ngorongoro Farm House — Coffee Plantation Lodge Near the Crater',
        'meta_description' => 'Experience Ngorongoro Farm House, a charming mid-range lodge on a 500-acre coffee plantation. Farm-to-table dining, estate tours & easy access to Ngorongoro Crater.',
        'meta_keywords' => 'Ngorongoro Farm House, Ngorongoro lodge, coffee plantation lodge, farm stay Ngorongoro, mid-range Ngorongoro accommodation',
        'focus_keyword' => 'Ngorongoro Farm House',
    ],
    [
        'name' => 'Moivaro Arusha Lodge',
        'category' => 'mid-range',
        'destination' => 'Arusha Town',
        'description' => '<p>Moivaro Arusha Lodge occupies a tranquil 5-acre estate on the outskirts of Arusha, shaded by towering jacaranda and flame trees just 25 minutes from Kilimanjaro International Airport. The lodge\'s 42 rooms are housed in individual plantation-style bungalows with thatched roofs, timber floors, and private wooden decks overlooking tropical gardens alive with sunbirds and colobus monkeys.</p>
<p>The open-air restaurant serves a fusion of Tanzanian and international cuisine — think slow-braised goat with coconut rice, grilled Nile perch with lime chutney, and fresh tropical fruit platters. The bar, built around a century-old fig tree, is the kind of place where safari stories get traded between sundowners and starlight.</p>
<p>Moivaro is the ideal pre- or post-safari base. It\'s close enough to Arusha for a quick trip to the Maasai market or Cultural Heritage Centre, yet secluded enough that the loudest sound at night is a bushbaby calling from the garden canopy. The lodge also operates a city-centre coffee house for guests with early-morning departures.</p>',
        'meta_title' => 'Moivaro Arusha Lodge — Garden Retreat Near Kilimanjaro Airport',
        'meta_description' => 'Relax at Moivaro Arusha Lodge, a peaceful garden estate with thatched bungalows just 25 min from KIA. Perfect pre/post-safari base with restaurant, bar & lush grounds.',
        'meta_keywords' => 'Moivaro Arusha Lodge, Arusha accommodation, pre-safari lodge Arusha, garden lodge Tanzania, Kilimanjaro airport hotel',
        'focus_keyword' => 'Moivaro Arusha Lodge',
    ],
    [
        'name' => 'Zuri Serengeti Camp',
        'category' => 'mid-range',
        'destination' => 'Serengeti National Park',
        'description' => '<p>Zuri Serengeti Camp is a semi-permanent tented camp positioned in the central Seronera valley of the Serengeti, one of the richest year-round wildlife areas on Earth. The camp\'s 12 spacious canvas tents sit on raised wooden platforms with full-length mesh windows, each offering uninterrupted views across the golden savannah where lion prides, leopards, and vast herds of wildebeest roam daily.</p>
<p>Each tent features a king-size bed with quality linens, solar-powered lighting, a writing desk, and an en-suite bathroom with bush-style hot shower and flush toilet. The central mess tent serves hearty breakfasts before dawn game drives and three-course dinners by lantern light — think grilled Serengeti lamb chops, roasted root vegetables, and warm banana bread with Tanzanian honey.</p>
<p>Zuri\'s strength is its location and its size. With only 12 tents, game drives feel private rather than like a convoy. Guides are Serengeti veterans who track big cats by reading vulture flight patterns and hyena calls. Guests regularly witness kills, river crossings during migration season (June–October), and the kind of raw, unfiltered wildlife encounters that define a true East African safari.</p>',
        'meta_title' => 'Zuri Serengeti Camp — Intimate Tented Safari in Seronera Valley',
        'meta_description' => 'Stay at Zuri Serengeti Camp, a 12-tent mid-range camp in central Serengeti\'s Seronera valley. Year-round big cat sightings, bush dining & expert-guided game drives.',
        'meta_keywords' => 'Zuri Serengeti Camp, Serengeti tented camp, Seronera accommodation, mid-range Serengeti camp, safari tent Serengeti',
        'focus_keyword' => 'Zuri Serengeti Camp',
    ],
    [
        'name' => 'Tloma Lodge',
        'category' => 'mid-range',
        'destination' => 'Karatu',
        'description' => '<p>Tloma Lodge is a hillside retreat nestled among the forested slopes above Karatu, with panoramic views stretching across the patchwork farmlands of the Great Rift Valley. The lodge features 36 cottage-style rooms built from local river stone and reclaimed hardwood, each with a fireplace, handcrafted four-poster bed, and a private balcony where morning coffee comes with views of mist rolling through the valley below.</p>
<p>The lodge\'s signature restaurant sources ingredients from its own organic vegetable garden and nearby smallholder farms. Expect dishes like pumpkin soup with roasted cumin, herb-crusted chicken with sautéed greens, and a legendary carrot cake that has become a talking point among returning guests. The wine list features South African and Tanzanian selections, and the stone-walled bar hosts convivial evenings around a crackling fire.</p>
<p>Tloma stands out for its community connection. The lodge is part of the Tanganyika Wilderness Camps group and employs almost exclusively from the surrounding Iraqw villages. A portion of each booking funds the adjacent Tloma Primary School. Guests can visit classrooms, join a guided village walk, or hike through the nearby Ngorongoro Forest Reserve — a rare patch of Afromontane forest home to elephant, buffalo, and over 200 bird species.</p>',
        'meta_title' => 'Tloma Lodge Karatu — Hillside Retreat with Rift Valley Views',
        'meta_description' => 'Discover Tloma Lodge, a stone-and-timber hillside retreat in Karatu with Rift Valley views, organic farm dining, fireplaces & community-supported tourism.',
        'meta_keywords' => 'Tloma Lodge, Karatu accommodation, Rift Valley lodge, Ngorongoro area lodge, community lodge Tanzania',
        'focus_keyword' => 'Tloma Lodge Karatu',
    ],
    [
        'name' => 'Land of Nature Camp',
        'category' => 'mid-range',
        'destination' => 'Serengeti National Park',
        'description' => '<p>Land of Nature Camp is a mobile-style tented camp set in the southern Serengeti plains near the Ndutu area, strategically positioned along the great wildebeest migration route. The camp\'s 10 walk-in safari tents are pitched on the open grassland with nothing between guests and the endless horizon — no fences, no walls, just canvas and the sound of the African night.</p>
<p>Each tent is furnished with comfortable twin or double beds, solar lanterns, bedside tables, and a rear en-suite with bucket shower and eco-friendly bush toilet. Meals are served communally in the open-air dining tent: full English breakfasts, packed safari lunches, and evening barbecues featuring grilled meats, fresh chapati, and seasonal fruits under a canopy of stars.</p>
<p>This camp is built for wildlife purists. The southern Serengeti between December and March hosts the calving season — over 8,000 wildebeest are born daily, attracting hungry predators in extraordinary concentrations. Land of Nature Camp puts guests right in the middle of this spectacle. Night drives and walking safaris (with armed rangers) offer perspectives impossible from a vehicle, and the camp\'s small size means personal attention from guides who know every kopje and waterhole in the area.</p>',
        'meta_title' => 'Land of Nature Camp — Mobile Tented Camp in Southern Serengeti',
        'meta_description' => 'Experience Land of Nature Camp, a 10-tent mobile camp in the southern Serengeti near Ndutu. Front-row seats to the wildebeest calving season, bush dining & walking safaris.',
        'meta_keywords' => 'Land of Nature Camp, Serengeti mobile camp, Ndutu camp, wildebeest calving Serengeti, budget tented camp Serengeti',
        'focus_keyword' => 'Land of Nature Camp Serengeti',
    ],
    [
        'name' => 'Ritzy Arcadia Cottages',
        'category' => 'mid-range',
        'destination' => 'Serengeti National Park',
        'description' => '<p>Ritzy Arcadia Cottages is a modern safari hotel located just outside the Naabi Hill gate of the Serengeti, offering comfortable rooms with contemporary finishes at an accessible price point. The property features 20 self-contained cottages arranged around a central courtyard garden, each with tiled floors, air conditioning, en-suite bathroom with hot water, and a small private terrace facing the surrounding acacia woodland.</p>
<p>The on-site restaurant blends Tanzanian and continental cooking — beef pilau with cardamom and cloves, grilled chicken with ugali, and fresh juices from mango, passion fruit, and tamarind. Wi-Fi is available in the reception area, and there\'s a small gift shop selling Tingatinga paintings and Maasai beadwork from local artisans.</p>
<p>Ritzy Arcadia works well for travellers who want a solid, clean base near the Serengeti without the premium of in-park lodges. Morning game drives depart early enough to reach central Seronera by sunrise, and the hotel\'s tour desk arranges full-day drives, balloon safaris, and cultural visits to nearby Maasai bomas. It\'s practical, well-run, and delivers genuine value on the northern circuit.</p>',
        'meta_title' => 'Ritzy Arcadia Cottages — Comfortable Stay Near Serengeti Gate',
        'meta_description' => 'Book Ritzy Arcadia Cottages near Serengeti\'s Naabi Hill gate. Modern self-contained cottages, Tanzanian cuisine, tour desk & affordable northern circuit base.',
        'meta_keywords' => 'Ritzy Arcadia Cottages, Serengeti gate hotel, Naabi Hill accommodation, affordable Serengeti lodge, Serengeti hotel',
        'focus_keyword' => 'Ritzy Arcadia Cottages Serengeti',
    ],
    [
        'name' => 'Ritzy Arcadia Cottage',
        'category' => 'mid-range',
        'destination' => 'Mto wa Mbu',
        'description' => '<p>Ritzy Arcadia Cottage Mto wa Mbu is a boutique guesthouse in the heart of the culturally rich Mto wa Mbu town, offering a peaceful garden retreat within walking distance of the village\'s famous open-air market and banana plantations. The property has 12 en-suite rooms in whitewashed cottage blocks surrounded by bougainvillea hedges and mature palm trees, with a swimming pool at the centre of the grounds.</p>
<p>Rooms are clean and well-appointed with mosquito nets, ceiling fans, tiled bathrooms with hot showers, and screened windows. The restaurant serves generous portions of Tanzanian home cooking — banana beer-braised beef, fresh lake fish with coconut sauce, and chapati with spiced lentil daal. Breakfast includes local fruits, eggs to order, and strong Tanzanian coffee.</p>
<p>The cottage\'s real advantage is location. Guests can step out the gate and be at the Lake Manyara National Park entrance in five minutes, hire a bicycle to ride through rice paddies to a Maasai village, or join a guided walk through the papyrus-fringed streams where over 400 bird species have been recorded. It\'s an ideal overnight stop between Tarangire and Ngorongoro — authentic, affordable, and genuinely connected to the community around it.</p>',
        'meta_title' => 'Ritzy Arcadia Cottage Mto wa Mbu — Village Guesthouse at Lake Manyara',
        'meta_description' => 'Stay at Ritzy Arcadia Cottage in Mto wa Mbu, a garden guesthouse near Lake Manyara National Park. Pool, home-cooked meals, village walks & authentic cultural experiences.',
        'meta_keywords' => 'Ritzy Arcadia Cottage Mto wa Mbu, Lake Manyara accommodation, Mto wa Mbu guesthouse, village stay Tanzania, budget lodge Lake Manyara',
        'focus_keyword' => 'Ritzy Arcadia Cottage Mto wa Mbu',
    ],
];

$created = 0;
$updated = 0;

foreach ($accommodations as $data) {
    $destName = $data['destination'];
    unset($data['destination']);

    if (! isset($destinations[$destName])) {
        echo "ERROR: Destination '{$destName}' not found. Skipping {$data['name']}.\n";
        continue;
    }

    $data['country_id'] = $tanzania->id;
    $data['destination_id'] = $destinations[$destName];
    $data['slug'] = Str::slug($data['name']);

    $focusKeyword = $data['focus_keyword'] ?? null;
    unset($data['focus_keyword']);

    $existing = Accommodation::where('name', $data['name'])->first();

    if ($existing) {
        $existing->update($data);
        $accommodation = $existing;
        $updated++;
        echo "Updated: {$data['name']}\n";
    } else {
        $accommodation = Accommodation::create($data);
        $created++;
        echo "Created: {$data['name']}\n";
    }

    // Save SEO meta (focus_keyword)
    if ($focusKeyword) {
        $accommodation->saveSeoMeta(['focus_keyword' => $focusKeyword]);
    }
}

echo "\nDone! Created: {$created}, Updated: {$updated}\n";
