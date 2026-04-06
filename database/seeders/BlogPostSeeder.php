<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create categories ──
        $categories = [
            'Travel Guides'    => ['en' => 'Travel Guides', 'fr' => 'Guides de Voyage', 'de' => 'Reiseführer', 'es' => 'Guías de Viaje'],
            'Safari Tips'      => ['en' => 'Safari Tips', 'fr' => 'Conseils Safari', 'de' => 'Safari-Tipps', 'es' => 'Consejos de Safari'],
            'Wildlife'         => ['en' => 'Wildlife', 'fr' => 'Faune Sauvage', 'de' => 'Tierwelt', 'es' => 'Vida Silvestre'],
            'Culture & People' => ['en' => 'Culture & People', 'fr' => 'Culture & Peuple', 'de' => 'Kultur & Menschen', 'es' => 'Cultura y Gente'],
        ];

        $catIds = [];
        foreach ($categories as $slug => $names) {
            $cat = BlogCategory::firstOrCreate(
                ['slug' => Str::slug($slug)],
                ['name' => $names, 'sort_order' => count($catIds)]
            );
            $catIds[$slug] = $cat->id;
        }

        $authorId = \App\Models\User::where('role', 'super_admin')->first()?->id ?? 1;

        // ── 5 SEO Blog Posts ──
        $posts = [
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // POST 1: Great Migration
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            [
                'slug' => 'great-wildebeest-migration-complete-guide',
                'category' => 'Travel Guides',
                'meta_title' => 'The Great Wildebeest Migration: Complete 2026 Guide | Lomo Tanzania Safari',
                'meta_description' => 'Everything you need to know about the Great Wildebeest Migration in Tanzania. Best months, where to stay, river crossings, and expert tips for 2026.',
                'meta_keywords' => 'great migration, wildebeest migration, serengeti migration, tanzania safari, river crossing, mara river, best time serengeti',
                'title' => [
                    'en' => 'The Great Wildebeest Migration: A Complete Guide for 2026',
                    'fr' => 'La Grande Migration des Gnous : Guide Complet pour 2026',
                    'de' => 'Die Große Gnu-Wanderung: Ein Vollständiger Leitfaden für 2026',
                    'es' => 'La Gran Migración del Ñu: Guía Completa para 2026',
                ],
                'excerpt' => [
                    'en' => 'Over two million wildebeest, zebra, and gazelle travel a relentless 1,800-kilometre loop through the Serengeti-Mara ecosystem every year. Here is everything you need to know to witness nature\'s greatest wildlife spectacle in 2026.',
                    'fr' => 'Plus de deux millions de gnous, zèbres et gazelles parcourent une boucle de 1 800 kilomètres à travers l\'écosystème Serengeti-Mara chaque année. Voici tout ce que vous devez savoir pour assister au plus grand spectacle animalier de 2026.',
                    'de' => 'Über zwei Millionen Gnus, Zebras und Gazellen wandern jedes Jahr eine 1.800 Kilometer lange Schleife durch das Serengeti-Mara-Ökosystem. Hier finden Sie alles, was Sie wissen müssen, um das größte Naturschauspiel 2026 zu erleben.',
                    'es' => 'Más de dos millones de ñus, cebras y gacelas recorren un circuito de 1.800 kilómetros por el ecosistema Serengeti-Mara cada año. Aquí encontrarás todo lo que necesitas saber para presenciar el mayor espectáculo de vida silvestre en 2026.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>What Is the Great Migration?</h2>
<p>The Great Wildebeest Migration is widely regarded as one of the most awe-inspiring natural events on Earth. Every year, approximately 1.5 million wildebeest — accompanied by around 400,000 zebra, 200,000 Thomson's gazelle, and thousands of eland — travel a circular route through the Serengeti National Park in Tanzania and the Masai Mara National Reserve in Kenya. This continuous movement is driven by the search for fresh grazing and water, and it has no real beginning or end.</p>

<h2>Month-by-Month Migration Calendar</h2>
<h3>January – March: Calving Season in the Southern Serengeti</h3>
<p>The herds gather on the short-grass plains of the southern Serengeti and the Ndutu area, near the Ngorongoro Conservation Area. This is calving season: an estimated 8,000 wildebeest calves are born every day over a two-to-three-week peak in February. The abundance of newborns attracts predators — lion, cheetah, hyena, and wild dog — making this one of the best times for predator-prey action photography and game viewing.</p>

<h3>April – May: The Long Rains and the Move West</h3>
<p>As the long rains arrive, the southern plains become waterlogged and the grass grows tall. The herds begin moving northwest through the central Serengeti's Seronera Valley. Game drives during this period are quieter with fewer tourists, yet the landscape is lush and green, and birdlife is extraordinary. Many lodges offer green-season discounts, making this an excellent value period for budget-conscious travellers.</p>

<h3>June – July: The Western Corridor and Grumeti River Crossings</h3>
<p>By June, the massive columns of wildebeest stretch across the Western Corridor toward the Grumeti River. The Grumeti crossings are intense but less crowded than the famous Mara River crossings later in the year. Enormous Nile crocodiles, some over five metres long, lie in wait. The drama of thousands of wildebeest plunging into the water while predators attack from all sides is unforgettable.</p>

<h3>August – October: The Iconic Mara River Crossings</h3>
<p>This is the peak season and the most sought-after period. The herds arrive at the Mara River in the northern Serengeti, and the crossings here are the defining image of the migration. Wildebeest pile into the river in their thousands, scrambling over rocks and facing hungry crocodiles. Not every crossing happens every day — patience is required — but when it happens the spectacle is unrivalled. Accommodation in the northern Serengeti books out 6 to 12 months in advance, so early planning is essential.</p>

<h3>November – December: Return South</h3>
<p>After the short rains begin in November, the herds turn south again, crossing back through the eastern Serengeti toward the calving grounds. The plains are refreshed and green, the light is spectacular for photography, and crowds thin dramatically. It is a superb time for those who prefer exclusivity and dramatic skies.</p>

<h2>Where to Stay During the Migration</h2>
<p>Choosing the right camp or lodge depends entirely on the time of year. Mobile tented camps that follow the herds offer the most immersive experience — you wake up surrounded by the migration itself. Fixed lodges like Sayari Camp in the north, Serengeti Migration Camp in the Western Corridor, and Ndutu Safari Lodge in the south each serve specific migration windows. Hot-air balloon safaris over the herds depart from several camps and offer an extraordinary aerial perspective.</p>

<h2>How to Photograph the Migration</h2>
<p>Bring a telephoto lens of at least 200–400mm for river crossings and predator-prey moments. A wide-angle lens captures the scale of the herds stretching to the horizon. Shoot in burst mode during crossings — the action happens fast. Golden hour (early morning and late afternoon) offers the best light. Dust kicked up by thousands of hooves creates atmospheric backlit shots that define African wildlife photography.</p>

<h2>Conservation and the Future</h2>
<p>The Serengeti-Mara ecosystem faces challenges including poaching, proposed road developments, and increasing human-wildlife conflict on the borders. Responsible tourism plays a vital role: visitor fees directly fund anti-poaching patrols, community programmes, and habitat preservation. By choosing ethical safari operators who invest in local communities, travellers help ensure this migration continues for generations.</p>

<h2>Planning Your Migration Safari</h2>
<p>We recommend a minimum of four nights in the Serengeti to maximise your chances of witnessing a river crossing. Combining the Serengeti with the Ngorongoro Crater (one of the best places in Africa to see the Big Five in a single day) creates a world-class itinerary. Fly-in safaris from Arusha save travel time and offer stunning aerial views. Contact our team to build a migration safari tailored to your preferred dates and budget.</p>
HTML,
                    'fr' => <<<'HTML'
<h2>Qu'est-ce que la Grande Migration ?</h2>
<p>La Grande Migration des gnous est considérée comme l'un des événements naturels les plus impressionnants sur Terre. Chaque année, environ 1,5 million de gnous — accompagnés d'environ 400 000 zèbres, 200 000 gazelles de Thomson et des milliers d'élands — parcourent un circuit circulaire à travers le parc national du Serengeti en Tanzanie et la réserve nationale du Masai Mara au Kenya. Ce mouvement continu est motivé par la recherche de pâturages frais et d'eau, et il n'a ni véritable début ni fin.</p>

<h2>Calendrier mensuel de la migration</h2>
<h3>Janvier – Mars : Saison de mise bas dans le sud du Serengeti</h3>
<p>Les troupeaux se rassemblent dans les plaines d'herbes courtes du sud du Serengeti et de la région de Ndutu, près de l'aire de conservation du Ngorongoro. C'est la saison de mise bas : environ 8 000 veaux de gnous naissent chaque jour pendant un pic de deux à trois semaines en février. L'abondance de nouveau-nés attire les prédateurs — lions, guépards, hyènes et lycaons — ce qui en fait l'une des meilleures périodes pour la photographie de scènes de prédation.</p>

<h3>Avril – Mai : Les grandes pluies et le déplacement vers l'ouest</h3>
<p>Avec l'arrivée des grandes pluies, les plaines du sud deviennent détrempées. Les troupeaux commencent à se déplacer vers le nord-ouest à travers la vallée de Seronera dans le centre du Serengeti. Les safaris pendant cette période sont plus calmes avec moins de touristes, mais le paysage est luxuriant et la vie des oiseaux est extraordinaire.</p>

<h3>Juin – Juillet : Le corridor occidental et les traversées de la rivière Grumeti</h3>
<p>En juin, les immenses colonnes de gnous s'étendent à travers le corridor occidental vers la rivière Grumeti. Les traversées du Grumeti sont intenses mais moins fréquentées que les célèbres traversées de la rivière Mara plus tard dans l'année. D'énormes crocodiles du Nil attendent dans l'eau.</p>

<h3>Août – Octobre : Les traversées emblématiques de la rivière Mara</h3>
<p>C'est la haute saison et la période la plus recherchée. Les troupeaux arrivent à la rivière Mara dans le nord du Serengeti. Les gnous se jettent dans la rivière par milliers, escaladant les rochers et affrontant les crocodiles affamés. Les hébergements dans le nord du Serengeti se réservent 6 à 12 mois à l'avance.</p>

<h3>Novembre – Décembre : Retour vers le sud</h3>
<p>Après le début des courtes pluies en novembre, les troupeaux reprennent le chemin du sud, traversant l'est du Serengeti vers les zones de mise bas. Les plaines sont rafraîchies et vertes, la lumière est spectaculaire pour la photographie.</p>

<h2>Où séjourner pendant la migration</h2>
<p>Le choix du camp ou du lodge dépend entièrement de la période de l'année. Les camps de tentes mobiles qui suivent les troupeaux offrent l'expérience la plus immersive. Les safaris en montgolfière au-dessus des troupeaux offrent une perspective aérienne extraordinaire.</p>

<h2>Planifier votre safari migration</h2>
<p>Nous recommandons un minimum de quatre nuits dans le Serengeti. La combinaison du Serengeti avec le cratère du Ngorongoro crée un itinéraire de classe mondiale. Contactez notre équipe pour construire un safari migration adapté à vos dates et votre budget.</p>
HTML,
                    'de' => <<<'HTML'
<h2>Was ist die Große Migration?</h2>
<p>Die Große Gnu-Wanderung gilt als eines der beeindruckendsten Naturereignisse der Erde. Jedes Jahr wandern etwa 1,5 Millionen Gnus — begleitet von rund 400.000 Zebras, 200.000 Thomson-Gazellen und Tausenden von Elenantilopen — auf einer Rundroute durch den Serengeti-Nationalpark in Tansania und das Masai-Mara-Wildreservat in Kenia.</p>

<h2>Monatskalender der Migration</h2>
<h3>Januar – März: Kalbungszeit im Süden der Serengeti</h3>
<p>Die Herden versammeln sich auf den Kurzgrasebenen der südlichen Serengeti und im Ndutu-Gebiet. Es ist Kalbungszeit: Schätzungsweise 8.000 Gnu-Kälber werden täglich während eines Höhepunkts im Februar geboren. Die Fülle an Neugeborenen lockt Raubtiere an — Löwen, Geparden, Hyänen und Wildhunde.</p>

<h3>April – Mai: Die langen Regenfälle</h3>
<p>Mit den langen Regenfällen beginnen die Herden nordwestwärts durch das Seronera-Tal in der zentralen Serengeti zu ziehen. Pirschfahrten während dieser Zeit sind ruhiger mit weniger Touristen.</p>

<h3>Juni – Juli: Der westliche Korridor</h3>
<p>Im Juni erstrecken sich die massiven Gnu-Kolonnen durch den Westkorridor zum Grumeti-Fluss. Riesige Nilkrokodile lauern im Wasser.</p>

<h3>August – Oktober: Die Mara-Fluss-Überquerungen</h3>
<p>Dies ist die Hauptsaison. Die Herden erreichen den Mara-Fluss in der nördlichen Serengeti. Unterkünfte in der nördlichen Serengeti sind 6 bis 12 Monate im Voraus ausgebucht.</p>

<h3>November – Dezember: Rückkehr nach Süden</h3>
<p>Nach den kurzen Regenfällen im November ziehen die Herden zurück nach Süden. Die Ebenen sind erfrischend grün und das Licht ist spektakulär für Fotografie.</p>

<h2>Planen Sie Ihre Migrations-Safari</h2>
<p>Wir empfehlen mindestens vier Nächte in der Serengeti. Die Kombination der Serengeti mit dem Ngorongoro-Krater ergibt eine Weltklasse-Reiseroute. Kontaktieren Sie unser Team für eine maßgeschneiderte Safari.</p>
HTML,
                    'es' => <<<'HTML'
<h2>¿Qué es la Gran Migración?</h2>
<p>La Gran Migración del ñu es ampliamente considerada como uno de los eventos naturales más impresionantes de la Tierra. Cada año, aproximadamente 1,5 millones de ñus — acompañados por unos 400.000 cebras, 200.000 gacelas de Thomson y miles de antílopes eland — recorren una ruta circular a través del Parque Nacional del Serengeti en Tanzania y la Reserva Nacional de Masai Mara en Kenia.</p>

<h2>Calendario mensual de la migración</h2>
<h3>Enero – Marzo: Temporada de partos en el sur del Serengeti</h3>
<p>Los rebaños se congregan en las llanuras de hierba corta del sur del Serengeti y el área de Ndutu. Se estima que nacen 8.000 crías de ñu cada día durante el pico en febrero. La abundancia de recién nacidos atrae depredadores — leones, guepardos, hienas y licaones.</p>

<h3>Abril – Mayo: Las lluvias largas</h3>
<p>Con la llegada de las lluvias largas, los rebaños comienzan a moverse hacia el noroeste a través del Valle de Seronera en el centro del Serengeti.</p>

<h3>Junio – Julio: El corredor occidental</h3>
<p>En junio, las enormes columnas de ñus se extienden a través del corredor occidental hacia el río Grumeti. Enormes cocodrilos del Nilo acechan en el agua.</p>

<h3>Agosto – Octubre: Los cruces del río Mara</h3>
<p>Esta es la temporada alta. Los rebaños llegan al río Mara en el norte del Serengeti. Los alojamientos se reservan con 6 a 12 meses de anticipación.</p>

<h3>Noviembre – Diciembre: Regreso al sur</h3>
<p>Después de las lluvias cortas en noviembre, los rebaños regresan al sur. Las llanuras están verdes y la luz es espectacular para la fotografía.</p>

<h2>Planifique su safari de migración</h2>
<p>Recomendamos un mínimo de cuatro noches en el Serengeti. Combinarlo con el cráter del Ngorongoro crea un itinerario de clase mundial. Contacte a nuestro equipo para un safari a medida.</p>
HTML,
                ],
            ],

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // POST 2: Best Time to Visit Tanzania
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            [
                'slug' => 'best-time-to-visit-tanzania-safari',
                'category' => 'Safari Tips',
                'meta_title' => 'Best Time to Visit Tanzania for Safari in 2026 | Season Guide',
                'meta_description' => 'Discover the best time to visit Tanzania for safari, climbing Kilimanjaro, and Zanzibar beaches. Month-by-month guide with weather, wildlife, and pricing tips.',
                'meta_keywords' => 'best time to visit tanzania, tanzania safari season, dry season safari, when to go tanzania, serengeti best time, kilimanjaro best time',
                'title' => [
                    'en' => 'Best Time to Visit Tanzania for Safari: A Season-by-Season Guide',
                    'fr' => 'Meilleure Période pour Visiter la Tanzanie en Safari : Guide Saison par Saison',
                    'de' => 'Beste Reisezeit für eine Tansania-Safari: Ein Leitfaden für Jede Jahreszeit',
                    'es' => 'Mejor Época para Visitar Tanzania en Safari: Guía Temporada por Temporada',
                ],
                'excerpt' => [
                    'en' => 'Tanzania offers extraordinary wildlife year-round, but each season has its own character. From the dry season\'s unbeatable game viewing to the green season\'s dramatic skies and newborn animals, here is your complete guide to choosing the perfect time for your safari.',
                    'fr' => 'La Tanzanie offre une faune extraordinaire toute l\'année, mais chaque saison a son propre caractère. De l\'observation des animaux en saison sèche aux ciels dramatiques de la saison verte, voici votre guide complet pour choisir le moment idéal.',
                    'de' => 'Tansania bietet das ganze Jahr über eine außergewöhnliche Tierwelt, aber jede Jahreszeit hat ihren eigenen Charakter. Von der Trockenzeit bis zur Grünen Saison — hier finden Sie Ihren vollständigen Leitfaden.',
                    'es' => 'Tanzania ofrece fauna extraordinaria durante todo el año, pero cada temporada tiene su propio carácter. Desde la temporada seca hasta la verde, aquí está su guía completa para elegir el momento perfecto.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Understanding Tanzania's Two Main Seasons</h2>
<p>Tanzania's climate is broadly divided into two seasons: the dry season (June to October) and the wet season (November to May). The wet season is further split into the short rains (November to December) and the long rains (March to May). Each period offers a distinctly different safari experience, and the "best" time depends entirely on what you want to see and do.</p>

<h2>Dry Season (June – October): Peak Safari Time</h2>
<p>The dry season is the most popular time to visit Tanzania, and for good reason. With minimal rainfall, vegetation thins out and animals congregate around rivers, waterholes, and remaining green areas, making wildlife spotting significantly easier. The Serengeti's northern region comes alive with the Great Migration's dramatic Mara River crossings from July to October.</p>
<p>The Ngorongoro Crater is spectacular year-round, but during the dry season the crater floor's limited water sources concentrate incredible densities of wildlife — you can see lion, buffalo, elephant, rhino, and hippo all within a few hours. Tarangire National Park, often called the "elephant capital of Tanzania," is at its best from July to October when thousands of elephants gather along the Tarangire River.</p>
<p><strong>Considerations:</strong> This is peak season, so expect higher prices and busier parks. Book lodges and camps at least 6 months in advance, especially for the northern Serengeti.</p>

<h2>Short Rains (November – December): The Secret Season</h2>
<p>November and December are an excellent shoulder season. Brief afternoon showers refresh the landscape without disrupting game drives. The migration herds move through the eastern Serengeti heading south, and birdlife reaches its peak as migratory species arrive from Europe and Asia. Many operators offer discounted green-season rates while conditions remain very favourable for safari.</p>
<p>This is also an ideal time for climbing Mount Kilimanjaro — the summit is less crowded and the weather windows between November and mid-December are generally reliable.</p>

<h2>Calving Season (January – February): Birth of the Plains</h2>
<p>If you are drawn to the raw drama of life on the plains, January and February are unmatched. The southern Serengeti and Ndutu area host the calving season, with up to 8,000 wildebeest calves born daily. Predators are everywhere — big cats, hyena clans, and jackals patrol in high numbers. The flat, open terrain makes for extraordinary photography. The weather is warm and relatively dry between the two rainy periods.</p>

<h2>Long Rains (March – May): The Quiet Green Season</h2>
<p>The long rains bring the most rainfall and the fewest tourists. Some remote camps close, but lodges in the Serengeti, Ngorongoro, and northern circuit remain open. The landscape transforms into vivid shades of green under dramatic thunderstorm skies. Game viewing is still rewarding — animals don't disappear; they simply spread out. Prices drop 30–50%, and you may have entire areas to yourself. For photographers, the moody lighting and storm clouds create award-winning compositions.</p>

<h2>Best Time for Kilimanjaro</h2>
<p>The two best windows for summiting Mount Kilimanjaro are January to mid-March and June to October. These periods offer the driest conditions on the mountain. The Machame and Lemosho routes, which take six to eight days, provide the best acclimatisation profiles and summit success rates above 90%.</p>

<h2>Best Time for Zanzibar</h2>
<p>Zanzibar's beaches are warmest and driest from June to October and December to February. These windows align perfectly with post-safari beach extensions. The island's underwater visibility for diving and snorkelling peaks between October and March, when whale sharks can sometimes be spotted off the south coast.</p>

<h2>Our Recommendation</h2>
<p>For first-time visitors seeking the classic safari experience, we recommend July to October. For repeat visitors or travellers seeking value, the November to December shoulder season or January to February calving season offer extraordinary experiences at lower prices with fewer crowds. Whatever your dates, our team will design an itinerary that maximises wildlife encounters and minimises time on the road.</p>
HTML,
                    'fr' => <<<'HTML'
<h2>Comprendre les deux saisons principales de la Tanzanie</h2>
<p>Le climat de la Tanzanie se divise en deux saisons : la saison sèche (juin à octobre) et la saison des pluies (novembre à mai). La saison des pluies se subdivise en courtes pluies (novembre-décembre) et longues pluies (mars-mai). Chaque période offre une expérience safari distincte, et la « meilleure » période dépend entièrement de vos attentes.</p>

<h2>Saison sèche (juin – octobre) : Haute saison safari</h2>
<p>C'est la période la plus populaire. Avec moins de pluie, la végétation s'éclaircit et les animaux se rassemblent autour des points d'eau. Le Serengeti nord s'anime avec les traversées de la rivière Mara de juillet à octobre. Le cratère du Ngorongoro concentre une densité incroyable de faune. Le parc national de Tarangire est à son meilleur avec des milliers d'éléphants.</p>

<h2>Courtes pluies (novembre – décembre) : La saison secrète</h2>
<p>Novembre et décembre sont une excellente période intermédiaire. De brèves averses rafraîchissent le paysage. La vie des oiseaux atteint son pic. Beaucoup d'opérateurs offrent des tarifs réduits.</p>

<h2>Saison de mise bas (janvier – février)</h2>
<p>Si vous êtes attiré par le drame de la vie sauvage, janvier et février sont inégalés. La zone de Ndutu accueille la saison de mise bas avec jusqu'à 8 000 veaux de gnous naissant chaque jour.</p>

<h2>Longues pluies (mars – mai) : La saison verte calme</h2>
<p>Les longues pluies apportent le plus de précipitations et le moins de touristes. Le paysage se transforme en nuances vibrantes de vert. Les prix baissent de 30 à 50 %.</p>

<h2>Notre recommandation</h2>
<p>Pour une première visite, nous recommandons juillet à octobre. Pour les voyageurs recherchant de la valeur, la période novembre-décembre ou janvier-février offre des expériences extraordinaires à des prix plus bas. Contactez notre équipe pour un itinéraire sur mesure.</p>
HTML,
                    'de' => <<<'HTML'
<h2>Tansanias zwei Hauptjahreszeiten verstehen</h2>
<p>Tansanias Klima teilt sich in Trockenzeit (Juni bis Oktober) und Regenzeit (November bis Mai). Die Regenzeit unterteilt sich weiter in kurze Regen (November-Dezember) und lange Regen (März-Mai). Jede Periode bietet ein einzigartiges Safari-Erlebnis.</p>

<h2>Trockenzeit (Juni – Oktober): Safari-Hochsaison</h2>
<p>Dies ist die beliebteste Reisezeit. Bei wenig Regen versammeln sich die Tiere an Wasserstellen. Die nördliche Serengeti bietet die dramatischen Mara-Fluss-Überquerungen von Juli bis Oktober. Der Ngorongoro-Krater konzentriert unglaubliche Wildtierdichten. Der Tarangire-Nationalpark zeigt Tausende von Elefanten.</p>

<h2>Kurze Regen (November – Dezember): Die Geheimtipp-Saison</h2>
<p>Kurze Nachmittagsregen erfrischen die Landschaft. Die Vogelwelt erreicht ihren Höhepunkt. Viele Veranstalter bieten ermäßigte Tarife.</p>

<h2>Kalbungszeit (Januar – Februar)</h2>
<p>Das Ndutu-Gebiet beherbergt die Kalbungszeit mit bis zu 8.000 neugeborenen Gnu-Kälbern täglich. Raubtiere sind überall.</p>

<h2>Lange Regen (März – Mai): Die ruhige Grüne Saison</h2>
<p>Die meisten Niederschläge und die wenigsten Touristen. Die Preise sinken um 30–50 %.</p>

<h2>Unsere Empfehlung</h2>
<p>Für Erstbesucher empfehlen wir Juli bis Oktober. Kontaktieren Sie unser Team für eine maßgeschneiderte Reiseroute.</p>
HTML,
                    'es' => <<<'HTML'
<h2>Las dos temporadas principales de Tanzania</h2>
<p>El clima de Tanzania se divide en temporada seca (junio a octubre) y temporada de lluvias (noviembre a mayo). La temporada de lluvias se divide en lluvias cortas (noviembre-diciembre) y lluvias largas (marzo-mayo). Cada período ofrece una experiencia de safari única.</p>

<h2>Temporada seca (junio – octubre): Temporada alta de safari</h2>
<p>Es la época más popular. Con menos lluvia, los animales se congregan alrededor de los puntos de agua. El norte del Serengeti cobra vida con los cruces del río Mara de julio a octubre. El cráter del Ngorongoro concentra densidades increíbles de fauna. Tarangire muestra miles de elefantes.</p>

<h2>Lluvias cortas (noviembre – diciembre): La temporada secreta</h2>
<p>Breves lluvias por la tarde refrescan el paisaje. La vida de las aves alcanza su máximo. Muchos operadores ofrecen tarifas reducidas.</p>

<h2>Temporada de partos (enero – febrero)</h2>
<p>El área de Ndutu alberga la temporada de partos con hasta 8.000 crías de ñu naciendo diariamente. Los depredadores están en todas partes.</p>

<h2>Lluvias largas (marzo – mayo): La temporada verde tranquila</h2>
<p>La mayor precipitación y menos turistas. Los precios bajan un 30–50 %.</p>

<h2>Nuestra recomendación</h2>
<p>Para visitantes primerizos, recomendamos julio a octubre. Contacte a nuestro equipo para un itinerario a medida.</p>
HTML,
                ],
            ],

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // POST 3: Big Five Tanzania
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            [
                'slug' => 'big-five-tanzania-where-to-find-them',
                'category' => 'Wildlife',
                'meta_title' => 'The Big Five in Tanzania: Where to See Lion, Elephant, Buffalo, Leopard & Rhino',
                'meta_description' => 'Complete guide to finding all Big Five animals in Tanzania. Best national parks, expert tips, and itineraries for spotting lion, elephant, buffalo, leopard, and rhino.',
                'meta_keywords' => 'big five tanzania, big five safari, tanzania lion, ngorongoro rhino, serengeti leopard, tarangire elephant, tanzania wildlife',
                'title' => [
                    'en' => 'The Big Five in Tanzania: Where to Find Every One of Them',
                    'fr' => 'Les Big Five en Tanzanie : Où Les Trouver Tous',
                    'de' => 'Die Big Five in Tansania: Wo Sie Jedes Einzelne Finden',
                    'es' => 'Los Cinco Grandes en Tanzania: Dónde Encontrarlos a Todos',
                ],
                'excerpt' => [
                    'en' => 'Tanzania is one of the best countries in Africa to see the Big Five — lion, African elephant, Cape buffalo, leopard, and black rhino — all in their natural habitat. This guide covers exactly where, when, and how to find each one.',
                    'fr' => 'La Tanzanie est l\'un des meilleurs pays d\'Afrique pour voir les Big Five — lion, éléphant, buffle, léopard et rhinocéros noir — dans leur habitat naturel. Ce guide couvre exactement où, quand et comment trouver chacun d\'entre eux.',
                    'de' => 'Tansania ist eines der besten Länder Afrikas, um die Big Five zu sehen — Löwe, Elefant, Büffel, Leopard und Nashorn — alle in ihrem natürlichen Lebensraum.',
                    'es' => 'Tanzania es uno de los mejores países de África para ver los Cinco Grandes — león, elefante, búfalo, leopardo y rinoceronte negro — todos en su hábitat natural.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Why Tanzania for the Big Five?</h2>
<p>Tanzania is home to some of the largest and most protected wildlife populations in Africa. The country's northern safari circuit — spanning the Serengeti, Ngorongoro Crater, Tarangire, and Lake Manyara — offers an almost guaranteed Big Five experience when you know where to look. Unlike some destinations where certain species are extremely rare, Tanzania provides realistic opportunities to see all five animals in a single safari of just four to six days.</p>

<h2>Lion: King of the Serengeti</h2>
<p>Tanzania has one of the largest lion populations in Africa, estimated at over 15,000 individuals. The Serengeti is the premier location, home to famous prides that have been studied for decades. The Seronera area in the central Serengeti offers some of the most reliable lion sightings in Africa, with prides regularly seen lounging on kopjes — the rocky granite outcrops dotting the plains. The Ngorongoro Crater's confined space means its resident pride of around 60 lions are almost always visible. For something truly special, the tree-climbing lions of Lake Manyara, though less predictable, are a unique Tanzanian phenomenon.</p>

<h2>African Elephant: Giants of Tarangire</h2>
<p>While elephants roam throughout Tanzania's parks, Tarangire National Park is where they gather in truly staggering numbers. During the dry season (July to October), an estimated 3,000 elephants congregate along the Tarangire River, creating some of the largest elephant gatherings visible anywhere in East Africa. Watching a herd of 50 or more elephants crossing the river at sunset, with baobab trees silhouetted against the sky, is a quintessential Tanzania moment. Elephants are also commonly seen in the Serengeti, Ngorongoro, and the lesser-visited Ruaha National Park, which holds Tanzania's largest elephant population.</p>

<h2>Cape Buffalo: The Unpredictable Giant</h2>
<p>Often underestimated, the Cape buffalo is considered by many guides to be the most dangerous of the Big Five. Tanzania's parks support vast herds — the Ngorongoro Crater alone has herds exceeding 500 individuals. In the Serengeti, buffalo are most commonly seen in the woodland areas around Seronera and the Western Corridor. Watching a large herd cross an open plain, kicking up dust while oxpeckers perch on their backs, is a powerful sight. Buffalo also provide dramatic predator-prey encounters — lions must work as a team to bring down a single adult buffalo, and the battle can last hours.</p>

<h2>Leopard: The Elusive Shadow</h2>
<p>The leopard is the most difficult of the Big Five to spot, but Tanzania offers some of the best leopard viewing in all of Africa. The Seronera Valley in the central Serengeti is renowned as one of the most reliable places in the world to see leopards. Sausage trees and tall acacias along the Seronera River provide perfect resting spots, and experienced guides know exactly which trees to check. Lake Manyara and the Ngorongoro Crater also produce regular leopard sightings. Expert guides dramatically increase your chances — their knowledge of individual leopards' territories and habits is invaluable.</p>

<h2>Black Rhino: Tanzania's Rarest Treasure</h2>
<p>With fewer than 5,500 black rhinos surviving in the wild across all of Africa, seeing one is a genuine privilege. The Ngorongoro Crater is by far the best place in Tanzania — and arguably all of East Africa — to spot black rhino. The crater's enclosed geography means the small population of approximately 30 rhinos has a limited range, and sightings, while not guaranteed, are regular. Binoculars are essential as rhinos often browse at a distance on the crater floor. The Serengeti's Moru Kopjes area in the south-central region also has a small rhino population protected by dedicated rangers.</p>

<h2>The Best Big Five Itinerary</h2>
<p>For the best chance of seeing all Big Five in a single trip, we recommend a six-day itinerary: two nights in the Serengeti (lion, leopard, elephant, buffalo), one night at Lake Manyara or Tarangire (elephant herds, tree-climbing lions), and two nights at the Ngorongoro Crater (rhino, plus all others). This routing covers different ecosystems and maximises species diversity. A skilled guide who knows the parks intimately can make the difference between a good safari and an extraordinary one.</p>
HTML,
                    'fr' => <<<'HTML'
<h2>Pourquoi la Tanzanie pour les Big Five ?</h2>
<p>La Tanzanie abrite certaines des populations de faune sauvage les plus importantes et les mieux protégées d'Afrique. Le circuit safari du nord — comprenant le Serengeti, le cratère du Ngorongoro, Tarangire et le lac Manyara — offre une expérience Big Five presque garantie en quatre à six jours.</p>

<h2>Lion : Le roi du Serengeti</h2>
<p>La Tanzanie possède l'une des plus grandes populations de lions d'Afrique, estimée à plus de 15 000 individus. La zone de Seronera dans le centre du Serengeti offre les observations les plus fiables. Les lions du cratère du Ngorongoro sont presque toujours visibles grâce à l'espace confiné.</p>

<h2>Éléphant d'Afrique : Les géants de Tarangire</h2>
<p>Pendant la saison sèche, environ 3 000 éléphants se rassemblent le long de la rivière Tarangire. Observer un troupeau de 50 éléphants au coucher du soleil avec les baobabs en silhouette est un moment quintessentiel.</p>

<h2>Buffle du Cap</h2>
<p>Souvent sous-estimé, le buffle du Cap est considéré par de nombreux guides comme le plus dangereux des Big Five. Le cratère du Ngorongoro abrite des troupeaux de plus de 500 individus.</p>

<h2>Léopard : L'ombre insaisissable</h2>
<p>La vallée de Seronera dans le Serengeti central est l'un des endroits les plus fiables au monde pour observer les léopards. Des guides expérimentés augmentent considérablement vos chances.</p>

<h2>Rhinocéros noir : Le trésor le plus rare de Tanzanie</h2>
<p>Le cratère du Ngorongoro est le meilleur endroit en Tanzanie pour observer le rhinocéros noir. Une population d'environ 30 rhinocéros vit dans l'enceinte du cratère.</p>

<h2>Le meilleur itinéraire Big Five</h2>
<p>Nous recommandons un itinéraire de six jours : deux nuits au Serengeti, une nuit à Tarangire, et deux nuits au cratère du Ngorongoro. Un guide compétent fait toute la différence.</p>
HTML,
                    'de' => <<<'HTML'
<h2>Warum Tansania für die Big Five?</h2>
<p>Tansanias nördlicher Safari-Rundkurs — Serengeti, Ngorongoro-Krater, Tarangire und Lake Manyara — bietet ein nahezu garantiertes Big-Five-Erlebnis in vier bis sechs Tagen.</p>

<h2>Löwe: König der Serengeti</h2>
<p>Tansania hat eine der größten Löwenpopulationen Afrikas mit über 15.000 Individuen. Das Seronera-Gebiet bietet die zuverlässigsten Sichtungen.</p>

<h2>Afrikanischer Elefant: Die Riesen von Tarangire</h2>
<p>Während der Trockenzeit versammeln sich etwa 3.000 Elefanten am Tarangire-Fluss.</p>

<h2>Kaffernbüffel</h2>
<p>Der Ngorongoro-Krater beherbergt Herden von über 500 Individuen.</p>

<h2>Leopard: Der scheue Schatten</h2>
<p>Das Seronera-Tal in der zentralen Serengeti ist einer der zuverlässigsten Orte der Welt für Leopardensichtungen.</p>

<h2>Spitzmaulnashorn: Tansanias seltenster Schatz</h2>
<p>Der Ngorongoro-Krater ist der beste Ort in Tansania, um Spitzmaulnashörner zu sehen — etwa 30 Tiere leben im Krater.</p>

<h2>Die beste Big-Five-Reiseroute</h2>
<p>Sechs Tage: zwei Nächte Serengeti, eine Nacht Tarangire, zwei Nächte Ngorongoro-Krater.</p>
HTML,
                    'es' => <<<'HTML'
<h2>¿Por qué Tanzania para los Cinco Grandes?</h2>
<p>El circuito norte de safari de Tanzania — Serengeti, cráter del Ngorongoro, Tarangire y lago Manyara — ofrece una experiencia de Cinco Grandes casi garantizada en cuatro a seis días.</p>

<h2>León: Rey del Serengeti</h2>
<p>Tanzania tiene más de 15.000 leones. El área de Seronera ofrece los avistamientos más fiables.</p>

<h2>Elefante africano: Los gigantes de Tarangire</h2>
<p>Durante la temporada seca, unos 3.000 elefantes se congregan junto al río Tarangire.</p>

<h2>Búfalo del Cabo</h2>
<p>El cráter del Ngorongoro alberga manadas de más de 500 individuos.</p>

<h2>Leopardo: La sombra esquiva</h2>
<p>El valle de Seronera es uno de los lugares más fiables del mundo para avistar leopardos.</p>

<h2>Rinoceronte negro: El tesoro más raro de Tanzania</h2>
<p>El cráter del Ngorongoro es el mejor lugar — unos 30 rinocerontes viven dentro del cráter.</p>

<h2>El mejor itinerario de los Cinco Grandes</h2>
<p>Seis días: dos noches en el Serengeti, una noche en Tarangire, dos noches en el cráter del Ngorongoro.</p>
HTML,
                ],
            ],

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // POST 4: Kilimanjaro
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            [
                'slug' => 'climbing-mount-kilimanjaro-ultimate-guide',
                'category' => 'Travel Guides',
                'meta_title' => 'Climbing Mount Kilimanjaro: Routes, Cost, Packing & Tips for 2026',
                'meta_description' => 'Everything you need to know about climbing Mount Kilimanjaro. Compare all 7 routes, understand costs, learn what to pack, and get expert tips for a successful summit.',
                'meta_keywords' => 'mount kilimanjaro, climb kilimanjaro, kilimanjaro routes, kilimanjaro cost, machame route, lemosho route, kilimanjaro packing list',
                'title' => [
                    'en' => 'Climbing Mount Kilimanjaro: The Ultimate Guide for 2026',
                    'fr' => 'Gravir le Mont Kilimandjaro : Le Guide Ultime pour 2026',
                    'de' => 'Besteigung des Kilimandscharo: Der Ultimative Leitfaden für 2026',
                    'es' => 'Escalar el Monte Kilimanjaro: La Guía Definitiva para 2026',
                ],
                'excerpt' => [
                    'en' => 'At 5,895 metres, Mount Kilimanjaro is Africa\'s highest peak and the world\'s tallest freestanding mountain. No technical climbing is required — only determination, preparation, and the right route choice. Here is everything you need for a successful summit.',
                    'fr' => 'Culminant à 5 895 mètres, le Mont Kilimandjaro est le plus haut sommet d\'Afrique. Aucune escalade technique n\'est requise — seulement de la détermination, de la préparation et le bon choix de route.',
                    'de' => 'Mit 5.895 Metern ist der Kilimandscharo Afrikas höchster Gipfel. Es ist keine technische Kletterei erforderlich — nur Entschlossenheit, Vorbereitung und die richtige Routenwahl.',
                    'es' => 'Con 5.895 metros, el Monte Kilimanjaro es el pico más alto de África. No se requiere escalada técnica — solo determinación, preparación y la elección de ruta correcta.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Why Kilimanjaro?</h2>
<p>Mount Kilimanjaro stands alone on the East African plains near the town of Moshi in northern Tanzania. It is a UNESCO World Heritage Site and one of the Seven Summits — the highest mountains on each continent. What makes Kilimanjaro unique is that it can be summited without any technical climbing equipment or mountaineering experience. You walk to the roof of Africa. That accessibility, combined with the extraordinary journey through five distinct climate zones — from tropical rainforest to arctic glacier — draws over 50,000 trekkers each year.</p>

<h2>The Seven Routes Compared</h2>

<h3>Machame Route ("Whisky Route") — 6–7 Days</h3>
<p>The most popular route for good reason. The Machame Route offers stunning scenery, excellent acclimatisation through its "walk high, sleep low" profile, and a challenging but rewarding Barranco Wall scramble. The summit success rate is around 85–90% on a seven-day itinerary. It approaches from the south and descends via the Mweka Route.</p>

<h3>Lemosho Route — 7–8 Days</h3>
<p>Widely considered the most scenic route, Lemosho starts from the west through pristine rainforest with high chances of seeing colobus monkeys and exotic birdlife. The extra days allow superior acclimatisation, pushing summit success rates above 90%. It joins the Machame Route at the Lava Tower on day four. For those with the time and budget, this is our top recommendation.</p>

<h3>Marangu Route ("Coca-Cola Route") — 5–6 Days</h3>
<p>The only route with hut accommodation rather than tents, Marangu is often perceived as the "easiest" route. However, its shorter default itinerary of five days provides less acclimatisation time, and summit success rates are lower (around 65–70%). Adding an extra acclimatisation day at Horombo Hut significantly improves your chances.</p>

<h3>Rongai Route — 6–7 Days</h3>
<p>Approaching from the north near the Kenyan border, Rongai offers a quieter, drier experience. It is the best choice during the rainy season as the northern slopes receive less precipitation. The landscape is more arid and open, with views toward Kenya. Summit success rates are comparable to Machame.</p>

<h3>Northern Circuit — 8–9 Days</h3>
<p>The longest route on Kilimanjaro, the Northern Circuit circumnavigates the mountain, passing through virtually untouched wilderness. The extended duration provides the best acclimatisation of any route, with summit success rates exceeding 95%. It is the least crowded route and offers panoramic views from every angle.</p>

<h3>Umbwe Route — 5–6 Days</h3>
<p>The steepest and most direct route, Umbwe is suited to experienced and very fit trekkers. It ascends rapidly through dense forest and reaches the Southern Icefields. Due to the fast altitude gain, summit success rates are lower unless an extra acclimatisation day is added.</p>

<h3>Shira Route — 7–8 Days</h3>
<p>Starting at a high elevation on the Shira Plateau (3,600 m), this route offers unique plateau landscapes but begins with less acclimatisation support. It merges with Lemosho after day two.</p>

<h2>What Does It Cost?</h2>
<p>A Kilimanjaro trek typically costs between $2,000 and $5,500 per person depending on the route, duration, operator, and group size. This includes park fees (which alone are approximately $1,000–$1,200 for a seven-day trek), guides, porters, meals, camping equipment, and airport transfers. Choosing a reputable operator who pays porters fairly, provides proper safety equipment, and employs certified guides is essential — this is not an experience to book on price alone.</p>

<h2>Essential Packing List</h2>
<p><strong>Clothing layers:</strong> Moisture-wicking base layer, insulating fleece mid-layer, waterproof outer shell, down jacket for summit night (temperatures can drop to -20°C). <strong>Footwear:</strong> Broken-in waterproof hiking boots, gaiters, camp sandals. <strong>Accessories:</strong> Warm beanie, sun hat, UV-protection sunglasses, headlamp with spare batteries, trekking poles. <strong>Health:</strong> Diamox (acetazolamide) for altitude sickness prevention (consult your doctor), water purification tablets, high-SPF sunscreen, lip balm with SPF. <strong>Other:</strong> 30–40 litre daypack, 3-litre hydration system, energy snacks, camera with spare batteries (cold drains batteries fast).</p>

<h2>Summit Night: What to Expect</h2>
<p>You will depart from high camp (either Barafu or Kibo Hut) around midnight, climbing through darkness by headlamp. The final push to Uhuru Peak takes five to seven hours on steep scree and switch-backs. The temperature can be as low as -20°C with wind chill. Fatigue, nausea, and breathlessness are normal at this altitude. When you reach the summit sign at 5,895 metres and see the sunrise painting the glaciers gold, every difficult step was worth it. After summit photos, you descend the same day to lower camp.</p>

<h2>Combining Kilimanjaro with Safari</h2>
<p>Many of our clients combine a Kilimanjaro trek with a three-to-five-day safari through the northern circuit parks and finish with a beach stay on Zanzibar. This combination — mountain, wildlife, and ocean — is the ultimate Tanzania experience. We recommend at least one rest day between the trek and safari.</p>
HTML,
                    'fr' => <<<'HTML'
<h2>Pourquoi le Kilimandjaro ?</h2>
<p>Le Mont Kilimandjaro se dresse seul sur les plaines d'Afrique de l'Est, près de la ville de Moshi. C'est un site du patrimoine mondial de l'UNESCO et l'un des Sept Sommets. Ce qui rend le Kilimandjaro unique, c'est qu'il peut être atteint sans équipement technique. Vous marchez jusqu'au toit de l'Afrique à travers cinq zones climatiques distinctes.</p>

<h2>Comparaison des sept routes</h2>
<h3>Route Machame (6–7 jours)</h3>
<p>La route la plus populaire. Paysages magnifiques, excellente acclimatation « monter haut, dormir bas ». Taux de réussite au sommet : 85–90 % sur un itinéraire de sept jours.</p>

<h3>Route Lemosho (7–8 jours)</h3>
<p>Considérée comme la plus panoramique. Les jours supplémentaires permettent une acclimatation supérieure avec un taux de réussite supérieur à 90 %. Notre recommandation principale.</p>

<h3>Route Marangu (5–6 jours)</h3>
<p>La seule route avec hébergement en refuge. Son itinéraire par défaut de cinq jours offre moins de temps d'acclimatation, avec un taux de réussite de 65–70 %.</p>

<h3>Route Rongai (6–7 jours)</h3>
<p>Approche par le nord, plus calme et plus sèche. Le meilleur choix pendant la saison des pluies.</p>

<h2>Combien ça coûte ?</h2>
<p>Un trek au Kilimandjaro coûte entre 2 000 $ et 5 500 $ par personne selon la route, la durée et l'opérateur. Les frais du parc seuls représentent environ 1 000–1 200 $ pour un trek de sept jours.</p>

<h2>La nuit du sommet</h2>
<p>Départ du camp supérieur vers minuit. L'ascension finale vers Uhuru Peak prend cinq à sept heures. La température peut descendre à -20 °C. Quand vous atteignez le sommet à 5 895 mètres et voyez le lever du soleil dorer les glaciers, chaque pas difficile en valait la peine.</p>

<h2>Combiner Kilimandjaro et Safari</h2>
<p>Montagne, faune sauvage et océan — c'est l'expérience ultime en Tanzanie. Nous recommandons au moins un jour de repos entre le trek et le safari.</p>
HTML,
                    'de' => <<<'HTML'
<h2>Warum Kilimandscharo?</h2>
<p>Der Kilimandscharo steht allein in den ostafrikanischen Ebenen nahe der Stadt Moshi. Er ist UNESCO-Welterbe und einer der Seven Summits. Was ihn einzigartig macht: Keine technische Kletterei erforderlich. Sie wandern durch fünf Klimazonen zum Dach Afrikas.</p>

<h2>Die sieben Routen im Vergleich</h2>
<h3>Machame-Route (6–7 Tage)</h3>
<p>Die beliebteste Route. Hervorragende Akklimatisierung, Gipfelerfolgsrate 85–90 % bei sieben Tagen.</p>

<h3>Lemosho-Route (7–8 Tage)</h3>
<p>Die landschaftlich schönste Route. Gipfelerfolgsrate über 90 %. Unsere Top-Empfehlung.</p>

<h3>Marangu-Route (5–6 Tage)</h3>
<p>Die einzige Route mit Hüttenunterkünften. Gipfelerfolgsrate 65–70 % bei fünf Tagen.</p>

<h2>Was kostet es?</h2>
<p>Ein Kilimandscharo-Trek kostet zwischen 2.000 $ und 5.500 $ pro Person je nach Route und Dauer.</p>

<h2>Gipfelnacht</h2>
<p>Aufbruch gegen Mitternacht. Der letzte Aufstieg zum Uhuru Peak dauert fünf bis sieben Stunden bei bis zu -20 °C.</p>

<h2>Kilimandscharo mit Safari kombinieren</h2>
<p>Berg, Wildtiere und Ozean — das ultimative Tansania-Erlebnis. Mindestens ein Ruhetag zwischen Trek und Safari empfohlen.</p>
HTML,
                    'es' => <<<'HTML'
<h2>¿Por qué el Kilimanjaro?</h2>
<p>El Monte Kilimanjaro se yergue solo en las llanuras de África Oriental, cerca de la ciudad de Moshi. Es Patrimonio de la Humanidad de la UNESCO y una de las Siete Cumbres. No se requiere escalada técnica — caminas a través de cinco zonas climáticas hasta el techo de África.</p>

<h2>Las siete rutas comparadas</h2>
<h3>Ruta Machame (6–7 días)</h3>
<p>La más popular. Excelente aclimatación, tasa de éxito del 85–90 % en siete días.</p>

<h3>Ruta Lemosho (7–8 días)</h3>
<p>La más panorámica. Tasa de éxito superior al 90 %. Nuestra principal recomendación.</p>

<h3>Ruta Marangu (5–6 días)</h3>
<p>La única con alojamiento en refugios. Tasa de éxito del 65–70 % en cinco días.</p>

<h2>¿Cuánto cuesta?</h2>
<p>Un trek al Kilimanjaro cuesta entre $2.000 y $5.500 por persona según la ruta y duración.</p>

<h2>Noche de cumbre</h2>
<p>Salida alrededor de medianoche. El ascenso final a Uhuru Peak lleva cinco a siete horas con temperaturas de hasta -20 °C.</p>

<h2>Combinar Kilimanjaro con Safari</h2>
<p>Montaña, vida silvestre y océano — la experiencia definitiva en Tanzania.</p>
HTML,
                ],
            ],

            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            // POST 5: Zanzibar Beaches
            // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
            [
                'slug' => 'zanzibar-beaches-guide-after-safari',
                'category' => 'Travel Guides',
                'meta_title' => 'Zanzibar After Safari: Best Beaches, Hotels & Things to Do in 2026',
                'meta_description' => 'The ultimate Zanzibar beach guide. Discover the best beaches, luxury resorts, Stone Town, spice tours, and snorkelling spots to pair with your Tanzania safari.',
                'meta_keywords' => 'zanzibar beaches, zanzibar after safari, best beaches zanzibar, stone town, nungwi beach, zanzibar hotels, zanzibar snorkelling, spice island',
                'title' => [
                    'en' => 'Zanzibar After Safari: Best Beaches, Hotels & Things to Do',
                    'fr' => 'Zanzibar Après le Safari : Meilleures Plages, Hôtels & Activités',
                    'de' => 'Sansibar Nach der Safari: Beste Strände, Hotels & Aktivitäten',
                    'es' => 'Zanzíbar Después del Safari: Mejores Playas, Hoteles y Qué Hacer',
                ],
                'excerpt' => [
                    'en' => 'After days of dusty game drives and early morning wake-up calls, Zanzibar\'s turquoise waters and white-sand beaches are the perfect reward. This guide covers the best beaches, where to stay, and what to do on the Spice Island.',
                    'fr' => 'Après des jours de safaris poussiéreux, les eaux turquoise et les plages de sable blanc de Zanzibar sont la récompense parfaite. Ce guide couvre les meilleures plages, où séjourner et quoi faire sur l\'île aux Épices.',
                    'de' => 'Nach staubigen Pirschfahrten sind Sansibars türkisfarbene Gewässer und weiße Sandstrände die perfekte Belohnung. Dieser Leitfaden zu den besten Stränden, Unterkünften und Aktivitäten.',
                    'es' => 'Después de días de safaris polvorientos, las aguas turquesas y las playas de arena blanca de Zanzíbar son la recompensa perfecta. Esta guía cubre las mejores playas, dónde alojarse y qué hacer.',
                ],
                'content' => [
                    'en' => <<<'HTML'
<h2>Why Zanzibar After Safari?</h2>
<p>A Tanzania safari followed by a Zanzibar beach stay has become the classic East African travel combination, and for good reason. After the dust, excitement, and early starts of the bush, Zanzibar delivers a completely different sensory experience: the smell of cloves and cinnamon drifting through Stone Town's ancient alleyways, the sound of dhow sails catching the monsoon wind, and the sensation of powder-soft sand between your toes on some of the Indian Ocean's most beautiful beaches. The flight from Arusha or the Serengeti takes just 60–90 minutes, making the transition effortless.</p>

<h2>The Best Beaches on Zanzibar</h2>

<h3>Nungwi Beach — The Iconic North</h3>
<p>Nungwi, on the northern tip of the island, is arguably Zanzibar's most famous beach. Its greatest advantage is minimal tidal variation — while other beaches expose vast tidal flats at low tide, Nungwi remains swimmable throughout the day. The sunsets here are legendary, painting the sky in shades of gold, copper, and violet as traditional dhows glide past. The town has a lively atmosphere with beachfront restaurants, fresh seafood, and a thriving dhow-building tradition you can observe up close.</p>

<h3>Kendwa Beach — Serenity Next Door</h3>
<p>Just south of Nungwi, Kendwa shares the same tide-friendly swimming conditions but is quieter and more relaxed. Several boutique hotels line this stretch of beach, offering a balance of comfort and tranquillity. The full-moon parties at Kendwa Rocks draw a social crowd, but outside those nights, the beach is peaceful.</p>

<h3>Paje Beach — The Kitesurf Capital</h3>
<p>On the southeast coast, Paje is a world-class kitesurfing destination. The shallow, warm lagoon exposed at low tide creates perfect flat-water conditions for beginners, while the reef break further out challenges experts. Even if you don't kite, Paje's wide white beach, bohemian cafes, and the nearby Jozani Forest — home to the endemic red colobus monkey — make it a worthwhile base.</p>

<h3>Matemwe Beach — Remote Luxury</h3>
<p>The northeast coast around Matemwe is where Zanzibar's most exclusive lodges are located. Long, empty stretches of coral-sand beach are backed by palm groves. The offshore Mnemba Atoll offers world-class snorkelling and diving — green turtles, dolphins, and vibrant coral gardens are almost guaranteed. Low tide reveals a vast, photogenic sandbank stretching hundreds of metres into the ocean.</p>

<h3>Stone Town Beaches — Culture Meets Coast</h3>
<p>The beaches immediately around Stone Town are not swimming destinations, but the cultural experience is unrivalled. The historic waterfront, with its carved wooden doors, spice markets, and the former sultan's palace, is a UNESCO World Heritage Site. Sunset cocktails at the Forodhani Gardens night market — where locals grill fresh octopus, Zanzibar pizza, and sugar cane juice — is a must-do experience.</p>

<h2>Where to Stay</h2>
<p>Zanzibar offers accommodation for every budget. <strong>Luxury:</strong> The Residence Zanzibar (Kizimkazi), Mnemba Island Lodge (private island), Zuri Zanzibar (Kendwa), and Baraza Resort (Bwejuu) deliver five-star experiences with private pools, spa treatments, and world-class dining. <strong>Mid-range:</strong> Sunshine Hotel (Nungwi), Pongwe Beach Hotel, and Kilindi Zanzibar offer excellent quality at moderate prices. <strong>Budget:</strong> Paje by Night, Lost & Found Hostel (Stone Town), and numerous local guesthouses provide authentic experiences from $30–80 per night.</p>

<h2>Things to Do Beyond the Beach</h2>
<p><strong>Stone Town walking tour:</strong> Two to three hours exploring the labyrinthine alleyways, the House of Wonders, the Old Fort, and the slave market memorial. <strong>Spice tour:</strong> Visit a working spice farm to taste cloves, vanilla, black pepper, cinnamon, and lemongrass straight from the plant. <strong>Jozani Forest:</strong> A guided walk to see the rare Zanzibar red colobus monkey in its natural mangrove habitat. <strong>Mnemba Atoll snorkelling:</strong> A half-day boat trip to the coral atoll with crystal-clear visibility, turtles, and tropical fish. <strong>Prison Island:</strong> A short boat ride to see giant Aldabra tortoises and learn the island's complex history. <strong>Sunset dhow cruise:</strong> Sail on a traditional wooden boat with drinks as the sun sets over the Indian Ocean.</p>

<h2>Best Time to Visit Zanzibar</h2>
<p>The warmest, driest months are June to October and December to February. These windows align with the main Tanzania safari seasons, making them ideal for combination trips. Water visibility for diving and snorkelling peaks between October and March. The "long rains" of April and May bring humidity and afternoon downpours, but fewer crowds and lower prices.</p>

<h2>How to Combine Safari and Zanzibar</h2>
<p>We recommend three to five nights on Zanzibar after your safari. A typical combination: five nights Serengeti and Ngorongoro, then fly to Zanzibar for four nights of beach, culture, and ocean activities. Direct flights operate from Arusha, Serengeti airstrips, and Kilimanjaro International Airport. Our team handles all internal flights, transfers, and accommodation so you can transition seamlessly from bush to beach.</p>
HTML,
                    'fr' => <<<'HTML'
<h2>Pourquoi Zanzibar après le safari ?</h2>
<p>Un safari en Tanzanie suivi d'un séjour balnéaire à Zanzibar est devenu la combinaison classique. Après la poussière et les réveils matinaux de la brousse, Zanzibar offre une expérience sensorielle complètement différente : l'odeur des clous de girofle dans les ruelles de Stone Town, le son des dhows et la sensation du sable fin. Le vol depuis Arusha ne prend que 60–90 minutes.</p>

<h2>Les meilleures plages de Zanzibar</h2>
<h3>Nungwi — Le nord emblématique</h3>
<p>Sur la pointe nord de l'île, Nungwi reste baignable toute la journée grâce à une variation de marée minimale. Les couchers de soleil sont légendaires.</p>

<h3>Kendwa — La sérénité à côté</h3>
<p>Plus calme que Nungwi, Kendwa partage les mêmes conditions de baignade favorables avec plusieurs hôtels-boutiques.</p>

<h3>Paje — La capitale du kitesurf</h3>
<p>Le lagon peu profond crée des conditions parfaites pour le kitesurf. La forêt de Jozani à proximité abrite le colobus rouge endémique.</p>

<h3>Matemwe — Le luxe reculé</h3>
<p>L'atoll de Mnemba au large offre plongée et snorkelling de classe mondiale — tortues vertes, dauphins et jardins de coraux vibrants.</p>

<h2>Que faire au-delà de la plage</h2>
<p>Stone Town, visite des épices, forêt de Jozani, snorkelling à Mnemba, Prison Island, croisière en dhow au coucher de soleil.</p>

<h2>Comment combiner safari et Zanzibar</h2>
<p>Nous recommandons trois à cinq nuits à Zanzibar après votre safari. Notre équipe gère tous les vols internes et transferts pour une transition fluide de la brousse à la plage.</p>
HTML,
                    'de' => <<<'HTML'
<h2>Warum Sansibar nach der Safari?</h2>
<p>Eine Tansania-Safari gefolgt von einem Strandaufenthalt auf Sansibar ist die klassische ostafrikanische Reisekombination. Nach Staub und frühen Aufbrüchen bietet Sansibar ein völlig anderes Erlebnis: Gewürzduft in Stone Town, Dhow-Segel und pudrig weicher Sand. Der Flug von Arusha dauert nur 60–90 Minuten.</p>

<h2>Die besten Strände Sansibars</h2>
<h3>Nungwi — Der ikonische Norden</h3>
<p>An der Nordspitze der Insel bleibt Nungwi den ganzen Tag schwimmbar. Die Sonnenuntergänge sind legendär.</p>

<h3>Kendwa — Ruhe nebenan</h3>
<p>Ruhiger als Nungwi, mit Boutique-Hotels und den gleichen tidefreundlichen Bedingungen.</p>

<h3>Paje — Die Kitesurf-Hauptstadt</h3>
<p>Weltklasse-Kitesurfen und der nahegelegene Jozani-Wald mit dem endemischen roten Stummelaffen.</p>

<h3>Matemwe — Abgelegener Luxus</h3>
<p>Das vorgelagerte Mnemba-Atoll bietet erstklassiges Schnorcheln und Tauchen.</p>

<h2>Sansibar mit Safari kombinieren</h2>
<p>Wir empfehlen drei bis fünf Nächte auf Sansibar nach Ihrer Safari. Unser Team übernimmt alle Inlandsflüge und Transfers.</p>
HTML,
                    'es' => <<<'HTML'
<h2>¿Por qué Zanzíbar después del safari?</h2>
<p>Un safari en Tanzania seguido de una estancia en las playas de Zanzíbar es la combinación clásica. Después del polvo y los madrugones, Zanzíbar ofrece una experiencia sensorial completamente diferente: el aroma de clavo en Stone Town, las velas de los dhows y la arena fina. El vuelo desde Arusha dura solo 60–90 minutos.</p>

<h2>Las mejores playas de Zanzíbar</h2>
<h3>Nungwi — El norte icónico</h3>
<p>En la punta norte de la isla, Nungwi es apta para el baño todo el día. Las puestas de sol son legendarias.</p>

<h3>Kendwa — Serenidad al lado</h3>
<p>Más tranquila que Nungwi, con hoteles boutique y las mismas condiciones favorables de baño.</p>

<h3>Paje — La capital del kitesurf</h3>
<p>Kitesurf de clase mundial y el cercano bosque de Jozani con monos colobos rojos endémicos.</p>

<h3>Matemwe — Lujo remoto</h3>
<p>El atolón de Mnemba ofrece snorkel y buceo de primer nivel.</p>

<h2>Combinar safari y Zanzíbar</h2>
<p>Recomendamos tres a cinco noches en Zanzíbar después de su safari. Nuestro equipo gestiona todos los vuelos internos y traslados.</p>
HTML,
                ],
            ],
        ];

        foreach ($posts as $postData) {
            Post::updateOrCreate(
                ['slug' => $postData['slug']],
                [
                    'title' => $postData['title'],
                    'excerpt' => $postData['excerpt'],
                    'content' => $postData['content'],
                    'blog_category_id' => $catIds[$postData['category']] ?? null,
                    'author_id' => $authorId,
                    'meta_title' => $postData['meta_title'],
                    'meta_description' => $postData['meta_description'],
                    'meta_keywords' => $postData['meta_keywords'],
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }

        $this->command->info('Created 5 SEO blog posts with 4 categories.');
    }
}
