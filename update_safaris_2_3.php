<?php
/**
 * Complete content overhaul for Safari #2 (8-Day Luxury) and Safari #3 (6-Day Adventure).
 * Fixes: titles, descriptions, highlights, inclusions, exclusions, itineraries, SEO, translations.
 *
 * Usage: php update_safaris_2_3.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SafariPackage;
use App\Models\Itinerary;
use Illuminate\Support\Facades\DB;

// ═══════════════════════════════════════════════════════
// SAFARI #3 — 6-Day Tanzania Wildlife Safari Adventure
// ═══════════════════════════════════════════════════════

$safari3 = SafariPackage::findOrFail(3);
$safari3->update([
    'title'    => '6-Day Tanzania Wildlife Safari Adventure',
    'slug'     => '6-day-tanzania-wildlife-safari-adventure',
    'duration' => '6 Days / 5 Nights',
    'difficulty' => 'Easy to Moderate',
    'price'    => 2850.00,
    'currency' => 'USD',
    'status'   => 'published',
    'safari_type'  => 'safari',
    'tour_type_id' => 1,  // Wildlife Safari
    'category_id'  => 10, // Premium
    'featured'     => true,
    'is_popular'   => true,

    'short_description' => 'Discover Tanzania\'s legendary Northern Circuit on this action-packed 6-day wildlife safari. Journey from Tarangire\'s elephant herds and ancient baobabs through the endless Serengeti plains to the Ngorongoro Crater — a natural amphitheatre teeming with Big Five wildlife. Expert guides, premium lodges, and daily game drives ensure unforgettable encounters at every turn.',

    'description' => "This carefully crafted 6-day safari takes you through the heart of Tanzania's Northern Safari Circuit — one of the most wildlife-rich corridors on Earth. Beginning in the charming hill town of Arusha, your journey unfolds across three of Africa's most iconic national parks.\n\nDay by day, you'll witness Tarangire's massive elephant herds gathering around the Tarangire River, scan the Serengeti's golden savannas for prowling lion prides and elusive leopards, and descend into the Ngorongoro Crater — the world's largest unflooded volcanic caldera — where an extraordinary concentration of wildlife thrives in a self-contained ecosystem.\n\nEvery detail is designed for immersion: morning and afternoon game drives timed for peak animal activity, comfortable lodges positioned for panoramic bush views, and knowledgeable Tanzanian guides who bring the ecosystem to life with decades of field experience. Whether it's your first African safari or your tenth, this itinerary delivers the quintessential Tanzania experience.",

    'overview_title' => 'Safari Overview',

    'highlights_title' => 'Safari Highlights',
    'highlights_intro' => 'The defining moments that make this 6-day Northern Circuit safari truly unforgettable.',
    'highlights' => [
        'Full-day game drive in the Serengeti — Africa\'s most iconic savanna',
        'Descend into the Ngorongoro Crater for Big Five viewing in a volcanic amphitheatre',
        'Witness Tarangire\'s legendary elephant herds among towering baobab trees',
        'Morning and afternoon game drives timed for golden-hour wildlife encounters',
        'Scenic drive through the Great Rift Valley with panoramic viewpoints',
        'Comfortable premium lodges with bush views and evening sundowners',
        'Expert Tanzanian naturalist guide throughout the entire journey',
        'Chance to spot the tree-climbing lions of Lake Manyara region',
    ],

    'inclusions_title' => 'What\'s Included & Excluded',
    'inclusions_intro' => 'Everything you need to know about what\'s covered in your safari package and what to plan for separately.',
    'included' => [
        'Airport pick-up and drop-off in Arusha',
        'All park entrance fees and conservation levies',
        'Professional English-speaking safari guide and driver',
        'Private 4×4 safari vehicle with pop-up roof and charging ports',
        '5 nights\' accommodation in premium lodges and tented camps',
        'All meals during the safari (breakfast, lunch, and dinner)',
        'Unlimited bottled drinking water throughout the trip',
        'Game drives as per the itinerary (morning and afternoon)',
        'Ngorongoro Crater service fee and descent permit',
        'Binoculars and wildlife reference books in the vehicle',
    ],
    'excluded' => [
        'International and domestic flights',
        'Travel and medical insurance (mandatory)',
        'Tanzania tourist visa fee (US$50)',
        'Tips and gratuities for guide, cook, and lodge staff',
        'Alcoholic and premium beverages',
        'Personal expenses (laundry, souvenirs, phone calls)',
        'Optional balloon safari over the Serengeti (US$599 per person)',
        'Any activities not mentioned in the itinerary',
    ],

    'seasonal_pricing' => [
        'low_season'  => ['period' => 'April – May', 'price_per_person' => 2450, 'notes' => 'Green season — fewer crowds, lush landscapes, excellent birding'],
        'mid_season'  => ['period' => 'June – October', 'price_per_person' => 2850, 'notes' => 'Dry season — peak wildlife viewing, Great Migration in the Serengeti'],
        'high_season' => ['period' => 'November – March', 'price_per_person' => 3250, 'notes' => 'Calving season, migratory birds, and festive period surcharge applies in Dec'],
    ],

    'meta_title'       => '6-Day Tanzania Safari | Tarangire, Serengeti & Ngorongoro | Lomo Safari',
    'meta_description' => 'Book a 6-day Tanzania wildlife safari through Tarangire, Serengeti & Ngorongoro Crater. Big Five game drives, premium lodges & expert guides. From $2,850 pp.',
    'meta_keywords'    => '6 day tanzania safari, serengeti safari package, ngorongoro crater tour, tarangire game drive, tanzania wildlife safari, big five safari tanzania, northern circuit safari',

    // ── Translations ──
    'title_translations' => [
        'fr' => 'Aventure Safari 6 Jours en Tanzanie',
        'de' => '6-Tage Wildlife-Safari Abenteuer Tansania',
        'es' => 'Aventura Safari de 6 Días en Tanzania',
    ],
    'short_description_translations' => [
        'fr' => 'Découvrez le légendaire Circuit Nord de la Tanzanie lors de ce safari de 6 jours riche en émotions. Des troupeaux d\'éléphants de Tarangire aux plaines infinies du Serengeti, puis au cratère du Ngorongoro — un amphithéâtre naturel grouillant de la faune des Big Five. Guides experts, lodges premium et safaris quotidiens garantissent des rencontres inoubliables.',
        'de' => 'Entdecken Sie Tansanias legendären Northern Circuit auf dieser actionreichen 6-Tage-Wildlife-Safari. Von Tarangires Elefantenherden und uralten Baobabs über die endlosen Serengeti-Ebenen bis zum Ngorongoro-Krater — einem natürlichen Amphitheater voller Big-Five-Wildtiere. Experten-Guides, Premium-Lodges und tägliche Pirschfahrten sorgen für unvergessliche Begegnungen.',
        'es' => 'Descubre el legendario Circuito Norte de Tanzania en este emocionante safari de 6 días. Desde las manadas de elefantes de Tarangire hasta las interminables llanuras del Serengeti y el Cráter del Ngorongoro — un anfiteatro natural repleto de fauna de los Cinco Grandes. Guías expertos, alojamientos premium y safaris diarios garantizan encuentros inolvidables.',
    ],
    'description_translations' => [
        'fr' => "Ce safari de 6 jours soigneusement conçu vous emmène au cœur du Circuit Nord de la Tanzanie — l'un des corridors les plus riches en faune sur Terre. Depuis la charmante ville d'Arusha, votre voyage se déroule à travers trois des parcs nationaux les plus emblématiques d'Afrique.\n\nJour après jour, vous observerez les impressionnants troupeaux d'éléphants de Tarangire, parcourrez les savanes dorées du Serengeti à la recherche de lions et de léopards, et descendrez dans le cratère du Ngorongoro — la plus grande caldeira non inondée au monde — où une concentration extraordinaire de faune prospère dans un écosystème autonome.\n\nChaque détail est pensé pour l'immersion : safaris matin et après-midi, lodges confortables avec vues panoramiques sur la brousse, et guides tanzaniens expérimentés qui donnent vie à l'écosystème.",
        'de' => "Diese sorgfältig gestaltete 6-Tage-Safari führt Sie durch das Herz von Tansanias nördlichem Safari-Circuit — einem der wildtierreichsten Korridore der Erde. Beginnend in der charmanten Stadt Arusha entfaltet sich Ihre Reise durch drei der ikonischsten Nationalparks Afrikas.\n\nTag für Tag erleben Sie Tarangires massive Elefantenherden, durchstreifen die goldenen Savannen der Serengeti auf der Suche nach Löwenrudeln und Leoparden und steigen in den Ngorongoro-Krater hinab — die größte nicht überflutete vulkanische Caldera der Welt — wo eine außergewöhnliche Konzentration von Wildtieren in einem eigenständigen Ökosystem gedeiht.\n\nJedes Detail ist auf Immersion ausgelegt: Morgen- und Nachmittags-Pirschfahrten, komfortable Lodges mit Panoramablick auf den Busch und erfahrene tansanische Guides.",
        'es' => "Este safari de 6 días cuidadosamente diseñado te lleva por el corazón del Circuito Norte de Tanzania — uno de los corredores más ricos en fauna del planeta. Partiendo de la encantadora ciudad de Arusha, tu viaje se desarrolla a través de tres de los parques nacionales más icónicos de África.\n\nDía a día, observarás las enormes manadas de elefantes de Tarangire, recorrerás las sabanas doradas del Serengeti buscando manadas de leones y leopardos, y descenderás al Cráter del Ngorongoro — la caldera volcánica no inundada más grande del mundo — donde una concentración extraordinaria de fauna prospera en un ecosistema autónomo.\n\nCada detalle está diseñado para la inmersión: safaris matutinos y vespertinos, alojamientos confortables con vistas panorámicas y guías tanzanos experimentados.",
    ],
    'overview_title_translations' => [
        'fr' => 'Aperçu du Safari',
        'de' => 'Safari-Übersicht',
        'es' => 'Resumen del Safari',
    ],
    'highlights_title_translations' => [
        'fr' => 'Points Forts du Safari',
        'de' => 'Safari-Highlights',
        'es' => 'Momentos Destacados',
    ],
    'highlights_intro_translations' => [
        'fr' => 'Les moments forts qui rendent ce safari de 6 jours dans le Circuit Nord véritablement inoubliable.',
        'de' => 'Die prägenden Momente, die diese 6-tägige Northern-Circuit-Safari unvergesslich machen.',
        'es' => 'Los momentos que definen este safari de 6 días por el Circuito Norte y lo hacen verdaderamente inolvidable.',
    ],
    'inclusions_title_translations' => [
        'fr' => 'Inclus et Non Inclus',
        'de' => 'Eingeschlossen & Ausgeschlossen',
        'es' => 'Incluido y No Incluido',
    ],
    'inclusions_intro_translations' => [
        'fr' => 'Tout ce que vous devez savoir sur ce qui est couvert dans votre forfait safari et ce qui est à prévoir séparément.',
        'de' => 'Alles, was Sie über die im Safari-Paket enthaltenen und nicht enthaltenen Leistungen wissen müssen.',
        'es' => 'Todo lo que necesitas saber sobre lo que está cubierto en tu paquete de safari y lo que debes planificar por separado.',
    ],
    'highlights_translations' => [
        'fr' => [
            'Safari journée complète dans le Serengeti — la savane la plus emblématique d\'Afrique',
            'Descente dans le cratère du Ngorongoro pour observer les Big Five dans un amphithéâtre volcanique',
            'Observation des légendaires troupeaux d\'éléphants de Tarangire parmi les baobabs géants',
            'Safaris matin et après-midi aux heures dorées pour des rencontres optimales',
            'Traversée panoramique de la Grande Vallée du Rift',
            'Lodges premium confortables avec vues sur la brousse et sundowners en soirée',
            'Guide naturaliste tanzanien expert tout au long du voyage',
            'Possibilité d\'observer les lions grimpeurs de la région du lac Manyara',
        ],
        'de' => [
            'Ganztägige Pirschfahrt in der Serengeti — Afrikas ikonischste Savanne',
            'Abstieg in den Ngorongoro-Krater für Big-Five-Beobachtungen im vulkanischen Amphitheater',
            'Tarangires legendäre Elefantenherden zwischen riesigen Baobab-Bäumen erleben',
            'Morgen- und Nachmittags-Pirschfahrten zu den goldenen Stunden für optimale Begegnungen',
            'Panoramafahrt durch das Große Rift Valley mit Aussichtspunkten',
            'Komfortable Premium-Lodges mit Buschblick und abendlichen Sundowners',
            'Erfahrener tansanischer Naturführer während der gesamten Reise',
            'Chance, die baumkletternden Löwen der Manyara-Region zu sehen',
        ],
        'es' => [
            'Safari de día completo en el Serengeti — la sabana más icónica de África',
            'Descenso al Cráter del Ngorongoro para avistamiento de los Cinco Grandes en un anfiteatro volcánico',
            'Observación de las legendarias manadas de elefantes de Tarangire entre baobabs gigantes',
            'Safaris matutinos y vespertinos en la hora dorada para encuentros óptimos',
            'Recorrido panorámico por el Gran Valle del Rift con miradores',
            'Alojamientos premium cómodos con vistas a la sabana y sundowners vespertinos',
            'Guía naturalista tanzano experto durante todo el viaje',
            'Oportunidad de avistar los leones trepadores de la región del lago Manyara',
        ],
    ],
]);

echo "✓ Safari #3 content updated\n";

// Delete old itineraries and recreate
Itinerary::where('safari_package_id', 3)->delete();
$itineraries3 = [
    [
        'day_number'     => 1,
        'title'          => 'Arrival in Arusha — Welcome to Tanzania',
        'description'    => "Your Tanzanian adventure begins the moment you land at Kilimanjaro International Airport (JRO). Our professional driver-guide will be waiting at arrivals with a welcome sign, ready to transfer you to your lodge in the lush foothills of Mount Meru.\n\nSettle into your comfortable room, freshen up, and enjoy a welcome briefing over a cold drink on the terrace. Your guide will introduce the safari itinerary, share practical tips for game drives, and answer any questions. Spend the rest of the afternoon relaxing by the pool or exploring the lodge gardens with views of Mount Meru. A delicious three-course dinner rounds off your first evening in East Africa.\n\nOvernight: Arusha Coffee Lodge or similar (Bed & Breakfast)",
        'destination_id' => 4, // Arusha Town
    ],
    [
        'day_number'     => 2,
        'title'          => 'Tarangire National Park — Land of the Giants',
        'description'    => "After an early breakfast, depart Arusha and drive south-west through the Great Rift Valley escarpment to Tarangire National Park — a two-hour scenic journey through Maasai heartland dotted with traditional bomas.\n\nEnter the park and immediately be immersed in one of Tanzania's most underrated wildlife spectacles. Tarangire is famous for its enormous elephant herds — often 300 strong — gathering around the Tarangire River, and its ancient baobab trees that can be over 1,000 years old. During your full-day game drive, look for lions resting in the shade, leopards draped over branches, giraffes browsing the acacia woodland, and over 550 bird species including the striking yellow-collared lovebird, endemic to northern Tanzania.\n\nYour guide will position the vehicle at prime viewing spots along the river, where elephants, buffalo, and zebra come to drink in the afternoon heat. Enjoy a picnic lunch under a giant baobab before continuing your exploration. As the sun drops low, watch the savanna turn golden before heading to your lodge.\n\nOvernight: Tarangire Simba Lodge or similar (Full Board)",
        'destination_id' => 5, // Tarangire
    ],
    [
        'day_number'     => 3,
        'title'          => 'Serengeti National Park — The Endless Plains',
        'description'    => "Rise with the sun for an early breakfast, then set off on the drive of a lifetime through the Ngorongoro Conservation Area and down into the Serengeti — Tanzania's most famous national park. The journey itself is extraordinary: you'll cross the fertile Karatu Highlands, pass Maasai villages, and stop at the Ngorongoro Crater rim viewpoint for your first breathtaking glimpse of the caldera before descending into the Serengeti ecosystem.\n\nAs you enter the Serengeti's Seronera Valley — the park's wildlife hub — the landscape opens into seemingly infinite grasslands. This central zone is renowned for year-round predator action: resident lion prides, cheetahs scanning the plains from termite mounds, and hyena clans near their dens. Your guide will read the landscape for signs of activity — circling vultures, alert Thomson's gazelles, and the distant dust clouds of wildebeest herds.\n\nConduct game drives en route to your camp, arriving in time for sundowners as the Serengeti sunset paints the sky in shades of amber and violet.\n\nOvernight: Serengeti Acacia Camp or similar (Full Board)",
        'destination_id' => 1, // Serengeti
    ],
    [
        'day_number'     => 4,
        'title'          => 'Full Day in the Serengeti — Predators and Plains',
        'description'    => "Today is dedicated entirely to exploring the Serengeti — with both morning and afternoon game drives designed to maximise your wildlife encounters during the peak activity hours.\n\nDepart at dawn for a sunrise game drive when predators are most active. The early morning light is magical — watch lion prides returning from overnight hunts, spotted hyenas nursing cubs at their den, and cheetahs stretching in the first warm rays of sun. Your guide will navigate the Seronera River crossings, kopje rock formations, and open grasslands where the density of predators is among the highest in Africa.\n\nReturn to camp for a leisurely brunch, then rest during the midday heat — perhaps watching the resident vervet monkeys or the dazzling lilac-breasted rollers from your tent verandah.\n\nIn the afternoon, head out again for a second game drive focusing on different terrain — perhaps the western corridor if the migration herds are nearby, or the Moru Kopjes where ancient Maasai rock art adorns the granite boulders alongside resident leopards. Keep your camera ready for dramatic predator-prey interactions as the day cools and herd animals begin to move.\n\nOvernight: Serengeti Acacia Camp or similar (Full Board)",
        'destination_id' => 1, // Serengeti
    ],
    [
        'day_number'     => 5,
        'title'          => 'Ngorongoro Crater — The Eighth Wonder of the World',
        'description'    => "After a final early morning game drive in the Serengeti — where every sighting feels like a gift — bid farewell to the endless plains and drive east to the Ngorongoro Conservation Area. The route climbs steadily through the Serengeti's acacia-studded eastern boundary and up onto the forested crater rim.\n\nCheck in at your lodge perched on the crater's edge, where the views are simply staggering: 600 metres below lies a 260-square-kilometre natural arena containing approximately 30,000 large mammals, including one of Africa's densest populations of black rhino.\n\nSpend the late afternoon soaking in the panoramic views from the rim. Watch the clouds drift across the crater floor and enjoy a spectacular sunset dinner at the lodge restaurant, where the caldera stretches below you in every shade of green and gold.\n\nOvernight: Ngorongoro Serena Safari Lodge or similar (Full Board)",
        'destination_id' => 2, // Ngorongoro
    ],
    [
        'day_number'     => 6,
        'title'          => 'Ngorongoro Crater Game Drive — Departure',
        'description'    => "Rise before dawn for the experience many travellers call the highlight of their entire African safari — a half-day game drive on the Ngorongoro Crater floor. Descend the steep crater wall as morning mist lifts from the forest canopy, revealing the vast grasslands, soda lakes, and marshlands below.\n\nThe crater is home to all of the Big Five: lion, leopard, elephant, buffalo, and the critically endangered black rhinoceros. Your guide will navigate the crater's distinct habitats — from Lake Magadi's flamingo-fringed shores to the Lerai Forest where elephant bulls feed in the shade of yellow-barked acacias. Hippo pools, hyena dens, and jackal packs provide constant activity.\n\nAfter a packed picnic lunch at one of the designated picnic sites on the crater floor, ascend the rim and begin the drive back to Arusha. Your guide will stop at strategic viewpoints for final photographs. Arrive in Arusha by late afternoon for a drop-off at your hotel or Kilimanjaro International Airport, carrying memories that will last a lifetime.\n\nEnd of safari — karibu tena (welcome back anytime)!",
        'destination_id' => 2, // Ngorongoro
    ],
];

foreach ($itineraries3 as $it) {
    Itinerary::create(array_merge($it, ['safari_package_id' => 3]));
}
echo "✓ Safari #3 itineraries created (" . count($itineraries3) . " days)\n";

// Sync destinations for Safari #3
$safari3->destinations()->sync([4, 5, 1, 2]); // Arusha, Tarangire, Serengeti, Ngorongoro
echo "✓ Safari #3 destinations synced\n";


// ═══════════════════════════════════════════════════════
// SAFARI #2 — 8-Day Tanzania Luxury Safari Experience
// ═══════════════════════════════════════════════════════

$safari2 = SafariPackage::findOrFail(2);
$safari2->update([
    'title'    => '8-Day Tanzania Luxury Safari Experience',
    'slug'     => '8-day-tanzania-luxury-safari-experience',
    'duration' => '8 Days / 7 Nights',
    'difficulty' => 'Easy',
    'price'    => 5950.00,
    'currency' => 'USD',
    'status'   => 'published',
    'safari_type'  => 'safari',
    'tour_type_id' => 3,  // Luxury Safari
    'category_id'  => 1,  // Luxury
    'featured'     => true,
    'is_popular'   => true,

    'short_description' => 'Immerse yourself in the ultimate 8-day luxury safari through Tanzania\'s finest national parks and the tropical shores of Zanzibar. Stay in five-star lodges and boutique tented camps, enjoy private game drives across the Serengeti and Ngorongoro Crater, then unwind on Zanzibar\'s white-sand beaches. Every moment is curated for elegance, exclusivity, and unforgettable wildlife encounters.',

    'description' => "This 8-day luxury safari is the definitive Tanzanian experience — a seamless blend of world-class wildlife viewing and five-star comfort that takes you from the bushlands of the Northern Circuit to the turquoise waters of the Indian Ocean. Every night is spent in hand-selected properties renowned for exceptional service, extraordinary locations, and refined African elegance.\n\nYour journey begins in Arusha and sweeps through Tarangire's baobab-studded elephant country, the Serengeti's predator-rich central plains, and the awe-inspiring Ngorongoro Crater — where nearly every game drive delivers Big Five sightings. Throughout, you'll travel in a private 4×4 with your dedicated guide, dine on gourmet bush cuisine, and return each evening to lodges with infinity pools, spa treatments, and sunset cocktails overlooking the African wilderness.\n\nThe safari culminates with a charter flight to Zanzibar, where two nights at a luxury beachfront resort offer the perfect counterpoint to your bush adventure. Snorkel on coral reefs, explore Stone Town's UNESCO heritage, or simply rest on powder-white sand — the ideal finale to an extraordinary East African escape.\n\nThis is not just a safari — it is an immersion in the beauty, wildlife, and cultures of Tanzania at the highest level of comfort and care.",

    'overview_title' => 'Luxury Safari Overview',

    'highlights_title' => 'Safari Highlights',
    'highlights_intro' => 'Eight days of curated luxury moments — from sunrise game drives to beachfront sundowners.',
    'highlights' => [
        'Two full days of game drives in the Serengeti — Africa\'s premier wildlife destination',
        'Ngorongoro Crater descent with Big Five viewing in a volcanic amphitheatre',
        'Tarangire National Park — elephant herds, baobabs, and exceptional birding',
        'Five-star lodges and boutique tented camps with private verandahs',
        'Charter flight to Zanzibar for two nights of tropical beach luxury',
        'Private 4×4 safari vehicle with personal guide throughout',
        'Gourmet bush dining — candlelit dinners, picnic lunches, and sundowner cocktails',
        'Optional hot-air balloon safari over the Serengeti at sunrise (additional cost)',
        'Stone Town heritage walk and spice tour in Zanzibar',
        'Seamless door-to-door transfers and domestic flights included',
    ],

    'inclusions_title' => 'What\'s Included & Excluded',
    'inclusions_intro' => 'Your luxury safari package is fully inclusive — here\'s exactly what\'s covered and what to budget separately.',
    'included' => [
        'Meet-and-greet at Kilimanjaro International Airport with VIP transfer',
        'All national park entrance fees, conservation levies, and crater fees',
        'Dedicated English-speaking naturalist guide and driver for the entire safari',
        'Private 4×4 luxury safari vehicle with pop-up roof, Wi-Fi, and USB charging',
        '7 nights\' accommodation in five-star lodges and luxury tented camps',
        'All meals throughout the safari (full board — breakfast, lunch, dinner)',
        'Premium house wines, beers, and soft drinks with meals at select lodges',
        'Domestic charter flight: Serengeti to Zanzibar',
        'Return airport transfer from Zanzibar hotel',
        'Unlimited bottled water and refreshments during game drives',
        'Complimentary binoculars and wildlife field guides in the vehicle',
        'Laundry service at all lodges (excluding Zanzibar)',
        '24/7 on-call safari operations support',
    ],
    'excluded' => [
        'International flights to/from Tanzania',
        'Tanzania tourist visa (US$50 — available on arrival or online)',
        'Comprehensive travel and medical insurance (required)',
        'Tips and gratuities for guide, lodge staff, and porters',
        'Optional hot-air balloon safari over the Serengeti (US$599 per person)',
        'Premium imported spirits, champagne, and cellar wines',
        'Spa treatments and wellness services at lodges',
        'Personal expenses (souvenirs, additional excursions, telephone calls)',
        'Zanzibar activities not specified (diving, deep-sea fishing, etc.)',
        'Anything not explicitly listed under inclusions',
    ],

    'seasonal_pricing' => [
        'low_season'  => ['period' => 'April – May', 'price_per_person' => 4950, 'notes' => 'Green season — fewer guests, lush scenery, reduced rates at premium lodges'],
        'mid_season'  => ['period' => 'June – October', 'price_per_person' => 5950, 'notes' => 'Peak dry season — best wildlife viewing, Great Migration in the Serengeti'],
        'high_season' => ['period' => 'November – March', 'price_per_person' => 6950, 'notes' => 'Calving season and festive period — premium rates, book early for availability'],
    ],

    'meta_title'       => '8-Day Luxury Safari Tanzania | Serengeti, Ngorongoro & Zanzibar | Lomo Safari',
    'meta_description' => 'Book an 8-day luxury safari in Tanzania. Five-star lodges, private game drives in the Serengeti & Ngorongoro, plus Zanzibar beach extension. From $5,950 pp.',
    'meta_keywords'    => '8 day luxury safari tanzania, serengeti luxury safari, ngorongoro luxury lodge, zanzibar beach safari, five star safari tanzania, bush and beach package, premium tanzania safari',

    // ── Translations ──
    'title_translations' => [
        'fr' => 'Safari de Luxe 8 Jours en Tanzanie',
        'de' => '8-Tage Luxus-Safari Erlebnis Tansania',
        'es' => 'Experiencia Safari de Lujo de 8 Días en Tanzania',
    ],
    'short_description_translations' => [
        'fr' => 'Plongez dans l\'ultime safari de luxe de 8 jours à travers les plus beaux parcs nationaux de Tanzanie et les rivages tropicaux de Zanzibar. Séjournez dans des lodges cinq étoiles et des camps sous tente boutique, profitez de safaris privés dans le Serengeti et le cratère du Ngorongoro, puis détendez-vous sur les plages de sable blanc de Zanzibar. Chaque moment est pensé pour l\'élégance, l\'exclusivité et des rencontres inoubliables.',
        'de' => 'Tauchen Sie ein in die ultimative 8-Tage-Luxus-Safari durch Tansanias schönste Nationalparks und die tropischen Küsten Sansibars. Übernachten Sie in Fünf-Sterne-Lodges und Boutique-Zeltcamps, genießen Sie private Pirschfahrten in der Serengeti und im Ngorongoro-Krater und entspannen Sie an Sansibars weißen Sandstränden. Jeder Moment ist auf Eleganz, Exklusivität und unvergessliche Wildtierbegegnungen ausgerichtet.',
        'es' => 'Sumérgete en el safari de lujo definitivo de 8 días por los mejores parques nacionales de Tanzania y las costas tropicales de Zanzíbar. Alójate en lodges cinco estrellas y campamentos boutique, disfruta de safaris privados en el Serengeti y el Cráter del Ngorongoro, y relájate en las playas de arena blanca de Zanzíbar. Cada momento está diseñado para la elegancia, la exclusividad y encuentros inolvidables.',
    ],
    'description_translations' => [
        'fr' => "Ce safari de luxe de 8 jours est l'expérience tanzanienne par excellence — un mélange harmonieux d'observation de la faune de classe mondiale et de confort cinq étoiles, du bushland du Circuit Nord aux eaux turquoise de l'océan Indien. Chaque nuit se passe dans des établissements triés sur le volet, réputés pour leur service exceptionnel et leur élégance africaine raffinée.\n\nVotre voyage commence à Arusha et traverse le pays des éléphants et des baobabs de Tarangire, les plaines centrales riches en prédateurs du Serengeti, et l'impressionnant cratère du Ngorongoro. Vous voyagerez dans un 4×4 privé avec votre guide dédié, dînerez d'une cuisine gastronomique de brousse et regagnerez chaque soir des lodges avec piscines à débordement et cocktails au coucher du soleil.\n\nLe safari culmine avec un vol charter vers Zanzibar, où deux nuits dans un resort de luxe en bord de mer offrent le contrepoint parfait. Snorkelling sur les récifs, exploration de Stone Town ou farniente sur le sable blanc — la finale idéale d'une escapade est-africaine extraordinaire.",
        'de' => "Diese 8-Tage-Luxus-Safari ist das ultimative Tansania-Erlebnis — eine nahtlose Verbindung von erstklassiger Wildtierbeobachtung und Fünf-Sterne-Komfort, vom Buschland des Northern Circuit bis zum türkisfarbenen Wasser des Indischen Ozeans. Jede Nacht verbringen Sie in handverlesenen Unterkünften, die für außergewöhnlichen Service und raffinierte afrikanische Eleganz bekannt sind.\n\nIhre Reise beginnt in Arusha und führt durch Tarangires Elefanten-Land mit seinen Baobabs, die raubtierreichen zentralen Serengeti-Ebenen und den atemberaubenden Ngorongoro-Krater. Sie reisen in einem privaten 4×4 mit Ihrem persönlichen Guide, speisen Gourmet-Buschküche und kehren jeden Abend zu Lodges mit Infinity-Pools und Sundowner-Cocktails zurück.\n\nDie Safari gipfelt in einem Charterflug nach Sansibar, wo zwei Nächte in einem Luxus-Strandresort den perfekten Gegenpol bieten. Schnorcheln an Korallenriffen, Stone Town erkunden oder einfach am weißen Sand entspannen — das ideale Finale eines außergewöhnlichen ostafrikanischen Erlebnisses.",
        'es' => "Este safari de lujo de 8 días es la experiencia tanzana definitiva — una fusión perfecta de observación de fauna de primer nivel y comodidad cinco estrellas, desde los arbustos del Circuito Norte hasta las aguas turquesas del Océano Índico. Cada noche la pasas en propiedades selectas, reconocidas por su servicio excepcional y elegancia africana refinada.\n\nTu viaje comienza en Arusha y recorre el territorio de elefantes y baobabs de Tarangire, las llanuras centrales del Serengeti ricas en depredadores, y el impresionante Cráter del Ngorongoro. Viajarás en un 4×4 privado con tu guía dedicado, cenarás cocina gourmet de sabana y regresarás cada noche a lodges con piscinas infinitas y cócteles al atardecer.\n\nEl safari culmina con un vuelo chárter a Zanzíbar, donde dos noches en un resort de lujo frente al mar ofrecen el contrapunto perfecto. Snorkel en arrecifes, exploración de Stone Town o descanso en arena blanca — el final ideal de una escapada extraordinaria por África Oriental.",
    ],
    'overview_title_translations' => [
        'fr' => 'Aperçu du Safari de Luxe',
        'de' => 'Luxus-Safari Übersicht',
        'es' => 'Resumen del Safari de Lujo',
    ],
    'highlights_title_translations' => [
        'fr' => 'Points Forts du Safari',
        'de' => 'Safari-Highlights',
        'es' => 'Momentos Destacados',
    ],
    'highlights_intro_translations' => [
        'fr' => 'Huit jours de moments de luxe soigneusement sélectionnés — des safaris au lever du soleil aux sundowners face à l\'océan.',
        'de' => 'Acht Tage kuratierter Luxusmomente — von Sonnenaufgangs-Pirschfahrten bis zu Sundowners am Strand.',
        'es' => 'Ocho días de momentos de lujo seleccionados — desde safaris al amanecer hasta sundowners frente al mar.',
    ],
    'inclusions_title_translations' => [
        'fr' => 'Inclus et Non Inclus',
        'de' => 'Eingeschlossen & Ausgeschlossen',
        'es' => 'Incluido y No Incluido',
    ],
    'inclusions_intro_translations' => [
        'fr' => 'Votre forfait safari de luxe est tout inclus — voici exactement ce qui est couvert et ce qu\'il faut budgéter séparément.',
        'de' => 'Ihr Luxus-Safari-Paket ist all-inclusive — hier sehen Sie genau, was abgedeckt ist und was separat zu budgetieren ist.',
        'es' => 'Tu paquete de safari de lujo es todo incluido — esto es exactamente lo que está cubierto y lo que debes presupuestar por separado.',
    ],
    'highlights_translations' => [
        'fr' => [
            'Deux journées complètes de safari dans le Serengeti — première destination faune d\'Afrique',
            'Descente dans le cratère du Ngorongoro avec observation des Big Five',
            'Parc national de Tarangire — troupeaux d\'éléphants, baobabs et observation d\'oiseaux exceptionnelle',
            'Lodges cinq étoiles et camps sous tente boutique avec vérandas privées',
            'Vol charter vers Zanzibar pour deux nuits de luxe tropical en bord de mer',
            'Véhicule safari 4×4 privé avec guide personnel tout au long du séjour',
            'Gastronomie de brousse — dîners aux chandelles, déjeuners pique-nique et cocktails au coucher du soleil',
            'Safari optionnel en montgolfière au-dessus du Serengeti au lever du soleil (supplément)',
            'Visite du patrimoine de Stone Town et circuit des épices à Zanzibar',
            'Transferts porte-à-porte et vols domestiques inclus',
        ],
        'de' => [
            'Zwei volle Tage Pirschfahrten in der Serengeti — Afrikas Wildtier-Destination Nr. 1',
            'Abstieg in den Ngorongoro-Krater mit Big-Five-Beobachtung',
            'Tarangire Nationalpark — Elefantenherden, Baobabs und hervorragende Vogelbeobachtung',
            'Fünf-Sterne-Lodges und Boutique-Zeltcamps mit privaten Veranden',
            'Charterflug nach Sansibar für zwei Nächte tropischen Strandluxus',
            'Privates 4×4-Safari-Fahrzeug mit persönlichem Guide durchgehend',
            'Gourmet-Buschküche — Candlelight-Dinner, Picknick-Lunch und Sundowner-Cocktails',
            'Optionale Heißluftballon-Safari über der Serengeti bei Sonnenaufgang (Aufpreis)',
            'Stone Town Kulturspaziergang und Gewürztour auf Sansibar',
            'Nahtlose Tür-zu-Tür-Transfers und Inlandsflüge inklusive',
        ],
        'es' => [
            'Dos días completos de safari en el Serengeti — el destino de fauna número uno de África',
            'Descenso al Cráter del Ngorongoro con avistamiento de los Cinco Grandes',
            'Parque Nacional Tarangire — manadas de elefantes, baobabs y observación de aves excepcional',
            'Alojamientos cinco estrellas y campamentos boutique con terrazas privadas',
            'Vuelo chárter a Zanzíbar para dos noches de lujo tropical frente al mar',
            'Vehículo safari 4×4 privado con guía personal durante toda la estancia',
            'Gastronomía de sabana — cenas con velas, almuerzos tipo pícnic y cócteles al atardecer',
            'Safari opcional en globo aerostático sobre el Serengeti al amanecer (costo adicional)',
            'Paseo por el patrimonio de Stone Town y tour de especias en Zanzíbar',
            'Traslados puerta a puerta y vuelos domésticos incluidos',
        ],
    ],
]);

echo "✓ Safari #2 content updated\n";

// Delete old itineraries and recreate
Itinerary::where('safari_package_id', 2)->delete();
$itineraries2 = [
    [
        'day_number'     => 1,
        'title'          => 'Arrival in Arusha — VIP Welcome',
        'description'    => "Welcome to Tanzania. Upon arrival at Kilimanjaro International Airport, our VIP liaison will greet you at the aircraft door and fast-track you through immigration and customs. A luxury 4×4 transfer whisks you to your five-star lodge nestled in a coffee plantation on the slopes of Mount Meru.\n\nSettle into your elegant suite, enjoy a welcome cocktail on the terrace overlooking the manicured gardens, and join your personal safari guide for a pre-trip briefing and equipment check. If your flight arrives early, opt for a guided walk through the coffee estate or a relaxing spa treatment to shake off jet lag.\n\nA gourmet welcome dinner — featuring locally sourced Tanzanian ingredients paired with South African wines — sets the tone for the extraordinary week ahead.\n\nOvernight: Arusha Coffee Lodge or Elewana Arusha Hotel (Luxury, Full Board)",
        'destination_id' => 4, // Arusha Town
    ],
    [
        'day_number'     => 2,
        'title'          => 'Tarangire National Park — Elephants and Baobabs',
        'description'    => "Depart after a leisurely breakfast for the scenic two-hour drive to Tarangire National Park, passing through the Great Rift Valley with sweeping views across Maasai steppe land.\n\nTarangire is Tanzania's hidden gem — a park of ancient baobab trees, seasonal swamps, and the highest elephant density of any park in the country. Your luxury safari vehicle enters the park from the north, and within minutes you'll be surrounded by herds of elephants, their red-dust-coated silhouettes framed against towering 800-year-old baobabs.\n\nConduct a full-day game drive with a gourmet picnic lunch served at a shaded riverside spot. In the afternoon, follow the Tarangire River where elephants, buffalo, giraffes, and wildebeest congregate. Tarangire's birdlife is exceptional — look for the endemic ashy starling, yellow-collared lovebird, and rufous-tailed weaver.\n\nAs the sun sets, drive to your exclusive tented camp perched in the canopy of ancient trees, where a candlelit dinner awaits under the stars.\n\nOvernight: Tarangire Treetops or Chem Chem Lodge (Luxury, Full Board)",
        'destination_id' => 5, // Tarangire
    ],
    [
        'day_number'     => 3,
        'title'          => 'Ngorongoro Conservation Area — Crater Rim Retreat',
        'description'    => "After a sunrise game drive in Tarangire — when the park's predators are most active and the light is magical — bid farewell and drive west through the charming Karatu Highlands. This fertile region, known as the breadbasket of northern Tanzania, is blanketed in coffee farms, flower plantations, and lush montane forest.\n\nContinue climbing through dense cloud forest to the rim of the Ngorongoro Crater, where you'll check in at one of Africa's most spectacular lodge locations. Your suite perches on the crater's edge at 2,300 metres, offering uninterrupted views across the 260-square-kilometre caldera floor.\n\nSpend the afternoon at leisure — enjoy the lodge's infinity pool with crater views, indulge in a hot stone spa treatment, or take a guided nature walk along the forested crater rim with a resident naturalist. In the evening, sip sundowner cocktails as the crater transforms with the setting sun, then dine in the lodge's panoramic restaurant.\n\nOvernight: Ngorongoro Crater Lodge or Ngorongoro Serena Lodge (Luxury, Full Board)",
        'destination_id' => 2, // Ngorongoro
    ],
    [
        'day_number'     => 4,
        'title'          => 'Ngorongoro Crater Floor — Big Five Safari',
        'description'    => "Rise before dawn and descend 600 metres into the Ngorongoro Crater — often called the Eighth Wonder of the World. This collapsed volcanic caldera contains the highest density of wildlife in Africa, and it is one of the few places where you can realistically see all of the Big Five in a single game drive.\n\nYour luxury vehicle navigates the lush crater floor, moving between distinct habitats: open grasslands where lion prides hunt zebra and wildebeest; the shores of Lake Magadi, fringed with thousands of lesser flamingos; freshwater marshes where bull elephants and hippos wallow; and the Lerai Forest, where black rhinos browse under fever tree canopies. Your guide will take time at each sighting, positioning the vehicle for photography and explaining the remarkable ecology of this enclosed ecosystem.\n\nA gourmet picnic lunch is served at a hippo pool picnic site with views across the crater. After a full morning of exploration, ascend the rim and return to your lodge for an afternoon of relaxation, reflection, and preparation for the next chapter — the Serengeti.\n\nOvernight: Ngorongoro Crater Lodge or Ngorongoro Serena Lodge (Luxury, Full Board)",
        'destination_id' => 2, // Ngorongoro
    ],
    [
        'day_number'     => 5,
        'title'          => 'Serengeti National Park — Into the Endless Plains',
        'description'    => "After breakfast, journey west from the Ngorongoro Highlands and descend through the dramatic Olduvai Gorge — the Cradle of Mankind, where some of humanity's oldest fossils were discovered. Stop at the museum for a brief orientation before continuing into the Serengeti.\n\nAs the landscape opens into the Serengeti's iconic golden grasslands, your guide will read the plains for wildlife activity. The central Seronera area is famous for its resident big cat populations — lion prides lounging on kopje rock formations, cheetahs scanning the horizon from termite mounds, and leopards draped over sausage tree branches.\n\nConduct game drives en route to your luxury tented camp, positioned in a private concession bordering the national park. Your camp offers the intimacy and exclusivity that distinguishes a luxury safari — just a handful of tents, each with a private deck, outdoor shower, and uninterrupted views across the Serengeti. A private chef prepares a multi-course dinner as the sounds of the bush fill the night air.\n\nOvernight: Four Seasons Safari Lodge or Singita Sabora Tented Camp (Luxury, Full Board)",
        'destination_id' => 1, // Serengeti
    ],
    [
        'day_number'     => 6,
        'title'          => 'Full Day Serengeti — Dawn to Dusk Safari',
        'description'    => "Today is a full immersion in the Serengeti — widely regarded as the greatest wildlife arena on Earth. Your private guide tailors the day to your interests: big cat tracking, bird photography, migration herds, or a balanced mix of everything.\n\nDepart at first light for a dawn game drive, when the savanna comes alive. Watch lion prides returning from overnight hunts, hyena clans at their den sites, and herds of wildebeest and zebra beginning their daily grazing. The morning light — soft gold and shadow-rich — creates extraordinary photography conditions.\n\nReturn to camp for a gourmet brunch, then relax through the midday heat with a good book, a swim in the plunge pool, or an optional spa treatment. In the afternoon, venture out again for a second game drive, perhaps focusing on the Grumeti River area or the Moru Kopjes, where ancient Maasai rock paintings share the landscape with resident leopards.\n\nCap the day with a bush sundowner — champagne and canapés served at a scenic viewpoint as the Serengeti sunset blazes across the horizon.\n\nOvernight: Four Seasons Safari Lodge or Singita Sabora Tented Camp (Luxury, Full Board)",
        'destination_id' => 1, // Serengeti
    ],
    [
        'day_number'     => 7,
        'title'          => 'Charter Flight to Zanzibar — Tropical Paradise',
        'description'    => "Enjoy a final early-morning game drive in the Serengeti — each farewell drive seems to produce its own magical encounter — before transferring to the Seronera Airstrip for your charter flight to Zanzibar. Watch the Serengeti shrink beneath you as the aircraft crosses the Tanzanian landscape, trading golden savanna for the glittering Indian Ocean in under two hours.\n\nUpon landing at Zanzibar's Abeid Amani Karume International Airport, a private transfer whisks you to your luxury beachfront resort on the island's pristine east coast. Check in, settle into your ocean-view suite or private villa, and step onto powder-white sand lapped by warm turquoise water.\n\nSpend the afternoon at your own pace — snorkelling on the house reef, floating in an overwater hammock, or simply doing absolutely nothing. In the evening, dine barefoot on the beach with fresh seafood, tropical cocktails, and the sound of gentle waves.\n\nOvernight: Zuri Zanzibar or The Residence Zanzibar (Luxury Beach Resort, Full Board)",
        'destination_id' => 20, // Zanzibar Island
    ],
    [
        'day_number'     => 8,
        'title'          => 'Zanzibar Exploration — Stone Town & Departure',
        'description'    => "Your final day offers a choice of experiences to close your Tanzania journey in style. Option one: sleep in, enjoy a leisurely breakfast by the ocean, and soak up the last hours of beach bliss before your afternoon transfer.\n\nOption two: join a guided morning excursion to Stone Town — Zanzibar's UNESCO World Heritage old quarter. Wander through narrow coral-stone alleys, visit the Sultan's Palace, the House of Wonders, and the historic slave market memorial. Browse the lively Darajani bazaar for spices, hand-carved Zanzibar chests, and colourful kangas. Stop at a rooftop café for spiced Zanzibari coffee and panoramic views over the harbour.\n\nAlternatively, visit a spice farm in the island's lush interior — touch, smell, and taste vanilla, cloves, cinnamon, cardamom, and nutmeg growing in their natural environment.\n\nAfter lunch at the resort, a private vehicle transfers you to Zanzibar airport for your onward flight. Depart with memories of roaring lions, steaming craters, elephant parades, and ocean sunsets — the very best of Tanzania.\n\nEnd of safari — asante sana na karibu tena (thank you and welcome back)!",
        'destination_id' => 20, // Zanzibar Island
    ],
];

foreach ($itineraries2 as $it) {
    Itinerary::create(array_merge($it, ['safari_package_id' => 2]));
}
echo "✓ Safari #2 itineraries created (" . count($itineraries2) . " days)\n";

// Sync destinations for Safari #2
$safari2->destinations()->sync([4, 5, 2, 1, 20]); // Arusha, Tarangire, Ngorongoro, Serengeti, Zanzibar
echo "✓ Safari #2 destinations synced\n";

echo "\n══════════════════════════════════\n";
echo "Done! Both safaris fully updated.\n";
echo "══════════════════════════════════\n";
