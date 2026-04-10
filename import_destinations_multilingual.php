<?php

declare(strict_types=1);

use App\Models\Destination;
use App\Models\SeoMeta;
use App\Services\SeoAnalyzer;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

set_time_limit(0);

$countryId = 1;
$geminiKey = (string) config('services.gemini.api_key');
$geminiModel = (string) config('services.gemini.model', 'gemini-2.0-flash');
$translationCachePath = storage_path('app/destination_translation_cache.json');
$translationCache = file_exists($translationCachePath)
    ? json_decode((string) file_get_contents($translationCachePath), true)
    : [];

$destinations = [
    [
        'name' => 'Serengeti National Park',
        'slug' => 'serengeti-national-park',
        'aliases' => ['Serengeti National Park'],
        'slug_aliases' => ['serengeti-national-park'],
        'meta_title' => 'Serengeti National Park safari guide',
        'meta_description' => 'Serengeti National Park is Tanzania\'s signature safari landscape for migration herds, big cats, and year-round game viewing across vast open plains.',
        'meta_keywords' => 'Serengeti National Park, Serengeti safari, Great Migration Tanzania, Seronera game drives, Tanzania wildlife guide',
        'focus_keyword' => 'Serengeti National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'private safari itineraries',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is Tanzania\'s most iconic safari landscape, a huge sweep of short-grass plains, granite kopjes, river valleys, and acacia country where the movement of wildebeest and zebra shapes the rhythm of the year.',
        'landscape' => 'The southern plains around Ndutu and Kusini are famous for calving season, central Seronera mixes open grassland with riverine woodland that holds resident predators, and the north builds toward the Mara River where dry-season crossings create some of East Africa\'s most dramatic wildlife scenes.',
        'wildlife' => 'Because the ecosystem is so varied, travellers are not limited to one headline experience: lion prides, cheetah on termite mounds, leopard in sausage trees, spotted hyena, elephant, giraffe, and immense mixed herds can all appear on the same well-planned drive.',
        'activities' => 'Most visitors come for classic game drives, yet the park also rewards photographers who work dawn and dusk light carefully, travellers who split camps between regions, and guests who add a hot-air balloon flight to understand the scale of the plains from above.',
        'seasonality' => 'works best when the itinerary follows the season rather than forcing one fixed route. December to March is strongest for calving and predator action in the south, while June to October is better for the western corridor and the northern river system.',
        'logistics' => 'Access is usually via airstrips or a northern-circuit road journey, and serious safari planning often means deciding whether to prioritise migration movement, central Serengeti consistency, or a combination of both over several nights.',
        'pairing' => 'It pairs naturally with Ngorongoro Crater and Lake Manyara when travellers want a rounded northern circuit that balances headline wildlife with contrasting scenery and different safari moods.',
        'closing' => 'Moreover, guests comparing <a href="/destinations">other Tanzania destinations</a> often discover that Serengeti National Park delivers its best value when folded into <a href="/safaris">private safari itineraries</a> designed around season, flight logistics, and the type of wildlife moments they care about most.',
    ],
    [
        'name' => 'Ngorongoro Crater',
        'slug' => 'ngorongoro-crater',
        'aliases' => ['Ngorongoro Crater'],
        'slug_aliases' => ['ngorongoro-crater'],
        'meta_title' => 'Ngorongoro Crater safari guide',
        'meta_description' => 'Ngorongoro Crater offers dense wildlife viewing, dramatic volcanic scenery, and one of Tanzania\'s most rewarding full-day safari experiences.',
        'meta_keywords' => 'Ngorongoro Crater, Ngorongoro safari, Tanzania crater safari, Big Five Ngorongoro, northern circuit guide',
        'focus_keyword' => 'Ngorongoro Crater',
        'primary_link' => '/safaris',
        'primary_anchor' => 'northern circuit safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'nearby Tanzania destinations',
        'intro' => 'is one of the most concentrated wildlife arenas in Africa, a vast collapsed volcano whose enclosed floor holds grassland, Lerai forest, seasonal marsh, alkaline lake edges, and permanent springs inside a single spectacular natural amphitheatre.',
        'landscape' => 'That variety matters because it compresses different habitats into a relatively small space: buffalo graze open grassland, elephants move through the forest margins, hippo remain in freshwater pools, and the crater rim itself creates a cool, dramatic backdrop to every drive.',
        'wildlife' => 'Travellers come here for reliable big-game viewing, including lion, hyena, black rhino, zebra, wildebeest, and large herds of grazing animals, but the real strength of the crater is how quickly it reveals ecological contrast and predator-prey interaction in one day.',
        'activities' => 'A well-run visit usually begins with an early descent before vehicle numbers build, followed by slow game viewing around the marsh, forest edge, and soda lake flats where flamingos sometimes gather when conditions are right.',
        'seasonality' => 'can be visited throughout the year, although road conditions, cloud on the rim, and crowd patterns still affect how the day feels. The crater works particularly well for travellers who want a guaranteed wildlife-heavy stop inside a broader northern circuit.',
        'logistics' => 'Because descent permits and timing shape the experience, it is smarter to stay nearby in Karatu or on the highland rim and treat the crater as a focused full-day safari rather than a rushed stop between longer drives.',
        'pairing' => 'Ngorongoro Crater is especially effective when matched with Serengeti migration country, Tarangire elephant habitat, or Lake Manyara for a more diverse safari rhythm.',
        'closing' => 'Additionally, travellers comparing <a href="/destinations">nearby Tanzania destinations</a> often find that Ngorongoro Crater is the ideal centrepiece of <a href="/safaris">northern circuit safaris</a> because it combines scenery, density of wildlife, and straightforward logistics in a single memorable day.',
    ],
    [
        'name' => 'Tarangire National Park',
        'slug' => 'tarangire-national-park',
        'aliases' => ['Tarangire National Park'],
        'slug_aliases' => ['tarangire-national-park'],
        'meta_title' => 'Tarangire National Park safari guide',
        'meta_description' => 'Tarangire National Park is known for giant baobabs, heavy dry-season elephant numbers, and quietly excellent game viewing on Tanzania\'s northern circuit.',
        'meta_keywords' => 'Tarangire National Park, Tarangire safari, elephants in Tarangire, baobab landscape Tanzania, northern circuit park',
        'focus_keyword' => 'Tarangire National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'custom safari itineraries',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other northern circuit destinations',
        'intro' => 'is often the most pleasantly surprising stop on the northern circuit, especially for travellers who arrive expecting a short transit park and leave talking instead about elephants, baobab silhouettes, and a river system that holds wildlife deep into the dry season.',
        'landscape' => 'The park is shaped by the Tarangire River, broad savannah, seasonal swamps, and ancient baobabs that give the scenery a sense of age and scale that feels very different from the open plains of Serengeti.',
        'wildlife' => 'During the dry months, elephant numbers can be exceptional, and game drives regularly produce giraffe, zebra, wildebeest, buffalo, impala, ostrich, and a strong predator presence that includes lion, leopard, and occasional cheetah in the right sectors.',
        'activities' => 'Birders value Tarangire for its varied habitats, photographers love the contrast of red earth and giant trees, and slower travellers appreciate that the park rewards patient driving rather than a constant rush from sighting to sighting.',
        'seasonality' => 'shows its strongest safari character from roughly June to October when wildlife concentrates around permanent water, although the green season brings softer light, fewer vehicles, and beautiful birdlife around the swamps.',
        'logistics' => 'It works well near the beginning or end of a northern circuit because access from Arusha is manageable and because the park immediately introduces travellers to the mood of safari without requiring a flight.',
        'pairing' => 'Tarangire National Park combines particularly well with Ngorongoro, Lake Manyara, and the Serengeti when you want each park to bring a distinct landscape and wildlife pattern to the same journey.',
        'closing' => 'Meanwhile, guests exploring <a href="/destinations">other northern circuit destinations</a> often use Tarangire National Park as the opening chapter of <a href="/safaris">custom safari itineraries</a>, because it delivers excellent wildlife viewing while easing travellers into the rhythm of long game drives and changing habitats.',
    ],
    [
        'name' => 'Mount Kilimanjaro National Park',
        'slug' => 'mount-kilimanjaro-national-park',
        'aliases' => ['Mount Kilimanjaro National Park', 'Mount Kilimanjaro'],
        'slug_aliases' => ['mount-kilimanjaro-national-park', 'mount-kilimanjaro'],
        'meta_title' => 'Mount Kilimanjaro National Park trekking guide',
        'meta_description' => 'Mount Kilimanjaro National Park brings glacier views, mountain forest, and serious multi-day trekking across Africa\'s highest peak.',
        'meta_keywords' => 'Mount Kilimanjaro National Park, Kilimanjaro trekking, Tanzania mountain guide, Machame route, summit planning',
        'focus_keyword' => 'Mount Kilimanjaro National Park',
        'primary_link' => '/trekking',
        'primary_anchor' => 'Kilimanjaro trekking itineraries',
        'secondary_link' => '/custom-tour',
        'secondary_anchor' => 'custom Tanzania journeys',
        'intro' => 'protects the high-altitude slopes and summit cone of Africa\'s highest mountain, a volcanic massif that rises from cultivated foothills through rainforest, heath, moorland, alpine desert, and finally an arctic summit environment crowned by Uhuru Peak.',
        'landscape' => 'What makes the mountain so compelling is not only its altitude, but the way the route itself becomes a journey through climate zones: giant groundsels and lobelia in the moorland, open views over Meru and the plains, and stark high camps under cold, clear skies.',
        'wildlife' => 'Although Kilimanjaro is approached primarily as a trekking destination rather than a game park, the lower forest still holds blue monkeys, colobus, birds, and a rich belt of montane vegetation that makes the early days of the climb greener and more diverse than many first-time trekkers expect.',
        'activities' => 'Route choice changes the experience significantly. Machame is scenic and varied, Lemosho allows strong acclimatisation and broad western views, while Marangu remains the hut-based classic for travellers who prefer fixed shelter instead of tents.',
        'seasonality' => 'is best approached with respect for acclimatisation, not just fitness. Dry periods are usually more comfortable underfoot, but summit success depends far more on pacing, altitude management, guide quality, and the total number of nights on the mountain.',
        'logistics' => 'Because most climbs start near Moshi, many travellers combine the park with Materuni Village, Arusha, or a short safari after the trek. That combination works well when recovery days are built in rather than treated as an afterthought.',
        'pairing' => 'For climbers, the real value of the park lies in route planning, crew standards, safety systems, and giving the body enough time to adapt as the environment changes dramatically each day.',
        'closing' => 'Furthermore, travellers comparing <a href="/custom-tour">custom Tanzania journeys</a> often add Mount Kilimanjaro National Park to <a href="/trekking">Kilimanjaro trekking itineraries</a> that also include cultural visits, recovery days, or safari extensions built around sensible pacing rather than a rushed summit attempt.',
    ],
    [
        'name' => 'Arusha National Park',
        'slug' => 'arusha-national-park',
        'aliases' => ['Arusha National Park'],
        'slug_aliases' => ['arusha-national-park'],
        'meta_title' => 'Arusha National Park travel guide',
        'meta_description' => 'Arusha National Park offers forest, crater, lakes, canoeing, and excellent day-trip wildlife viewing close to Arusha town and Kilimanjaro routes.',
        'meta_keywords' => 'Arusha National Park, Arusha day safari, Momella Lakes, Mount Meru, canoe safari Tanzania',
        'focus_keyword' => 'Arusha National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'short northern circuit safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is the most accessible wildlife park in northern Tanzania and one of the most varied for a single day out, combining montane forest, the Momella Lakes, open grassland, Ngurdoto Crater, and the forested slopes of Mount Meru in a remarkably compact area.',
        'landscape' => 'That mixture creates a softer, greener safari mood than Tarangire or Serengeti. Morning mist often hangs over the forest, flamingos and waterbirds gather on alkaline lake edges when conditions suit them, and the crater rim gives wide views without requiring a full descent.',
        'wildlife' => 'Arusha National Park is not a Big Five classic, yet it is rich in giraffe, buffalo, zebra, warthog, waterbuck, bushbuck, and black-and-white colobus monkeys that are especially rewarding for travellers interested in photography and quieter game viewing.',
        'activities' => 'It is also one of the few places on the northern circuit where a canoe excursion on the Momella Lakes and a guided walking safari can sit beside a standard game drive, giving the visit a much broader texture than a simple drive-through park.',
        'seasonality' => 'works well year-round, but it is particularly useful at the start or end of a longer itinerary when travellers want scenery, birds, and wildlife without the commitment of a full transfer to the deeper safari circuit.',
        'logistics' => 'Its proximity to Arusha makes it easy to pair with a city stay, a Kilimanjaro recovery day, or a short family itinerary where long driving hours would drain the experience rather than improve it.',
        'pairing' => 'For many guests, the park serves as an elegant introduction to Tanzania rather than a lesser substitute for the bigger names further west.',
        'closing' => 'Additionally, travellers browsing <a href="/destinations">other Tanzania destinations</a> often find that Arusha National Park fits best inside <a href="/safaris">short northern circuit safaris</a> when they want active experiences, beautiful scenery, and wildlife viewing close to the region\'s main travel hub.',
    ],
    [
        'name' => 'Lake Manyara National Park',
        'slug' => 'lake-manyara-national-park',
        'aliases' => ['Lake Manyara National Park'],
        'slug_aliases' => ['lake-manyara-national-park'],
        'meta_title' => 'Lake Manyara National Park safari guide',
        'meta_description' => 'Lake Manyara National Park blends escarpment scenery, groundwater forest, prolific birdlife, and a compact but rewarding safari stop.',
        'meta_keywords' => 'Lake Manyara National Park, Lake Manyara safari, Tanzania birding park, tree-climbing lions, Rift Valley safari',
        'focus_keyword' => 'Lake Manyara National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'tailor-made safari routes',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Rift Valley destinations',
        'intro' => 'packs unusual ecological variety into a relatively small footprint beneath the Great Rift Valley escarpment, mixing evergreen groundwater forest, hot springs, floodplain, acacia woodland, and the shallow soda lake that gives the park its name.',
        'landscape' => 'That compressed range of habitats means the scenery changes quickly as a drive unfolds: one moment you are moving through tall forest with blue monkeys and baboons, and the next you are looking across open flats where buffalo, zebra, and birds gather near the waterline.',
        'wildlife' => 'The park is especially strong for birdlife and for travellers who appreciate diversity over sheer scale. Flamingos can appear in good numbers when lake conditions are right, while elephant, hippo, giraffe, and many smaller species keep the game viewing active through the year.',
        'activities' => 'Lake Manyara National Park is also famous for its atmospheric forest roads, seasonal lion sightings in the acacia belt, and its usefulness as a shorter safari stop that still feels distinctive rather than transitional.',
        'seasonality' => 'changes character with rainfall and lake levels, so expectations should be adjusted to season. The greener months suit birding and lush scenery, while drier periods usually make general wildlife viewing more straightforward.',
        'logistics' => 'Because the park sits close to Karatu and Mto wa Mbu, it works exceptionally well as a first or final park on the northern circuit, especially for families, photographers, and travellers who want an easier driving day.',
        'pairing' => 'It complements Tarangire, Ngorongoro, and Serengeti by bringing a wetter, more intimate safari atmosphere to itineraries otherwise dominated by open plains.',
        'closing' => 'Meanwhile, guests comparing <a href="/destinations">other Rift Valley destinations</a> often place Lake Manyara National Park inside <a href="/safaris">tailor-made safari routes</a> when they want scenery, birds, and a compact game drive that still adds something genuinely different to a wider northern circuit.',
    ],
    [
        'name' => 'Zanzibar Island',
        'slug' => 'zanzibar-island',
        'aliases' => ['Zanzibar Island'],
        'slug_aliases' => ['zanzibar-island'],
        'meta_title' => 'Zanzibar Island travel guide',
        'meta_description' => 'Zanzibar Island combines Stone Town history, spice farms, coral-rimmed beaches, and Indian Ocean downtime after a Tanzania safari.',
        'meta_keywords' => 'Zanzibar Island, Zanzibar beaches, Stone Town guide, Tanzania beach holiday, spice island travel',
        'focus_keyword' => 'Zanzibar Island',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom Tanzania holidays',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is where many Tanzania itineraries shift from dust and game drives to coral-rag villages, spice farms, dhow harbours, and warm Indian Ocean water. It is not a single beach resort destination but a layered island with distinct moods depending on whether you stay in Stone Town, the east coast, the north, or the quieter southern stretches.',
        'landscape' => 'Stone Town gives the island its cultural gravity with carved doors, Omani architecture, old merchant houses, and narrow lanes that still reflect centuries of Indian Ocean trade. Outside town, the island opens into clove and spice farms, seaweed villages, palm-backed beaches, and tidal flats that change the coastline hour by hour.',
        'wildlife' => 'Zanzibar Island is not driven by classic safari wildlife, yet it still offers marine life, reef fish, dolphins in some coastal zones, and forest reserves where the endemic red colobus monkey remains one of the archipelago\'s most recognisable species.',
        'activities' => 'Days can be built around walking tours in Stone Town, cooking classes, spice visits, diving or snorkelling on coral reefs, sunset dhow cruises, or simply resting after a demanding mainland safari or Kilimanjaro climb.',
        'seasonality' => 'works differently depending on the coast and the season, so the right beach matters as much as the right month. Some travellers want nightlife and easy access to excursions, while others want a quieter hideaway with more privacy and slower pacing.',
        'logistics' => 'Because flights from Arusha and Serengeti connect well, Zanzibar is one of the easiest extensions to add, but it is most rewarding when travellers are clear about whether they want culture, diving, honeymoon downtime, or family beach time.',
        'pairing' => 'The island is particularly strong as the final chapter of a safari or trekking itinerary because it gives the trip emotional contrast rather than more of the same.',
        'closing' => 'Moreover, guests comparing <a href="/destinations">other Tanzania destinations</a> often add Zanzibar Island to <a href="/custom-tour">custom Tanzania holidays</a> when they want a trip that balances wildlife, culture, and genuine Indian Ocean rest instead of ending the journey on another long road transfer.',
    ],
    [
        'name' => 'Arusha Town',
        'slug' => 'arusha-town',
        'aliases' => ['Arusha Town'],
        'slug_aliases' => ['arusha-town'],
        'meta_title' => 'Arusha Town travel guide and safari hub',
        'meta_description' => 'Arusha Town is northern Tanzania\'s main safari gateway, useful for rest days, shopping, coffee, logistics, and short cultural outings.',
        'meta_keywords' => 'Arusha Town, Arusha travel guide, Tanzania safari gateway, coffee in Arusha, northern circuit base',
        'focus_keyword' => 'Arusha Town',
        'primary_link' => '/safaris',
        'primary_anchor' => 'northern Tanzania safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is the working gateway to northern Tanzania rather than a polished resort town, and that is precisely why it matters. Most safaris, Kilimanjaro climbs, and regional flights pass through Arusha, so the town often determines whether a trip begins in confusion or with enough breathing room to settle into the journey properly.',
        'landscape' => 'The city sits between Mount Meru and Kilimanjaro International Airport, with jacaranda-lined neighbourhoods, busy roundabouts, craft shops, coffee houses, vegetable markets, and a practical mix of safari outfitters, local businesses, and long-stay residents.',
        'wildlife' => 'Arusha Town is not itself a wildlife destination, yet it gives travellers access to nearby forest, coffee estates, museums, and easy day trips to Arusha National Park or cultural sites that add context before or after time in the bush.',
        'activities' => 'Good use of the town includes visiting the Cultural Heritage Centre, buying supplies before heading into the parks, arranging a coffee tour, eating well after an international flight, and taking a rest day before climbing or after a long safari circuit.',
        'seasonality' => 'works all year because its role is logistical as much as experiential. The key decision is not when to visit Arusha Town, but how many nights to give it so that airport arrivals, briefing time, shopping, or recovery do not eat into the more expensive parts of the itinerary.',
        'logistics' => 'For families, photographers, and climbers especially, a properly timed Arusha stay can make the rest of the trip feel more organised and less rushed.',
        'pairing' => 'It pairs naturally with nearby parks, Kilimanjaro routes, Materuni Village, and any northern circuit plan that starts early the next morning.',
        'closing' => 'Additionally, travellers browsing <a href="/destinations">other Tanzania destinations</a> usually discover that Arusha Town supports better <a href="/safaris">northern Tanzania safaris</a> when it is treated as a useful base for rest, preparation, and local context instead of a place to sleep through and forget.',
    ],
    [
        'name' => 'Karatu',
        'slug' => 'karatu',
        'aliases' => ['Karatu'],
        'slug_aliases' => ['karatu'],
        'meta_title' => 'Karatu travel guide and crater gateway',
        'meta_description' => 'Karatu is the highland gateway to Ngorongoro Crater and Lake Manyara, known for coffee farms, cool air, and well-placed safari lodges.',
        'meta_keywords' => 'Karatu, Karatu travel guide, Ngorongoro gateway, coffee farms Tanzania, safari lodge base',
        'focus_keyword' => 'Karatu',
        'primary_link' => '/safaris',
        'primary_anchor' => 'northern circuit safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other northern Tanzania destinations',
        'intro' => 'is the highland market town that quietly supports a huge share of northern Tanzania safari logistics. Sitting between Lake Manyara and the Ngorongoro highlands, it offers cooler air, fertile farmland, and a calmer rhythm than the busier transit points further east.',
        'landscape' => 'The surrounding area is defined by coffee estates, wheat fields, red-earth roads, and wooded ridges that lead upward toward the conservation area. That agricultural setting gives the town a lived-in feel rather than the purpose-built atmosphere of a tourism-only stop.',
        'wildlife' => 'Karatu is not a park in its own right, but it matters because it places travellers within easy reach of Ngorongoro Crater, Lake Manyara National Park, and cultural visits to Iraqw and farming communities in the surrounding hills.',
        'activities' => 'A stay here works well for guests who want more than an early wake-up call before descending into the crater. Coffee tours, farm walks, local markets, and relaxed lodge afternoons all help turn Karatu into a genuine part of the itinerary rather than a simple overnight halt.',
        'seasonality' => 'remains useful throughout the year, with the highland climate making it especially comfortable after hotter days on the plains. During wetter periods the greenery is attractive, while dry months make onward safari logistics more straightforward.',
        'logistics' => 'Because lodge choice in the area ranges from intimate farm properties to larger safari bases, Karatu suits many budgets and is especially practical for travellers who want to balance park access with comfort and shorter driving days.',
        'pairing' => 'It pairs naturally with Ngorongoro, Lake Manyara, Tarangire, and Mto wa Mbu in itineraries that value variety in scenery and pace.',
        'closing' => 'Furthermore, guests comparing <a href="/destinations">other northern Tanzania destinations</a> often use Karatu inside <a href="/safaris">northern circuit safaris</a> because it creates a more relaxed, better-timed route into the crater region without losing access to the parks that matter most.',
    ],
    [
        'name' => 'Mto wa Mbu',
        'slug' => 'mto-wa-mbu',
        'aliases' => ['Mto wa Mbu'],
        'slug_aliases' => ['mto-wa-mbu'],
        'meta_title' => 'Mto wa Mbu travel guide and Lake Manyara gateway',
        'meta_description' => 'Mto wa Mbu blends village walks, farming culture, and easy access to Lake Manyara as one of northern Tanzania\'s most useful stopovers.',
        'meta_keywords' => 'Mto wa Mbu, Mto wa Mbu travel guide, Lake Manyara gateway, Tanzania village walk, cultural stopover',
        'focus_keyword' => 'Mto wa Mbu',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom northern Tanzania trips',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is one of the most interesting stopovers on the northern circuit because it is a real working town before it is a tourism label. Set beneath the Rift Valley escarpment near Lake Manyara, it brings together farming, trade, and remarkable cultural diversity in a compact area that is easy to explore slowly.',
        'landscape' => 'The town is greener than many visitors expect, with banana plantations, irrigated plots, papyrus-lined water channels, and the escarpment rising sharply behind it. This fertile setting explains why the area has drawn communities from across Tanzania for generations.',
        'wildlife' => 'Mto wa Mbu is not sold on headline wildlife itself, yet it sits right beside Lake Manyara National Park and offers a valuable human counterpoint to the game-viewing sections of a safari.',
        'activities' => 'Village walks here are worthwhile when they are led well: visitors can see farms, food markets, local workshops, rice fields, and in some cases small-scale cultural projects that explain how different communities have shaped the town. Cycling routes and market visits also work well.',
        'seasonality' => 'fits most itineraries at any time of year because its value lies in location, culture, and convenience rather than one narrow wildlife event. It is especially helpful for travellers who want a shorter driving day between parks.',
        'logistics' => 'Because accommodation ranges from simple guesthouses to comfortable safari lodges nearby, the area can serve backpackers, families, and custom private clients equally well.',
        'pairing' => 'Mto wa Mbu works best when combined with Lake Manyara, Karatu, Tarangire, or a broader cultural extension built around local food and village experience.',
        'closing' => 'Moreover, travellers comparing <a href="/destinations">other Tanzania destinations</a> often include Mto wa Mbu in <a href="/custom-tour">custom northern Tanzania trips</a> because it adds genuine local texture to a route that might otherwise move too quickly from one wildlife area to the next.',
    ],
    [
        'name' => 'Nyerere National Park',
        'slug' => 'nyerere-national-park',
        'aliases' => ['Nyerere National Park', 'Selous Game Reserve'],
        'slug_aliases' => ['nyerere-national-park', 'selous-game-reserve'],
        'meta_title' => 'Nyerere National Park safari guide',
        'meta_description' => 'Nyerere National Park offers remote southern Tanzania safari country, the Rufiji River, boat safaris, and strong wild-dog wilderness appeal.',
        'meta_keywords' => 'Nyerere National Park, southern Tanzania safari, Rufiji River safari, boat safari Tanzania, wild dog safari',
        'focus_keyword' => 'Nyerere National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'southern Tanzania safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other remote safari destinations',
        'intro' => 'is the flagship safari wilderness of southern Tanzania, a huge protected landscape centred on the Rufiji River system where wide floodplains, palm-fringed channels, woodland, and seasonal lakes create a very different rhythm from the northern circuit.',
        'landscape' => 'The sense of scale is immediate. Roads often feel quieter, camps are more spread out, and the river itself changes how game viewing works by bringing boat safaris into the itinerary rather than limiting visitors to standard drives.',
        'wildlife' => 'Elephant, buffalo, lion, hippo, crocodile, and broad birdlife are all major draws, while the park is also one of the places where African wild dog can still be a realistic target for travellers who value remote ecosystems and knowledgeable guiding.',
        'activities' => 'That mix of habitats makes the park ideal for travellers who want variation within a single stay: classic game drives in open areas, sunset river cruises past hippo pods and sandbanks, and in some concessions even guided walking that sharpens attention to tracks and smaller details.',
        'seasonality' => 'is strongest in the drier months when roads are more dependable and wildlife concentrates around permanent water, although the exact feel of the river changes with rainfall and camp location.',
        'logistics' => 'Because southern Tanzania is usually best accessed by air rather than by road from Dar es Salaam, it appeals most to travellers who value space, lower vehicle density, and camps that feel more secluded than many northern properties.',
        'pairing' => 'Nyerere National Park works especially well with Ruaha for a deeper southern safari or as a stand-alone wilderness choice for repeat East Africa travellers who want something less obvious than the migration route.',
        'closing' => 'Meanwhile, guests exploring <a href="/destinations">other remote safari destinations</a> often choose Nyerere National Park inside <a href="/safaris">southern Tanzania safaris</a> because boat-based game viewing, river scenery, and a stronger sense of solitude make the experience feel markedly different from the north.',
    ],
    [
        'name' => 'Mkomazi National Park',
        'slug' => 'mkomazi-national-park',
        'aliases' => ['Mkomazi National Park'],
        'slug_aliases' => ['mkomazi-national-park'],
        'meta_title' => 'Mkomazi National Park safari guide',
        'meta_description' => 'Mkomazi National Park is a quiet northern Tanzania reserve with rhino conservation, dry-country game, and strong views toward Kilimanjaro.',
        'meta_keywords' => 'Mkomazi National Park, Mkomazi safari, black rhino Tanzania, northern dry-country park, wild dog conservation',
        'focus_keyword' => 'Mkomazi National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'tailor-made northern Tanzania routes',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'sits in the dry northeast of Tanzania near the Kenyan border, where open savannah, acacia scrub, rocky hills, and wide skies create a quieter safari environment than the country\'s better-known parks. For travellers who appreciate conservation stories and lighter vehicle traffic, that low profile is part of its appeal.',
        'landscape' => 'The scenery feels more semi-arid than the greener highlands of Arusha or Manyara, and on clear days the long horizon can open toward Kilimanjaro and the Pare mountains in a way that gives the park a striking sense of space.',
        'wildlife' => 'General game includes giraffe, zebra, eland, oryx in some areas, and dry-country birdlife, but Mkomazi National Park is especially important because of its black rhino and African wild dog conservation work within protected sanctuaries and managed zones.',
        'activities' => 'This is not the place to chase nonstop blockbuster sightings. It is better for travellers who want a more reflective safari, where strong guiding, ecological interpretation, and an understanding of restoration work matter as much as ticking species off a list.',
        'seasonality' => 'usually shows best in the drier months when roads are easier and wildlife is more predictable around water, although the park\'s conservation message remains relevant throughout the year.',
        'logistics' => 'Because it lies outside the standard northern circuit flow, it works best as a special-interest addition for repeat travellers, conservation-minded guests, or itineraries linking Kilimanjaro, Materuni, and the quieter parks of the north-east.',
        'pairing' => 'In the right route, it adds both geographic breadth and a useful reminder that Tanzania\'s wildlife story is not limited to the migration parks alone.',
        'closing' => 'Additionally, visitors comparing <a href="/destinations">other Tanzania destinations</a> often place Mkomazi National Park inside <a href="/custom-tour">tailor-made northern Tanzania routes</a> when they want dry-country scenery, conservation depth, and a safari day that feels far less crowded than the mainstream circuit.',
    ],
    [
        'name' => 'Materuni Village',
        'slug' => 'materuni-village',
        'aliases' => ['Materuni Village'],
        'slug_aliases' => ['materuni-village'],
        'meta_title' => 'Materuni Village travel guide',
        'meta_description' => 'Materuni Village offers Chagga culture, coffee making, waterfall walks, and a greener side of Kilimanjaro close to Moshi.',
        'meta_keywords' => 'Materuni Village, Materuni waterfall, Chagga coffee tour, Moshi day trip, Kilimanjaro foothills culture',
        'focus_keyword' => 'Materuni Village',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom Kilimanjaro-area trips',
        'secondary_link' => '/trekking',
        'secondary_anchor' => 'Kilimanjaro trekking itineraries',
        'intro' => 'sits on the green lower slopes of Kilimanjaro above Moshi, where banana farms, cloud forest, and small Chagga settlements create one of the most rewarding cultural and landscape day trips in the region. It is the kind of place that works best when travellers give it time rather than treating it as a quick photo stop.',
        'landscape' => 'The road climbs through increasingly lush farmland and small homesteads before footpaths continue toward streams, ferns, and a tall waterfall hidden in a cool valley. On clear mornings, the broader area can also offer striking views toward Kilimanjaro\'s upper slopes.',
        'wildlife' => 'Materuni Village is not about classic safari game, but it is rich in agriculture, local knowledge, forest-edge scenery, and the cultural landscape created by long-established Chagga farming systems.',
        'activities' => 'A well-run visit usually includes a coffee experience where visitors roast, grind, and brew beans locally, a walk to the waterfall, and time to understand how food crops, banana beer traditions, and everyday village life fit into the mountain economy.',
        'seasonality' => 'is attractive through much of the year, though the trail can be wetter and muddier in rainy periods. That said, the moisture is also what makes the vegetation so vivid, especially compared with drier safari areas elsewhere in Tanzania.',
        'logistics' => 'Because it sits close to Moshi, the village works well before or after a Kilimanjaro climb, as a rest-day excursion, or as a softer cultural counterpoint to a more demanding trekking schedule.',
        'pairing' => 'Materuni is particularly useful for travellers who want their Tanzania trip to include mountain culture and coffee heritage instead of only summit-focused or wildlife-heavy days.',
        'closing' => 'Furthermore, travellers comparing <a href="/trekking">Kilimanjaro trekking itineraries</a> often add Materuni Village to <a href="/custom-tour">custom Kilimanjaro-area trips</a> when they want a greener, more human-scale experience that deepens the story of the mountain beyond the climb itself.',
    ],
    [
        'name' => 'Mikumi National Park',
        'slug' => 'mikumi-national-park',
        'aliases' => ['Mikumi National Park'],
        'slug_aliases' => ['mikumi-national-park'],
        'meta_title' => 'Mikumi National Park safari guide',
        'meta_description' => 'Mikumi National Park offers accessible southern Tanzania game viewing, broad plains, and a practical safari add-on from Dar es Salaam.',
        'meta_keywords' => 'Mikumi National Park, Mikumi safari, southern Tanzania park, Dar es Salaam safari, Mkata floodplain',
        'focus_keyword' => 'Mikumi National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'southern Tanzania safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other safari destinations',
        'intro' => 'is the most approachable safari park in southern Tanzania for many travellers, especially those starting from Dar es Salaam. Its open Mkata floodplain, surrounding hills, and relatively straightforward access make it a practical way to enter the southern circuit without flying immediately into a far more remote camp.',
        'landscape' => 'The central plain is the park\'s signature feature, often compared in feel to a scaled-down East African savannah where zebra, buffalo, wildebeest, and elephant can all be visible against a broad horizon and mountain backdrop.',
        'wildlife' => 'Mikumi National Park is valued less for rare species than for clear, satisfying general game viewing. Lion, giraffe, hippo, antelope, and many birds are realistic targets, and the open terrain often makes sightings easier for first-time safari travellers and families.',
        'activities' => 'Because drives can be productive without extreme distances, the park works well for shorter stays, road-based itineraries, and travellers who want a simpler safari logistics profile than the fly-in reserves deeper in the south.',
        'seasonality' => 'usually improves as water becomes scarcer and wildlife gathers more predictably, although green-season travel can still be scenic and rewarding for birders or guests prioritising value and softer light.',
        'logistics' => 'Its role in an itinerary is often practical as much as scenic: Mikumi can stand alone as a short wildlife break or act as a stepping stone toward Udzungwa, Nyerere, or a longer southern Tanzania route.',
        'pairing' => 'That flexibility makes it useful for travellers who want a real safari without committing to the cost and air logistics of the deepest wilderness areas, and it also gives overland itineraries a practical wildlife anchor between the coast, the mountains, and the remoter southern reserves.',
        'closing' => 'Moreover, travellers browsing <a href="/destinations">other safari destinations</a> often choose Mikumi National Park inside <a href="/safaris">southern Tanzania safaris</a> because it offers accessible game viewing, broad plains, and an easier entry point into the region\'s wilder landscapes.',
    ],
    [
        'name' => 'Mahale Mountain National Park',
        'slug' => 'mahale-mountain-national-park',
        'aliases' => ['Mahale Mountain National Park'],
        'slug_aliases' => ['mahale-mountain-national-park'],
        'meta_title' => 'Mahale Mountain National Park travel guide',
        'meta_description' => 'Mahale Mountain National Park is Tanzania\'s remote chimpanzee destination on Lake Tanganyika, combining forest trekking with beach scenery.',
        'meta_keywords' => 'Mahale Mountain National Park, Mahale chimpanzees, Lake Tanganyika travel, western Tanzania park, remote primate trekking',
        'focus_keyword' => 'Mahale Mountain National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom western Tanzania journeys',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other remote Tanzania destinations',
        'intro' => 'is one of Tanzania\'s most extraordinary but least accessible protected areas, where forested mountains fall directly into the clear blue water of Lake Tanganyika and the main draw is not classic game driving but habituated chimpanzee trekking in truly remote terrain.',
        'landscape' => 'The setting is exceptional: forest-covered ridges, narrow beaches, steep valleys, and one of the deepest freshwater lakes in the world. The contrast between mountain jungle and tropical shoreline gives the park a mood unlike anywhere else in the country.',
        'wildlife' => 'Chimpanzees are the signature experience, but they are not a guaranteed stage-managed sighting. The value lies in patient tracking, listening to guides read forest signs, and understanding behaviour rather than rushing through the bush in search of a quick encounter.',
        'activities' => 'When conditions allow, stays can also include kayaking, swimming in Tanganyika, dhow trips, birding, and long periods of stillness between treks that make the entire visit feel more like an expedition than a checklist holiday.',
        'seasonality' => 'is shaped by lake access, trekking conditions, and camp operations, so planning matters. The park is generally strongest for travellers who accept that remoteness, transfers, and weather are part of what make the destination feel special rather than inconvenient.',
        'logistics' => 'Because access usually involves flights and then boat transfers, Mahale fits best into longer, higher-value itineraries where travellers want depth, solitude, and one or two transformative wildlife experiences rather than constant movement.',
        'pairing' => 'It pairs naturally with Katavi or as a stand-alone western Tanzania journey for guests who have already visited the mainstream safari circuit.',
        'closing' => 'Additionally, guests comparing <a href="/destinations">other remote Tanzania destinations</a> often place Mahale Mountain National Park inside <a href="/custom-tour">custom western Tanzania journeys</a> because chimp trekking, lake scenery, and deep isolation create a travel experience that feels radically different from the northern safari model.',
    ],
    [
        'name' => 'Katavi National Park',
        'slug' => 'katavi-national-park',
        'aliases' => ['Katavi National Park'],
        'slug_aliases' => ['katavi-national-park'],
        'meta_title' => 'Katavi National Park safari guide',
        'meta_description' => 'Katavi National Park is one of Tanzania\'s wildest safari areas, known for remoteness, dry-season drama, and powerful big-game sightings.',
        'meta_keywords' => 'Katavi National Park, Katavi safari, western Tanzania safari, remote big game park, dry season wildlife',
        'focus_keyword' => 'Katavi National Park',
        'primary_link' => '/safaris',
        'primary_anchor' => 'remote Tanzania safaris',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other western Tanzania destinations',
        'intro' => 'is one of Tanzania\'s last truly wild safari arenas, a western park where floodplains, seasonal rivers, tamarind woodland, and shrinking dry-season pools create a powerful sense of elemental wildlife concentration without the traffic associated with more famous parks.',
        'landscape' => 'The park feels broad, old, and lightly touched. In the wet season the plains can seem expansive and green; in the dry months, water retreats and the landscape hardens into a stage for some of the most intense animal competition in the country.',
        'wildlife' => 'That shift is what gives Katavi National Park its reputation. Hippo crowd into diminishing pools, crocodiles wait along muddy channels, buffalo gather in impressive numbers, and predators work the edges where prey is forced into tighter space.',
        'activities' => 'Safaris here are less about ticking every species and more about staying present long enough to see behaviour unfold. Good guiding, patient vehicle work, and a willingness to spend time at water rather than racing onward are what reveal the park at its best.',
        'seasonality' => 'matters enormously. The drier months deliver the most famous wildlife pressure and visibility, while wetter periods offer greener scenery but a less concentrated, more dispersive safari pattern.',
        'logistics' => 'Because access is usually by scheduled or charter air and camp capacity is limited, the park best suits experienced safari travellers or clients who want exclusivity through remoteness rather than luxury alone.',
        'pairing' => 'Katavi works particularly well with Mahale for a western Tanzania combination that mixes big-game wilderness with chimp trekking and lake scenery, especially for travellers who want one journey to move from predator-heavy floodplains into forest and shoreline environments without losing the sense of remoteness.',
        'closing' => 'Meanwhile, travellers researching <a href="/destinations">other western Tanzania destinations</a> often choose Katavi National Park inside <a href="/safaris">remote Tanzania safaris</a> because very few places still feel this raw, this spacious, and this dependent on season and patience for their rewards.',
    ],
    [
        'name' => 'Kitulo National Park',
        'slug' => 'kitulo-national-park',
        'aliases' => ['Kitulo National Park'],
        'slug_aliases' => ['kitulo-national-park'],
        'meta_title' => 'Kitulo National Park travel guide',
        'meta_description' => 'Kitulo National Park is Tanzania\'s mountain grassland park, prized for orchids, wildflowers, hiking, and highland scenery.',
        'meta_keywords' => 'Kitulo National Park, Kitulo flowers, Tanzania hiking park, orchid plateau, southern highlands travel',
        'focus_keyword' => 'Kitulo National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom southern highlands trips',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is unlike Tanzania\'s better-known wildlife parks because its fame rests on alpine grassland, seasonal flowers, and the quiet drama of the southern highlands rather than on big-game safari circuits. For hikers, botanists, and travellers who value landscape detail, that difference is exactly the point.',
        'landscape' => 'The park protects an elevated plateau framed by mountains, where rolling meadows, streams, and montane vegetation create one of the country\'s most distinctive cool-climate environments. During flowering periods, the ground can become extraordinarily rich in colour.',
        'wildlife' => 'Kitulo National Park is celebrated especially for orchids, endemic plants, butterflies, and birdlife. It is not a place to chase lion or elephant, but it is a place where people notice textures, species richness, and the importance of habitats that are often ignored in mainstream safari planning.',
        'activities' => 'Walking, scenic driving, botanical observation, and highland photography are the core experiences here. The reward comes from slowing down, reading the plateau carefully, and understanding the Eastern Afromontane setting rather than expecting a conventional game drive.',
        'seasonality' => 'is central to the experience because flower displays depend on rainfall and timing. Visitors who arrive during the right months see why the area is often called the Serengeti of Flowers, while drier periods highlight the broader shape of the plateau and its hiking potential.',
        'logistics' => 'The park fits best for travellers already interested in the southern highlands, Mbeya region, or unusual natural history rather than for first-time visitors seeking a standard safari check-list.',
        'pairing' => 'It can work beautifully with Udzungwa, Ruaha, or cultural stops in the southern interior when a trip is built around diversity rather than only headline animals.',
        'closing' => 'Furthermore, guests comparing <a href="/destinations">other Tanzania destinations</a> often add Kitulo National Park to <a href="/custom-tour">custom southern highlands trips</a> when they want flowers, hiking, and mountain scenery that show a completely different side of Tanzania than the classic safari plains.',
    ],
    [
        'name' => 'Saanane Island National Park',
        'slug' => 'saanane-island-national-park',
        'aliases' => ['Saanane Island National Park', 'Saanane Island  National Park'],
        'slug_aliases' => ['saanane-island-national-park'],
        'meta_title' => 'Saanane Island National Park travel guide',
        'meta_description' => 'Saanane Island National Park brings rocky Lake Victoria scenery, short wildlife outings, and easy access from Mwanza city.',
        'meta_keywords' => 'Saanane Island National Park, Mwanza day trip, Lake Victoria island park, Tanzania urban park, Saanane guide',
        'focus_keyword' => 'Saanane Island National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom Tanzania travel plans',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'sits just off Mwanza on Lake Victoria and stands out because it is Tanzania\'s most accessible island national park. Rather than remote wilderness, it offers a compact protected landscape of granite outcrops, lakeshore views, and short nature outings within reach of a major city.',
        'landscape' => 'The park\'s rocky setting is very characteristic of the Mwanza region, where massive boulders and island vegetation meet the broad inland sea of Lake Victoria. That scenery gives even a short visit a recognisable sense of place.',
        'wildlife' => 'Saanane Island National Park is not designed to compete with Serengeti-scale safari parks. Its value lies in smaller-scale wildlife such as impala, monkeys, rock hyrax, reptiles, and birds, together with the unusual experience of reaching a national park by a short boat ride from the city.',
        'activities' => 'Walking, picnicking, birdwatching, photography, and half-day escapes from Mwanza are the main strengths here. It is especially useful for business travellers, families, or overland routes that want a nature stop without a long transfer.',
        'seasonality' => 'is not the main planning issue; instead, travellers should think about weather, lake conditions, and how much time they want to dedicate to Mwanza and the Lake Victoria corridor overall.',
        'logistics' => 'Because the island is so close to town, it can be combined with city visits, local history, or onward travel toward western Tanzania rather than being treated as a stand-alone wilderness expedition.',
        'pairing' => 'It works best as a regional highlight for visitors already moving through Mwanza or connecting between northern and western routes.',
        'closing' => 'Additionally, travellers comparing <a href="/destinations">other Tanzania destinations</a> often place Saanane Island National Park inside <a href="/custom-tour">custom Tanzania travel plans</a> when they want a short, scenic, and unexpectedly distinctive nature outing linked to Lake Victoria and Mwanza.',
    ],
    [
        'name' => 'Udzungwa Mountain National Park',
        'slug' => 'udzungwa-mountain-national-park',
        'aliases' => ['Udzungwa Mountain National Park', 'Udzungwa Mountain National Park'],
        'slug_aliases' => ['udzungwa-mountain-national-park'],
        'meta_title' => 'Udzungwa Mountain National Park travel guide',
        'meta_description' => 'Udzungwa Mountain National Park is Tanzania\'s prime hiking rainforest, known for endemic primates, waterfalls, and Eastern Arc biodiversity.',
        'meta_keywords' => 'Udzungwa Mountain National Park, Udzungwa hiking, Sanje Falls, endemic primates Tanzania, Eastern Arc Mountains',
        'focus_keyword' => 'Udzungwa Mountain National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom southern Tanzania journeys',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is Tanzania\'s leading hiking park for travellers who want rainforest, waterfalls, and rare biodiversity rather than a vehicle-based safari. Part of the Eastern Arc chain, it protects one of East Africa\'s most important blocks of ancient mountain forest and a high concentration of endemic species.',
        'landscape' => 'Trails climb through humid forest, across streams, and toward viewpoints and waterfalls, with the Sanje Falls route providing one of the park\'s best-known day hikes and broad views over the Kilombero Valley below.',
        'wildlife' => 'Udzungwa Mountain National Park is especially valuable for endemic and near-endemic primates, including species such as the Sanje mangabey and the Iringa red colobus, along with rich birdlife, forest plants, and insect diversity that reward travellers who enjoy natural history in detail.',
        'activities' => 'The experience here is active and on foot. Visitors come to hike, listen to the forest, watch primates move through the canopy, and spend time in a landscape where altitude, humidity, and vegetation all shape the pace of travel.',
        'seasonality' => 'should be considered carefully because rain affects trails and visibility, but moisture is also what gives the park its remarkable ecological richness. The right time depends on whether you prioritise easier hiking or peak forest atmosphere.',
        'logistics' => 'Udzungwa works best for travellers already moving through southern Tanzania, especially those connecting Mikumi, Ruaha, or the southern highlands and wanting a real walking component in the journey.',
        'pairing' => 'It is particularly strong for repeat visitors who have already done classic safari driving and want a different encounter with Tanzania\'s protected landscapes.',
        'closing' => 'Moreover, guests exploring <a href="/destinations">other Tanzania destinations</a> often add Udzungwa Mountain National Park to <a href="/custom-tour">custom southern Tanzania journeys</a> when they want forest hiking, endemic wildlife, and a more active counterpart to the region\'s savannah parks.',
    ],
    [
        'name' => 'Saadani National Park',
        'slug' => 'saadani-national-park',
        'aliases' => ['Saadani National Park'],
        'slug_aliases' => ['saadani-national-park'],
        'meta_title' => 'Saadani National Park travel guide',
        'meta_description' => 'Saadani National Park is where bush meets beach in Tanzania, combining wildlife, the Wami River, and Indian Ocean shoreline.',
        'meta_keywords' => 'Saadani National Park, Saadani beach safari, Wami River safari, coastal Tanzania park, bush and beach Tanzania',
        'focus_keyword' => 'Saadani National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom bush and beach itineraries',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other Tanzania destinations',
        'intro' => 'is one of the few places in East Africa where a safari landscape runs directly into the Indian Ocean. That meeting of coastal bush, riverine habitat, beaches, and marine edge gives the park a character that is impossible to confuse with the inland reserves of either the north or south.',
        'landscape' => 'The Wami River, mangrove zones, coastal forest, and open grassland create a layered environment in which a game drive, a boat excursion, and a quiet stretch of beach can all fit into the same stay.',
        'wildlife' => 'Elephant, giraffe, buffalo, antelope, primates, crocodile, and birdlife shape the main wildlife story, while the river adds a different viewing angle and the coastline brings seasonal marine interest and a sense of space rare in most safari parks.',
        'activities' => 'Saadani National Park is best for travellers who like variety: game drives, Wami River boat trips, beach walks, birding, and coastal scenery matter more here than chasing a classic Big Five checklist.',
        'seasonality' => 'needs some planning because coastal humidity, rain, turtle activity, and river conditions can all influence the experience. Guests who arrive with the right expectations usually appreciate the park far more than those expecting a northern-circuit style safari.',
        'logistics' => 'Its position between Dar es Salaam and the north coast makes it a useful option for overland coastal routes, beach-and-bush combinations, or private itineraries that avoid internal flights.',
        'pairing' => 'It works especially well with Zanzibar or a mainland coastal extension when the goal is to keep wildlife in the programme while staying close to the ocean.',
        'closing' => 'Meanwhile, travellers comparing <a href="/destinations">other Tanzania destinations</a> often choose Saadani National Park inside <a href="/custom-tour">custom bush and beach itineraries</a> because it offers a rare blend of safari, river life, and coastline in a single protected landscape.',
    ],
    [
        'name' => 'Gombe National Park',
        'slug' => 'gombe-national-park',
        'aliases' => ['Gombe National Park'],
        'slug_aliases' => ['gombe-national-park'],
        'meta_title' => 'Gombe National Park travel guide',
        'meta_description' => 'Gombe National Park is Tanzania\'s historic chimpanzee park on Lake Tanganyika, known for steep forest trails and Jane Goodall\'s research legacy.',
        'meta_keywords' => 'Gombe National Park, Gombe chimpanzees, Jane Goodall Tanzania, Lake Tanganyika park, western Tanzania primates',
        'focus_keyword' => 'Gombe National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom western Tanzania trips',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other western Tanzania destinations',
        'intro' => 'is one of Tanzania\'s most historically important conservation areas, known worldwide for chimpanzee research and for the steep forested slopes that drop into Lake Tanganyika. It is small in size, but its scientific legacy and emotional power make it a major destination for travellers interested in primates and behavioural ecology.',
        'landscape' => 'The park feels intimate and vertical rather than expansive. Trails move through forested valleys, ridges, and lake-edge zones, and access by boat reinforces the sense that you are arriving at a specialised field site rather than a conventional safari park.',
        'wildlife' => 'Chimpanzees are the core attraction, yet the value of Gombe National Park goes beyond a simple sighting. The destination is about understanding habituation, long-term research, troop dynamics, and the work pioneered here through Jane Goodall\'s presence and observation.',
        'activities' => 'Trekking can be physically demanding because of the terrain, but that effort is part of what makes encounters meaningful. Time on the lake, simple shoreline downtime, and conversations with guides about chimp ecology often deepen the visit significantly.',
        'seasonality' => 'affects trail conditions and sighting comfort, so it is worth discussing expectations clearly before planning. Guests who treat the park as a primate-focused expedition generally value it far more than those expecting a relaxed vehicle safari.',
        'logistics' => 'Because access involves flights to Kigoma and onward boat movement, Gombe works best for travellers willing to devote time and budget to western Tanzania rather than squeezing it into a short general itinerary.',
        'pairing' => 'It can be combined with cultural and lakeside experiences around Kigoma or paired conceptually with Mahale for a deeper chimpanzee-focused journey.',
        'closing' => 'Additionally, visitors researching <a href="/destinations">other western Tanzania destinations</a> often place Gombe National Park inside <a href="/custom-tour">custom western Tanzania trips</a> because its research history, lake setting, and chimpanzee trekking experience feel unlike any standard safari product.',
    ],
    [
        'name' => 'Rubondo Island National Park',
        'slug' => 'rubondo-island-national-park',
        'aliases' => ['Rubondo Island National Park'],
        'slug_aliases' => ['rubondo-island-national-park'],
        'meta_title' => 'Rubondo Island National Park travel guide',
        'meta_description' => 'Rubondo Island National Park protects forest, shoreline, and rare wildlife on Lake Victoria, with chimp tracking and remote island atmosphere.',
        'meta_keywords' => 'Rubondo Island National Park, Rubondo Island, Lake Victoria safari, chimp tracking Tanzania, island wilderness park',
        'focus_keyword' => 'Rubondo Island National Park',
        'primary_link' => '/custom-tour',
        'primary_anchor' => 'custom remote Tanzania journeys',
        'secondary_link' => '/destinations',
        'secondary_anchor' => 'other unusual Tanzania destinations',
        'intro' => 'protects a large forested island and surrounding smaller islets in Lake Victoria, creating one of Tanzania\'s most unusual conservation landscapes. It is a destination defined by water, thick forest, low visitor numbers, and a feeling of remoteness that is very different from both mainland safari parks and beach islands on the coast.',
        'landscape' => 'The island is draped in evergreen and semi-deciduous forest, papyrus-fringed in places, and cut by bays and shoreline that make boat movement and lake views part of the daily experience rather than a simple transfer detail.',
        'wildlife' => 'Rubondo Island National Park is notable for chimpanzee rehabilitation history, rich birdlife, and species such as sitatunga, elephant, and other animals that give the island a highly individual ecological profile. Sightings can be more exploratory than guaranteed, which suits travellers who enjoy discovery rather than a rigid species checklist.',
        'activities' => 'Birding, forest walks, boat outings, fishing in designated contexts, and slow immersion in the island environment are central strengths here. The park appeals to people who like wilderness with a slightly expeditionary edge.',
        'seasonality' => 'should be matched to lake conditions, activity priorities, and transfer planning, but the destination is most rewarding for travellers who value atmosphere and habitat as much as headline wildlife encounters.',
        'logistics' => 'Because access requires coordination and the experience is specialised, Rubondo is best for repeat visitors, photographers, birders, or clients who want something genuinely unusual within Tanzania.',
        'pairing' => 'It can stand alone as a niche remote escape or complement western and lake-region travel for guests who want to see how diverse the country\'s protected areas really are.',
        'closing' => 'Furthermore, travellers comparing <a href="/destinations">other unusual Tanzania destinations</a> often choose Rubondo Island National Park inside <a href="/custom-tour">custom remote Tanzania journeys</a> because it combines island atmosphere, forest ecology, and conservation depth in a way that few African parks can match.',
    ],
];

