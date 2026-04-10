<?php
/**
 * Seed itineraries & destination pivots for safari IDs 4-19
 * 
 * Destinations:    1=Serengeti, 2=Ngorongoro, 3=Arusha NP, 4=Arusha Town, 5=Tarangire,
 *                  6=Lake Manyara, 7=Kilimanjaro, 18=Karatu, 19=Mto wa Mbu, 20=Zanzibar, 22=Materuni
 * Accommodations:  1=Moivaro Arusha, 3=Zuri Serengeti, 4=Serengeti Serena, 5=Ngorongoro Crater Lodge,
 *                  7=Tarangire Treetops, 11=Lake Manyara Tree, 13=Kili Mountain Hut,
 *                  16=Ngorongoro Farm House, 17=Tloma Lodge, 18=Land of Nature Camp
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SafariPackage;
use App\Models\Itinerary;
use Illuminate\Support\Facades\DB;

// ──────────────────────────────────────────────────────
// ITINERARY DATA: safari_id => [ [day, title, description, destination_id, accommodation_id] ]
// ──────────────────────────────────────────────────────
$data = [

    // ID 4: The Great Migration Encounter — 6-Day Serengeti Safari
    4 => [
        'destinations' => [4, 5, 1, 2, 18],
        'itinerary' => [
            [1, 'Arrival in Arusha', 'Welcome at Kilimanjaro International Airport. Your private guide greets you and transfers you to your luxury lodge in Arusha, where you enjoy a welcome dinner and safari briefing under the African stars.', 4, 1],
            [2, 'Arusha to Tarangire National Park', 'Drive south into the sweeping baobab-studded landscapes of Tarangire. Track elephant herds along the Tarangire River, spot tree-climbing lions and prolific birdlife. Afternoon game drive continues until sunset.', 5, 7],
            [3, 'Tarangire to Central Serengeti', 'Early morning departure through the Great Rift Valley and Ngorongoro Highlands to reach the vast Serengeti plains. Afternoon game drive in the Seronera Valley seeking leopard, lion prides and cheetah on the open grasslands.', 1, 4],
            [4, 'Full Day — Serengeti Migration Zones', 'A full day dedicated to the Great Migration. Follow your expert guide to the current migration hot-spots — witness thousands of wildebeest and zebra on the move, river crossings, and the predators that shadow the herds. Optional sunrise hot-air balloon flight.', 1, 4],
            [5, 'Serengeti to Ngorongoro Crater', 'Morning game drive en route to the Ngorongoro Conservation Area. Descend into the crater — a natural amphitheatre sheltering the densest concentration of wildlife in Africa. Spot the Big Five all in one day.', 2, 5],
            [6, 'Ngorongoro to Arusha — Departure', 'After breakfast, drive through the lush Karatu highlands back to Arusha. Enjoy a farewell lunch before your transfer to Kilimanjaro International Airport for your onward journey.', 4, null],
        ],
    ],

    // ID 5: The Ultimate Northern Circuit — 8-Day Big Five Safari
    5 => [
        'destinations' => [4, 6, 5, 1, 2, 18],
        'itinerary' => [
            [1, 'Arrival in Arusha', 'Arrive at Kilimanjaro International Airport. Private transfer to your Arusha lodge with welcome dinner and detailed safari briefing.', 4, 1],
            [2, 'Lake Manyara National Park', 'Drive to the breathtaking Lake Manyara, shimmering at the base of the Rift Valley escarpment. Search for tree-climbing lions, hippo pods, flamingo flocks and over 400 bird species in this compact gem.', 6, 17],
            [3, 'Tarangire National Park', 'A full day in Tarangire — famous for its ancient baobabs and massive elephant herds. Game drives along the river reveal lion, leopard, python and herds of wildebeest and zebra.', 5, 7],
            [4, 'Karatu to Serengeti — Olduvai Gorge', 'Cross the Ngorongoro Highlands, stopping at Olduvai Gorge — the "Cradle of Mankind". Descend into the Serengeti for afternoon game drives across the endless plains.', 1, 3],
            [5, 'Full Day in the Serengeti', 'Explore the Serengeti from dawn to dusk. Morning and afternoon game drives revealing lion prides, leopard in sausage trees, cheetah hunts, and vast herds of herbivores. Optional balloon safari at sunrise.', 1, 3],
            [6, 'Serengeti — Northern Sector', 'Drive north toward the Mara River region. This area is famous for dramatic wildebeest river crossings. Big cats and crocodile sightings are frequent. Picnic lunch on the savanna.', 1, 3],
            [7, 'Ngorongoro Crater Descent', 'Early departure for the Ngorongoro Crater. Descend 600 metres into the caldera for a half-day game drive — spot black rhino, lion, elephant, buffalo and hippo in this extraordinary natural arena.', 2, 16],
            [8, 'Ngorongoro to Arusha — Departure', 'Morning drive through the green Karatu highlands back to Arusha. Farewell lunch, souvenir shopping, and transfer to Kilimanjaro Airport.', 4, null],
        ],
    ],

    // ID 6: Romantic Serengeti & Zanzibar — 7-Day Honeymoon Safari
    6 => [
        'destinations' => [4, 5, 1, 2, 20],
        'itinerary' => [
            [1, 'Arrival in Arusha', 'Welcome to Tanzania! Private VIP transfer to a boutique lodge in Arusha. Enjoy a romantic welcome dinner with views of Mount Meru.', 4, 1],
            [2, 'Tarangire & Sundowner', 'Morning game drive in Tarangire among elephant herds and baobab groves. Late afternoon sundowner cocktails on a private lookout over the Rift Valley.', 5, 7],
            [3, 'Serengeti — Private Bush Camp', 'Fly by charter to the Serengeti. Settle into your luxury tented camp with private plunge pool. Afternoon game drive followed by a bush dinner under a canopy of stars.', 1, 6],
            [4, 'Serengeti — Balloon & Bush Breakfast', 'Rise before dawn for a hot-air balloon flight over the Serengeti. Land for a champagne bush breakfast on the savanna. Afternoon game drive focused on big cat territory.', 1, 6],
            [5, 'Ngorongoro Crater', 'Fly or drive to Ngorongoro. Descend into the crater for a romantic picnic lunch surrounded by the Big Five. Overnight at the iconic crater rim lodge.', 2, 5],
            [6, 'Fly to Zanzibar — Beach & Spice', 'Morning flight to Zanzibar. Transfer to your beachfront resort. Afternoon at leisure — relax on white-sand beaches or explore Stone Town. Sunset dhow cruise.', 20, null],
            [7, 'Zanzibar & Departure', 'Morning spa treatment and beach time. Optional spice tour or snorkelling before afternoon transfer to Zanzibar Airport for your onward journey.', 20, null],
        ],
    ],

    // ID 7: Ngorongoro & Tarangire — 5-Day Family Safari Adventure
    7 => [
        'destinations' => [4, 5, 6, 2, 18],
        'itinerary' => [
            [1, 'Arrival in Arusha', 'Family welcome at Kilimanjaro Airport. Transfer to your family-friendly lodge in Arusha with kids\' activity pack and safari briefing.', 4, 1],
            [2, 'Lake Manyara — Flamingos & Tree Lions', 'Drive to Lake Manyara National Park — perfect for families with shorter drives. Kids love spotting flamingos, baboons, blue monkeys, and the famous tree-climbing lions.', 6, 17],
            [3, 'Tarangire — Giants of Africa', 'A full day among Tarangire\'s elephant herds — the largest gatherings in East Africa. Children are amazed by the ancient baobab trees and diverse birdlife. Guided family bush walk.', 5, 7],
            [4, 'Ngorongoro Crater — Big Five Day', 'Descend into the Ngorongoro Crater — a natural wonder. Spot lion, elephant, buffalo, rhino and hippo. Family picnic lunch on the crater floor. Afternoon drive with junior ranger activity.', 2, 16],
            [5, 'Karatu to Arusha — Departure', 'Morning visit to a Maasai cultural boma — dancing, beading and storytelling with Maasai children. Drive back to Arusha for departure.', 18, null],
        ],
    ],

    // ID 8: Kilimanjaro Marangu Route — 5-Day Classic Summit Trek
    8 => [
        'destinations' => [7],
        'itinerary' => [
            [1, 'Marangu Gate to Mandara Hut (2,720 m)', 'Register at Marangu Gate (1,840 m) and begin your trek through lush montane rainforest. Spot Colobus monkeys and exotic birds. Arrive at Mandara Hut — the only route with permanent sleeping huts.', 7, 13],
            [2, 'Mandara Hut to Horombo Hut (3,720 m)', 'Ascend through heather and moorland with stunning views of Mawenzi Peak. The landscape opens to alpine meadow with giant lobelias and groundsel. Arrive at Horombo for acclimatisation.', 7, 13],
            [3, 'Acclimatisation Day at Horombo', 'Critical acclimatisation day. Optional hike toward Zebra Rocks (4,000 m) for altitude training. Afternoon rest and preparation for summit night. Guides check health and monitor altitude symptoms.', 7, 13],
            [4, 'Horombo to Kibo Hut (4,703 m) & Summit Night', 'Cross the alpine desert "Saddle" between Mawenzi and Kibo. Arrive at Kibo Hut by afternoon. After an early dinner, begin the midnight summit push through scree and switchbacks to Uhuru Peak (5,895 m) at sunrise.', 7, 13],
            [5, 'Summit Descent to Marangu Gate', 'Descend from the summit through all climate zones back to Horombo for lunch, then continue to Marangu Gate. Receive your summit certificate and transfer back to Arusha for celebration dinner.', 7, null],
        ],
    ],

    // ID 9: Kilimanjaro Machame Route — 7-Day Whiskey Trail
    9 => [
        'destinations' => [7],
        'itinerary' => [
            [1, 'Machame Gate to Machame Camp (3,000 m)', 'Begin at Machame Gate (1,800 m) and hike through pristine rainforest alive with birdsong and blue monkeys. Arrive at Machame Camp in the upper forest zone.', 7, null],
            [2, 'Machame Camp to Shira Camp (3,840 m)', 'Ascend through the heather zone onto the enormous Shira Plateau — one of Kilimanjaro\'s ancient calderas. Vast views of the Western Breach and the ice-capped summit.', 7, null],
            [3, 'Shira Camp to Barranco Camp (3,960 m)', 'Hike high toward Lava Tower (4,630 m) for acclimatisation, then descend to the spectacular Barranco Valley beneath the towering Barranco Wall. "Walk high, sleep low" strategy.', 7, null],
            [4, 'Barranco Wall to Karanga Camp (3,995 m)', 'Scale the famous Barranco Wall — a thrilling scramble with spectacular views. Continue across alpine desert to Karanga Camp, the last water collection point.', 7, null],
            [5, 'Karanga Camp to Barafu Camp (4,673 m)', 'Short but steep ascent to the summit base camp at Barafu. Rest and prepare for midnight summit bid. Early dinner and final gear check.', 7, null],
            [6, 'Summit Night — Uhuru Peak (5,895 m)', 'Midnight departure through the Arctic zone. Push through Stella Point to Uhuru Peak at sunrise. After summit photos, descend to Millennium Camp for overnight rest.', 7, null],
            [7, 'Descent to Mweka Gate', 'Final descent through the rainforest zone via Mweka Route. Arrive at Mweka Gate for your summit certificate and transfer to Arusha.', 7, null],
        ],
    ],

    // ID 10: Kilimanjaro Lemosho Route — 8-Day Wilderness Expedition
    10 => [
        'destinations' => [7],
        'itinerary' => [
            [1, 'Londorossi Gate to Mti Mkubwa (2,895 m)', 'Drive to Londorossi Gate on the western side of Kilimanjaro. Trek through pristine montane rainforest rich with blue monkeys and birdsong to the Big Tree Camp.', 7, null],
            [2, 'Mti Mkubwa to Shira 1 Camp (3,505 m)', 'Emerge from the forest into the heather zone. Cross streams and climb onto the vast Shira Plateau with panoramic views of Kibo peak ahead.', 7, null],
            [3, 'Shira 1 to Shira 2 Camp (3,840 m)', 'Traverse the Shira Plateau — one of the most scenic walks on Kilimanjaro. Giant groundsel and lobelia dot the landscape. Camp with views of the summit.', 7, null],
            [4, 'Shira 2 to Barranco Camp (3,960 m)', 'Acclimatisation day climbing toward Lava Tower (4,630 m), then descending to Barranco Camp in the shadow of the Great Barranco Wall.', 7, null],
            [5, 'Barranco Wall to Karanga Camp (3,995 m)', 'Scale the thrilling Barranco Wall with hands-on scrambling. Continue across alpine desert to Karanga Valley camp.', 7, null],
            [6, 'Karanga Camp to Barafu Camp (4,673 m)', 'Ascend the steep ridge to the summit base camp. Rest, hydrate, and prepare for the midnight summit push. Sunset views across the cloud sea.', 7, null],
            [7, 'Summit Night — Uhuru Peak (5,895 m)', 'Begin at midnight through the Arctic zone. Reach Stella Point at dawn, then push to Uhuru Peak (5,895 m). Descend to Millennium Camp.', 7, null],
            [8, 'Descent to Mweka Gate', 'Final descent through lush rainforest to Mweka Gate. Collect your summit certificate and celebrate with your crew before returning to Arusha.', 7, null],
        ],
    ],

    // ID 11: Kilimanjaro Umbwe Route — 6-Day Direct Ascent
    11 => [
        'destinations' => [7],
        'itinerary' => [
            [1, 'Umbwe Gate to Umbwe Cave Camp (2,850 m)', 'Start at Umbwe Gate (1,600 m). Ascend steep forested ridges along a narrow trail — Kilimanjaro\'s most direct and dramatic approach.', 7, null],
            [2, 'Umbwe Cave to Barranco Camp (3,960 m)', 'Continue the steep climb through giant heather and moorland to reach the spectacular Barranco Valley beneath the great wall.', 7, null],
            [3, 'Barranco Wall to Karanga Camp (3,995 m)', 'Scale the Barranco Wall and traverse alpine desert to Karanga Camp. Acclimatisation hike in the afternoon.', 7, null],
            [4, 'Karanga to Barafu Camp (4,673 m)', 'Short ascent to the summit base camp. Rest and prepare for midnight summit attempt. Final gear check.', 7, null],
            [5, 'Summit Night — Uhuru Peak (5,895 m)', 'Midnight departure. Push through scree and switchbacks via Stella Point to the summit at sunrise. Descend to Millennium Camp.', 7, null],
            [6, 'Descent to Mweka Gate', 'Descend through the rainforest to Mweka Gate. Summit certificate ceremony and transfer to Arusha.', 7, null],
        ],
    ],

    // ID 12: Kilimanjaro Rongai Route — 7-Day Northern Approach
    12 => [
        'destinations' => [7],
        'itinerary' => [
            [1, 'Rongai Gate to Simba Camp (2,625 m)', 'Drive from Arusha to the Kenyan border at Rongai Gate. Begin your trek through pine and juniper forest on Kilimanjaro\'s drier, quieter northern slopes.', 7, null],
            [2, 'Simba Camp to Second Cave (3,450 m)', 'Ascend through heather moorland with views stretching into Kenya. Arrive at Second Cave camp among lava formations.', 7, null],
            [3, 'Second Cave to Kikelewa Camp (3,600 m)', 'Traverse rocky terrain toward the Mawenzi massif. Camp at Kikelewa with dramatic views of Mawenzi\'s jagged pinnacles.', 7, null],
            [4, 'Kikelewa to Mawenzi Tarn (4,310 m)', 'Climb steeply to the beautiful Mawenzi Tarn — a glacial lake at the foot of Mawenzi Peak. Afternoon acclimatisation walk.', 7, null],
            [5, 'Mawenzi Tarn to School Hut (4,750 m)', 'Cross the lunar desert of the "Saddle" between Mawenzi and Kibo. Arrive at School Hut for rest before summit night.', 7, null],
            [6, 'Summit Night — Uhuru Peak (5,895 m)', 'Midnight start across scree to the crater rim. Reach Gilman\'s Point, then Uhuru Peak at sunrise. Descend to Horombo Hut.', 7, 13],
            [7, 'Horombo Hut to Marangu Gate', 'Final descent through moorland and rainforest to Marangu Gate. Summit certificate ceremony and transfer to Arusha.', 7, null],
        ],
    ],

    // ID 13: Kilimanjaro Northern Circuit — 9-Day Grand Traverse
    13 => [
        'destinations' => [7],
        'itinerary' => [
            [1, 'Londorossi Gate to Mti Mkubwa (2,895 m)', 'Register at Londorossi Gate and trek through ancient forest to Big Tree Camp. The Lemosho starting point offers the most pristine wilderness approach.', 7, null],
            [2, 'Mti Mkubwa to Shira 2 Camp (3,840 m)', 'Long but gentle climb through heather onto the expansive Shira Plateau. Camp with sunset views of the summit.', 7, null],
            [3, 'Shira 2 to Lava Tower & Moir Hut (4,200 m)', 'Acclimatisation hike to Lava Tower (4,630 m). Descend to camp at Moir Hut in a remote valley on Kilimanjaro\'s northern face.', 7, null],
            [4, 'Moir Hut to Buffalo Camp (4,020 m)', 'Traverse the rarely-trekked northern circuit with views into Kenya and the Northern Ice Field. Few other trekkers — pure wilderness.', 7, null],
            [5, 'Buffalo Camp to Third Cave (3,870 m)', 'Continue the northern traverse across lava ridges. Camp at Third Cave with views of Mawenzi\'s pinnacles and the Saddle.', 7, null],
            [6, 'Third Cave to School Hut (4,750 m)', 'Cross the Saddle — a high-altitude desert between Mawenzi and Kibo. Arrive at School Hut for final preparations.', 7, null],
            [7, 'Summit Day — Uhuru Peak (5,895 m)', 'Midnight summit push via Gilman\'s Point and Stella Point to Uhuru Peak at sunrise. Descend to Millennium Camp to rest.', 7, null],
            [8, 'Millennium Camp to Mweka Camp', 'Descent through alpine desert and heather zones to Mweka Camp in the upper rainforest. Celebration dinner with crew.', 7, null],
            [9, 'Mweka Camp to Mweka Gate', 'Final trek through rainforest to Mweka Gate. Receive your summit certificate and transfer to Arusha for celebration.', 7, null],
        ],
    ],

    // ID 14: Arusha National Park — Full-Day Wildlife & Canoe Safari
    14 => [
        'destinations' => [3, 4],
        'itinerary' => [
            [1, 'Full Day — Arusha National Park', 'Pick-up from your Arusha hotel at 7:30 AM. Drive to Arusha National Park — the hidden gem at Kilimanjaro\'s doorstep. Morning game drive through Ngurdoto Crater and Momella Lakes — spot giraffe, buffalo, flamingos and colobus monkeys. Late morning canoe safari on the Momella Lakes with hippos nearby. Picnic lunch with views of Mount Meru. Afternoon walking safari with an armed ranger. Return to Arusha by 5:30 PM.', 3, null],
        ],
    ],

    // ID 15: Tarangire National Park — Full-Day Elephant Safari
    15 => [
        'destinations' => [5, 4],
        'itinerary' => [
            [1, 'Full Day — Tarangire National Park', 'Depart Arusha at 7:00 AM and drive south to Tarangire — home to the largest elephant herds in Tanzania. Morning game drive along the Tarangire River spotting elephant families, lion, leopard, python and over 500 bird species among ancient baobabs. Gourmet picnic lunch under a baobab tree. Afternoon drive into the remote southern sector for quieter wildlife sightings. Return to Arusha by 6:30 PM.', 5, null],
        ],
    ],

    // ID 16: Ngorongoro Crater — Full-Day Big Five Safari
    16 => [
        'destinations' => [2, 4, 18],
        'itinerary' => [
            [1, 'Full Day — Ngorongoro Crater', 'Early departure from Arusha at 5:30 AM, driving through the Karatu highlands to the Ngorongoro Conservation Area. Descend 600 metres into the world\'s largest intact volcanic caldera. Game drive on the crater floor — spot the Big Five (lion, leopard, elephant, buffalo, black rhino), hippo, flamingos and jackal. Picnic lunch at a scenic lookout point. Afternoon game drive before ascending and returning to Arusha by 7:00 PM.', 2, null],
        ],
    ],

    // ID 17: Materuni Waterfalls & Coffee Plantation Walk
    17 => [
        'destinations' => [22, 4],
        'itinerary' => [
            [1, 'Full Day — Materuni Village & Waterfalls', 'Pick-up from Arusha at 8:00 AM. Drive to the slopes of Kilimanjaro and the Chagga village of Materuni. Begin with a guided tour of a traditional coffee plantation — learn to pick, roast and brew Tanzanian coffee by hand. Continue with a scenic hike through banana plantations and lush forest to the 80-metre Materuni Waterfall — swim in the natural pool at the base. Enjoy a traditional Chagga lunch prepared by local women. Return to Arusha by 4:00 PM.', 22, null],
        ],
    ],

    // ID 18: Lake Manyara National Park — Full-Day Tree-Climbing Lion Safari
    18 => [
        'destinations' => [6, 4],
        'itinerary' => [
            [1, 'Full Day — Lake Manyara National Park', 'Depart Arusha at 6:30 AM heading west along the Rift Valley escarpment. Enter Lake Manyara National Park — an emerald jewel beneath towering cliffs. Morning game drive through the groundwater forest spotting blue monkeys, elephants and the famous tree-climbing lions lounging in mahogany branches. Drive along the lakeshore for flamingo, pelican and hippo sightings. Gourmet picnic lunch with Rift Valley views. Afternoon birding drive — over 400 species recorded here. Return to Arusha by 6:00 PM.', 6, null],
        ],
    ],

    // ID 19: Kilimanjaro Day Hike — Shira Plateau Summit Walk
    19 => [
        'destinations' => [7, 4],
        'itinerary' => [
            [1, 'Full Day — Shira Plateau Hike', 'Depart Arusha at 6:00 AM for the western slopes of Kilimanjaro. Drive to Londorossi Gate (2,250 m) and continue by 4x4 to the Shira Plateau trailhead (3,500 m). Hike across the ancient caldera with panoramic views of Kibo peak, the Western Breach and the Shira Cathedral. Reach the Shira Ridge viewpoint (3,800 m) for photographs. Gourmet packed lunch with a summit view. Descend and return to Arusha by 5:00 PM. A taste of Kilimanjaro without the multi-day commitment.', 7, null],
        ],
    ],
];

// ──────────────────────────────────────────────────────
// EXECUTE
// ──────────────────────────────────────────────────────
DB::beginTransaction();
try {
    $itinCount = 0;
    $pivotCount = 0;

    foreach ($data as $safariId => $info) {
        $safari = SafariPackage::find($safariId);
        if (!$safari) {
            echo "WARNING: Safari ID {$safariId} not found, skipping\n";
            continue;
        }

        // Sync destination pivots
        $safari->destinations()->syncWithoutDetaching($info['destinations']);
        $pivotCount += count($info['destinations']);
        echo "Safari {$safariId}: linked " . count($info['destinations']) . " destinations\n";

        // Insert itineraries (skip if already has some)
        $existing = Itinerary::where('safari_package_id', $safariId)->count();
        if ($existing > 0) {
            echo "  → Already has {$existing} itineraries, skipping\n";
            continue;
        }

        foreach ($info['itinerary'] as $day) {
            Itinerary::create([
                'safari_package_id' => $safariId,
                'day_number'        => $day[0],
                'title'             => $day[1],
                'description'       => $day[2],
                'destination_id'    => $day[3],
                'accommodation_id'  => $day[4],
            ]);
            $itinCount++;
        }
        echo "  → Created " . count($info['itinerary']) . " day itineraries\n";
    }

    DB::commit();
    echo "\n✓ Done: {$itinCount} itineraries created, {$pivotCount} destination links synced\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