function buildDescription(array $destination): string
{
    $name = $destination['focus_keyword'];

    return implode("\n", [
        '<h2>' . e($name) . ': why this destination matters</h2>',
        '<p>' . e($name) . ' ' . e($destination['intro']) . ' ' . e($destination['landscape']) . '</p>',
        '<p>' . e($destination['wildlife']) . ' ' . e($destination['activities']) . '</p>',
        '<h2>Planning time in ' . e($name) . '</h2>',
        '<p>' . e($name) . ' ' . e($destination['seasonality']) . ' ' . e($destination['logistics']) . ' ' . e($destination['pairing']) . '</p>',
        '<p>' . $destination['closing'] . '</p>',
    ]);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

function cleanJsonResponse(string $text): string
{
    $text = trim($text);
    $text = preg_replace('/^```json\s*/i', '', $text) ?? $text;
    $text = preg_replace('/^```\s*/i', '', $text) ?? $text;
    $text = preg_replace('/\s*```$/', '', $text) ?? $text;

    return trim($text);
}

function translateViaMyMemory(string $text, string $targetLanguage): string
{
    $response = Http::withoutVerifying()->timeout(90)->get('https://api.mymemory.translated.net/get', [
        'q' => $text,
        'langpair' => 'en|' . $targetLanguage,
    ]);

    if (! $response->successful()) {
        throw new RuntimeException('MyMemory translation failed for language ' . $targetLanguage);
    }

    $translated = (string) $response->json('responseData.translatedText', '');

    if ($translated === '') {
        throw new RuntimeException('MyMemory returned empty translation for language ' . $targetLanguage);
    }

    return $translated;
}

function translateDestinationViaMyMemory(array $destination): array
{
    return [
        'fr_name' => translateViaMyMemory($destination['name'], 'fr'),
        'de_name' => translateViaMyMemory($destination['name'], 'de'),
        'es_name' => translateViaMyMemory($destination['name'], 'es'),
        'fr_description' => translateViaMyMemory($destination['description'], 'fr'),
        'de_description' => translateViaMyMemory($destination['description'], 'de'),
        'es_description' => translateViaMyMemory($destination['description'], 'es'),
    ];
}

function translateDestination(array $destination, array &$cache, string $cachePath, string $geminiKey, string $geminiModel): array
{
    $slug = $destination['slug'];

    if (isset($cache[$slug])) {
        return $cache[$slug];
    }

    if ($geminiKey === '') {
        throw new RuntimeException('GEMINI_API_KEY is missing.');
    }

    $prompt = <<<PROMPT
Translate the following English tourism content into French, German, and Spanish.

Rules:
- Preserve all HTML tags exactly, including <h2>, <p>, and <a href="..."> links.
- Preserve every URL exactly as written.
- Keep the tone editorial and destination-specific, not generic.
- Return strict JSON only with these keys:
  fr_name, de_name, es_name, fr_description, de_description, es_description
- Do not wrap the JSON in markdown fences.

English name: {$destination['name']}

English HTML description:
{$destination['description']}
PROMPT;

    $models = [$geminiModel];
    if ($geminiModel !== 'gemini-2.0-flash-lite') {
        $models[] = 'gemini-2.0-flash-lite';
    }

    foreach ($models as $model) {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $geminiKey;

        $response = Http::withoutVerifying()->timeout(90)->post($url, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $prompt]],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'maxOutputTokens' => 8192,
                'responseMimeType' => 'application/json',
            ],
        ]);

        if (! $response->successful()) {
            continue;
        }

        $text = (string) $response->json('candidates.0.content.parts.0.text', '');
        $payload = json_decode(cleanJsonResponse($text), true);

        if (! is_array($payload)) {
            continue;
        }

        $required = ['fr_name', 'de_name', 'es_name', 'fr_description', 'de_description', 'es_description'];
        $missing = array_diff($required, array_keys($payload));

        if ($missing !== []) {
            continue;
        }

        $cache[$slug] = $payload;
        file_put_contents($cachePath, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $payload;
    }

    $fallback = translateDestinationViaMyMemory($destination);
    $cache[$slug] = $fallback;
    file_put_contents($cachePath, json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

    return $fallback;
}

function upsertDestination(array $destination, SeoAnalyzer $analyzer): array
{
    $query = Destination::query()->where('slug', $destination['slug']);

    if ($destination['aliases'] !== []) {
        $query->orWhereIn('name', $destination['aliases']);
    }

    if ($destination['slug_aliases'] !== []) {
        $query->orWhereIn('slug', $destination['slug_aliases']);
    }

    $model = $query->first() ?? new Destination();
    $wasExisting = $model->exists;

    $model->fill([
        'country_id' => 1,
        'name' => $destination['name'],
        'slug' => $destination['slug'],
        'description' => $destination['description'],
        'meta_title' => $destination['meta_title'],
        'meta_description' => $destination['meta_description'],
        'meta_keywords' => $destination['meta_keywords'],
    ]);

    $model->setTranslations('name', [
        'en' => $destination['name'],
        'fr' => $destination['fr_name'],
        'de' => $destination['de_name'],
        'es' => $destination['es_name'],
    ]);

    $model->setTranslations('description', [
        'en' => $destination['description'],
        'fr' => $destination['fr_description'],
        'de' => $destination['de_description'],
        'es' => $destination['es_description'],
    ]);

    $model->save();

    $analysis = $analyzer->analyze([
        'focus_keyword' => $destination['focus_keyword'],
        'title' => $destination['name'],
        'meta_title' => $destination['meta_title'],
        'meta_description' => $destination['meta_description'],
        'content' => $destination['description'],
        'slug' => $destination['slug'],
    ]);

    SeoMeta::updateOrCreate(
        [
            'seoable_type' => Destination::class,
            'seoable_id' => $model->id,
            'locale' => 'en',
        ],
        [
            'focus_keyword' => $destination['focus_keyword'],
            'seo_score' => $analysis['seo_score'],
            'readability_score' => $analysis['readability_score'],
            'analysis_data' => [
                'checks' => $analysis['checks'],
                'suggestions' => $analysis['suggestions'],
            ],
            'last_analyzed_at' => now(),
        ]
    );

    return [
        'status' => $wasExisting ? 'updated' : 'created',
        'id' => $model->id,
        'seo_score' => $analysis['seo_score'],
        'readability_score' => $analysis['readability_score'],
    ];
}

$analyzer = app(SeoAnalyzer::class);

$created = 0;
$updated = 0;

foreach ($destinations as $index => $destination) {
    $destination['description'] = buildDescription($destination);
    $translations = translateDestination($destination, $translationCache, $translationCachePath, $geminiKey, $geminiModel);

    $destination['fr_name'] = trim((string) $translations['fr_name']);
    $destination['de_name'] = trim((string) $translations['de_name']);
    $destination['es_name'] = trim((string) $translations['es_name']);
    $destination['fr_description'] = trim((string) $translations['fr_description']);
    $destination['de_description'] = trim((string) $translations['de_description']);
    $destination['es_description'] = trim((string) $translations['es_description']);

    $result = upsertDestination($destination, $analyzer);

    if ($result['status'] === 'created') {
        $created++;
    } else {
        $updated++;
    }

    echo sprintf(
        "[%02d/%02d] %s %s (ID %d) | SEO %d | Readability %d\n",
        $index + 1,
        count($destinations),
        strtoupper($result['status']),
        $destination['name'],
        $result['id'],
        $result['seo_score'],
        $result['readability_score']
    );
}

echo "\nDone. Created: {$created}, Updated: {$updated}\n";