<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create or update the About Us page ──────────────────
        $page = Page::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => [
                    'en' => 'About Us',
                    'fr' => 'À Propos',
                    'de' => 'Über Uns',
                    'es' => 'Sobre Nosotros',
                ],
                'status'          => 'published',
                'type'            => 'page',
                'template'        => 'default',
                'layout'          => 'full_width',
                'section_spacing' => 'normal',
                'sort_order'      => 5,
                'meta_title'      => 'Lomo Tanzania Safaris | Luxury African Safari Experts',
                'meta_description'=> 'Discover authentic Tanzania safaris with Lomo Tanzania Safaris. Locally owned, expertly guided, and designed to create unforgettable and meaningful travel experiences.',
                'meta_keywords'   => 'Tanzania safari, luxury safari Tanzania, Serengeti safari, Ngorongoro tours, African safari company, local safari operator Tanzania',
                'meta'            => [
                    'schema_type'        => 'TravelAgency',
                    'schema_name'        => 'Lomo Tanzania Safaris',
                    'schema_description' => 'Luxury and tailor-made safari experiences in Tanzania',
                    'schema_area'        => 'Tanzania',
                    'schema_founder'     => 'Erasto',
                ],
            ]
        );

        // Delete existing sections to avoid duplicates on re-run
        $page->pageSections()->delete();

        // ── SECTION 1 — Split Hero (Luxury Intro) ──────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'split_hero',
            'order'         => 0,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'Your Journey. Our Passion.',
                    'fr' => 'Votre Voyage. Notre Passion.',
                    'de' => 'Ihre Reise. Unsere Leidenschaft.',
                    'es' => 'Tu Viaje. Nuestra Pasión.',
                ],
                'subheading' => [
                    'en' => 'A Safari That Changes Lives',
                    'fr' => 'Un Safari Qui Change des Vies',
                    'de' => 'Eine Safari, Die Leben Verändert',
                    'es' => 'Un Safari Que Cambia Vidas',
                ],
                'body' => [
                    'en' => '<p>Lomo Tanzania Safaris is a locally owned and operated safari company based in Tanzania, offering authentic and personalized safari experiences across the country\'s most iconic destinations.</p>',
                    'fr' => '<p>Lomo Tanzania Safaris est une entreprise de safari locale basée en Tanzanie, offrant des expériences de safari authentiques et personnalisées à travers les destinations les plus emblématiques du pays.</p>',
                    'de' => '<p>Lomo Tanzania Safaris ist ein lokal geführtes Safariunternehmen mit Sitz in Tansania, das authentische und personalisierte Safarierlebnisse an den ikonischsten Reisezielen des Landes anbietet.</p>',
                    'es' => '<p>Lomo Tanzania Safaris es una empresa de safari local con sede en Tanzania, que ofrece experiencias de safari auténticas y personalizadas en los destinos más icónicos del país.</p>',
                ],
                'button_text' => [
                    'en' => 'Start Planning Your Safari',
                    'fr' => 'Planifiez Votre Safari',
                    'de' => 'Planen Sie Ihre Safari',
                    'es' => 'Planifica Tu Safari',
                ],
                'button_url' => '/custom-tour',
                'image'      => null, // Set via admin media picker
                'layout'     => 'image_right',
            ],
        ]);

        // ── SECTION 2 — Founder Story (Image + Text) ────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'image_text',
            'order'         => 1,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'Founded on Passion, Built on Experience',
                    'fr' => 'Fondé sur la Passion, Construit sur l\'Expérience',
                    'de' => 'Gegründet aus Leidenschaft, Aufgebaut auf Erfahrung',
                    'es' => 'Fundada en la Pasión, Construida en la Experiencia',
                ],
                'body' => [
                    'en' => '<p>Lomo Tanzania Safaris was founded by Mr. Erasto, a passionate Tanzanian wildlife guide with over 19 years of experience in the safari industry.</p><p>Born and raised in Tanzania, Erasto began his journey in 2003 at Selous Game Reserve. His deep love for wildlife led him to train at Mwewe Training College in Serengeti, where he qualified as a ranger.</p><p>Over the years, he worked with luxury camps across Serengeti, mastering the art of guiding and delivering unforgettable safari experiences.</p><p>During one safari, he met a Dutch couple who shared his vision — together, they built Lomo Tanzania Safaris.</p>',
                    'fr' => '<p>Lomo Tanzania Safaris a été fondé par M. Erasto, un guide passionné de la faune tanzanienne avec plus de 19 ans d\'expérience dans l\'industrie du safari.</p><p>Né et élevé en Tanzanie, Erasto a commencé son parcours en 2003 à la Réserve de Selous. Son amour profond pour la faune l\'a conduit à se former au Mwewe Training College dans le Serengeti, où il a obtenu son diplôme de ranger.</p><p>Au fil des années, il a travaillé avec des camps de luxe à travers le Serengeti, maîtrisant l\'art du guidage et offrant des expériences de safari inoubliables.</p><p>Lors d\'un safari, il a rencontré un couple néerlandais qui partageait sa vision — ensemble, ils ont construit Lomo Tanzania Safaris.</p>',
                    'de' => '<p>Lomo Tanzania Safaris wurde von Herrn Erasto gegründet, einem leidenschaftlichen tansanischen Wildlife-Guide mit über 19 Jahren Erfahrung in der Safari-Branche.</p><p>In Tansania geboren und aufgewachsen, begann Erasto seine Reise 2003 im Selous Game Reserve. Seine tiefe Liebe zur Tierwelt führte ihn zum Mwewe Training College im Serengeti, wo er sich als Ranger qualifizierte.</p><p>Im Laufe der Jahre arbeitete er mit Luxuscamps im Serengeti zusammen und perfektionierte die Kunst des Guidings und das Liefern unvergesslicher Safari-Erlebnisse.</p><p>Während einer Safari traf er ein niederländisches Paar, das seine Vision teilte — gemeinsam bauten sie Lomo Tanzania Safaris auf.</p>',
                    'es' => '<p>Lomo Tanzania Safaris fue fundada por el Sr. Erasto, un apasionado guía de vida silvestre tanzano con más de 19 años de experiencia en la industria del safari.</p><p>Nacido y criado en Tanzania, Erasto comenzó su viaje en 2003 en la Reserva de Selous. Su profundo amor por la vida silvestre lo llevó a formarse en el Mwewe Training College en Serengeti, donde se calificó como ranger.</p><p>A lo largo de los años, trabajó con campamentos de lujo en Serengeti, dominando el arte de guiar y ofrecer experiencias de safari inolvidables.</p><p>Durante un safari, conoció a una pareja holandesa que compartía su visión — juntos, construyeron Lomo Tanzania Safaris.</p>',
                ],
                'image'   => null, // Set via admin media picker
                'layout'  => 'image_left',
            ],
        ]);

        // ── SECTION 3 — Meaning of LOMO (Highlight) ────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'highlight',
            'order'         => 2,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'What LOMO Means',
                    'fr' => 'Ce que LOMO Signifie',
                    'de' => 'Was LOMO Bedeutet',
                    'es' => 'Qué Significa LOMO',
                ],
                'subheading' => [
                    'en' => 'Less on Ourselves, More on Others',
                    'fr' => 'Moins pour Nous, Plus pour les Autres',
                    'de' => 'Weniger für Uns, Mehr für Andere',
                    'es' => 'Menos para Nosotros, Más para los Demás',
                ],
                'body' => [
                    'en' => '<p>This philosophy defines everything we do. We believe tourism should create meaningful change — not only for travelers, but also for local communities. Every safari supports:</p>',
                    'fr' => '<p>Cette philosophie définit tout ce que nous faisons. Nous croyons que le tourisme devrait créer un changement significatif — pas seulement pour les voyageurs, mais aussi pour les communautés locales. Chaque safari soutient :</p>',
                    'de' => '<p>Diese Philosophie bestimmt alles, was wir tun. Wir glauben, dass Tourismus bedeutungsvolle Veränderungen schaffen sollte — nicht nur für Reisende, sondern auch für lokale Gemeinschaften. Jede Safari unterstützt:</p>',
                    'es' => '<p>Esta filosofía define todo lo que hacemos. Creemos que el turismo debe crear un cambio significativo — no solo para los viajeros, sino también para las comunidades locales. Cada safari apoya:</p>',
                ],
                'items' => [
                    [
                        'title' => [
                            'en' => 'Education',
                            'fr' => 'Éducation',
                            'de' => 'Bildung',
                            'es' => 'Educación',
                        ],
                    ],
                    [
                        'title' => [
                            'en' => 'Healthcare',
                            'fr' => 'Santé',
                            'de' => 'Gesundheitswesen',
                            'es' => 'Salud',
                        ],
                    ],
                    [
                        'title' => [
                            'en' => 'Community Development',
                            'fr' => 'Développement Communautaire',
                            'de' => 'Gemeinschaftsentwicklung',
                            'es' => 'Desarrollo Comunitario',
                        ],
                    ],
                ],
                'bg_color' => '#083321',
            ],
        ]);

        // ── SECTION 4 — Mission & Vision (Two Column Feature) ──
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'two_column_feature',
            'order'         => 3,
            'is_active'     => true,
            'data'          => [
                'columns' => [
                    [
                        'title' => [
                            'en' => 'Our Mission',
                            'fr' => 'Notre Mission',
                            'de' => 'Unsere Mission',
                            'es' => 'Nuestra Misión',
                        ],
                        'body' => [
                            'en' => '<p>To deliver unforgettable safari experiences that connect people with nature, culture, and community — while promoting sustainable tourism and empowering local people.</p>',
                            'fr' => '<p>Offrir des expériences de safari inoubliables qui connectent les gens avec la nature, la culture et la communauté — tout en promouvant le tourisme durable et en autonomisant les populations locales.</p>',
                            'de' => '<p>Unvergessliche Safari-Erlebnisse zu liefern, die Menschen mit Natur, Kultur und Gemeinschaft verbinden — und gleichzeitig nachhaltigen Tourismus fördern und lokale Menschen stärken.</p>',
                            'es' => '<p>Ofrecer experiencias de safari inolvidables que conectan a las personas con la naturaleza, la cultura y la comunidad — mientras promueven el turismo sostenible y empoderan a las personas locales.</p>',
                        ],
                        'icon' => 'target',
                    ],
                    [
                        'title' => [
                            'en' => 'Our Vision',
                            'fr' => 'Notre Vision',
                            'de' => 'Unsere Vision',
                            'es' => 'Nuestra Visión',
                        ],
                        'body' => [
                            'en' => '<p>To become Tanzania\'s most trusted and socially responsible safari company, known for personalized service and meaningful travel experiences.</p>',
                            'fr' => '<p>Devenir l\'entreprise de safari la plus fiable et socialement responsable de Tanzanie, reconnue pour son service personnalisé et ses expériences de voyage significatives.</p>',
                            'de' => '<p>Das vertrauenswürdigste und sozial verantwortungsvollste Safariunternehmen Tansanias zu werden, bekannt für personalisierten Service und bedeutungsvolle Reiseerlebnisse.</p>',
                            'es' => '<p>Convertirnos en la empresa de safari más confiable y socialmente responsable de Tanzania, conocida por su servicio personalizado y experiencias de viaje significativas.</p>',
                        ],
                        'icon' => 'eye',
                    ],
                ],
                'bg_color' => '#F9F7F3',
            ],
        ]);

        // ── SECTION 5 — Why Choose Us (Icon Features) ───────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'icon_features',
            'order'         => 4,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'Why Choose Us',
                    'fr' => 'Pourquoi Nous Choisir',
                    'de' => 'Warum Uns Wählen',
                    'es' => 'Por Qué Elegirnos',
                ],
                'subheading' => [
                    'en' => 'Five reasons travelers trust Lomo Tanzania Safaris',
                    'fr' => 'Cinq raisons pour lesquelles les voyageurs font confiance à Lomo Tanzania Safaris',
                    'de' => 'Fünf Gründe, warum Reisende Lomo Tanzania Safaris vertrauen',
                    'es' => 'Cinco razones por las que los viajeros confían en Lomo Tanzania Safaris',
                ],
                'items' => [
                    [
                        'title' => [
                            'en' => 'Locally Owned Experts',
                            'fr' => 'Experts Locaux',
                            'de' => 'Lokale Experten',
                            'es' => 'Expertos Locales',
                        ],
                        'description' => [
                            'en' => 'Born and raised in Tanzania, our team knows every trail, every animal behavior, and every hidden gem across the country.',
                            'fr' => 'Nés et élevés en Tanzanie, notre équipe connaît chaque sentier, chaque comportement animal et chaque joyau caché du pays.',
                            'de' => 'In Tansania geboren und aufgewachsen, kennt unser Team jeden Pfad, jedes Tierverhalten und jedes versteckte Juwel des Landes.',
                            'es' => 'Nacidos y criados en Tanzania, nuestro equipo conoce cada sendero, cada comportamiento animal y cada joya oculta del país.',
                        ],
                        'icon' => 'shield',
                    ],
                    [
                        'title' => [
                            'en' => 'Tailor-Made Safaris',
                            'fr' => 'Safaris Sur Mesure',
                            'de' => 'Maßgeschneiderte Safaris',
                            'es' => 'Safaris a Medida',
                        ],
                        'description' => [
                            'en' => 'Every safari is designed around your interests, pace, and budget. No cookie-cutter itineraries — only personalized experiences.',
                            'fr' => 'Chaque safari est conçu autour de vos intérêts, votre rythme et votre budget. Pas d\'itinéraires standardisés — uniquement des expériences personnalisées.',
                            'de' => 'Jede Safari wird nach Ihren Interessen, Ihrem Tempo und Ihrem Budget gestaltet. Keine Standardreisen — nur personalisierte Erlebnisse.',
                            'es' => 'Cada safari está diseñado en torno a sus intereses, ritmo y presupuesto. Sin itinerarios estándar — solo experiencias personalizadas.',
                        ],
                        'icon' => 'map',
                    ],
                    [
                        'title' => [
                            'en' => 'Community Impact',
                            'fr' => 'Impact Communautaire',
                            'de' => 'Gemeinschaftliche Wirkung',
                            'es' => 'Impacto Comunitario',
                        ],
                        'description' => [
                            'en' => 'Every booking directly supports education, healthcare, and development in local Tanzanian communities.',
                            'fr' => 'Chaque réservation soutient directement l\'éducation, la santé et le développement des communautés locales tanzaniennes.',
                            'de' => 'Jede Buchung unterstützt direkt Bildung, Gesundheitswesen und Entwicklung in lokalen tansanischen Gemeinden.',
                            'es' => 'Cada reserva apoya directamente la educación, la salud y el desarrollo en las comunidades locales de Tanzania.',
                        ],
                        'icon' => 'heart',
                    ],
                    [
                        'title' => [
                            'en' => 'Attention to Detail',
                            'fr' => 'Attention aux Détails',
                            'de' => 'Liebe zum Detail',
                            'es' => 'Atención al Detalle',
                        ],
                        'description' => [
                            'en' => 'From your first inquiry to your last sunset game drive, we ensure every detail is taken care of with precision and warmth.',
                            'fr' => 'De votre première demande à votre dernier safari au coucher du soleil, nous veillons à ce que chaque détail soit pris en charge avec précision et chaleur.',
                            'de' => 'Von Ihrer ersten Anfrage bis zu Ihrer letzten Pirschfahrt bei Sonnenuntergang sorgen wir dafür, dass jedes Detail mit Präzision und Wärme erledigt wird.',
                            'es' => 'Desde su primera consulta hasta su último safari al atardecer, nos aseguramos de que cada detalle sea atendido con precisión y calidez.',
                        ],
                        'icon' => 'camera',
                    ],
                    [
                        'title' => [
                            'en' => 'Sustainable Tourism',
                            'fr' => 'Tourisme Durable',
                            'de' => 'Nachhaltiger Tourismus',
                            'es' => 'Turismo Sostenible',
                        ],
                        'description' => [
                            'en' => 'We are committed to protecting Tanzania\'s wildlife and ecosystems through responsible and sustainable travel practices.',
                            'fr' => 'Nous nous engageons à protéger la faune et les écosystèmes de la Tanzanie grâce à des pratiques de voyage responsables et durables.',
                            'de' => 'Wir setzen uns für den Schutz der Tierwelt und Ökosysteme Tansanias durch verantwortungsvolle und nachhaltige Reisepraktiken ein.',
                            'es' => 'Estamos comprometidos a proteger la vida silvestre y los ecosistemas de Tanzania a través de prácticas de viaje responsables y sostenibles.',
                        ],
                        'icon' => 'globe',
                    ],
                ],
            ],
        ]);

        // ── SECTION 6 — Destinations (Destination Grid) ────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'destinations',
            'order'         => 5,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'Where We Take You',
                    'fr' => 'Où Nous Vous Emmenons',
                    'de' => 'Wohin Wir Sie Bringen',
                    'es' => 'Adónde Te Llevamos',
                ],
                'subheading' => [
                    'en' => 'Explore Tanzania\'s most iconic destinations with expert local guides',
                    'fr' => 'Explorez les destinations les plus emblématiques de Tanzanie avec des guides locaux experts',
                    'de' => 'Erkunden Sie Tansanias ikonischste Reiseziele mit erfahrenen lokalen Guides',
                    'es' => 'Explora los destinos más icónicos de Tanzania con guías locales expertos',
                ],
                'count' => 6,
            ],
        ]);

        // ── SECTION 7 — Experiences (Experience Grid) ───────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'experience_grid',
            'order'         => 6,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'Experiences We Offer',
                    'fr' => 'Expériences Que Nous Offrons',
                    'de' => 'Erlebnisse, Die Wir Anbieten',
                    'es' => 'Experiencias Que Ofrecemos',
                ],
                'subheading' => [
                    'en' => 'From luxury game drives to cultural immersion — there\'s something for everyone',
                    'fr' => 'Des safaris de luxe à l\'immersion culturelle — il y en a pour tous les goûts',
                    'de' => 'Von Luxus-Pirschfahrten bis hin zur kulturellen Immersion — für jeden ist etwas dabei',
                    'es' => 'Desde safaris de lujo hasta inmersión cultural — hay algo para todos',
                ],
                'items' => [
                    [
                        'title' => [
                            'en' => 'Luxury Safaris',
                            'fr' => 'Safaris de Luxe',
                            'de' => 'Luxus-Safaris',
                            'es' => 'Safaris de Lujo',
                        ],
                        'description' => [
                            'en' => 'Premium lodges, private vehicles, and exclusive game viewing in Serengeti and beyond.',
                            'fr' => 'Lodges premium, véhicules privés et observation exclusive de la faune dans le Serengeti et au-delà.',
                            'de' => 'Premium-Lodges, private Fahrzeuge und exklusive Wildbeobachtung im Serengeti und darüber hinaus.',
                            'es' => 'Lodges premium, vehículos privados y avistamiento exclusivo de animales en Serengeti y más allá.',
                        ],
                        'icon' => 'diamond',
                        'image' => null,
                    ],
                    [
                        'title' => [
                            'en' => 'Honeymoon Trips',
                            'fr' => 'Voyages de Noces',
                            'de' => 'Flitterwochen-Reisen',
                            'es' => 'Viajes de Luna de Miel',
                        ],
                        'description' => [
                            'en' => 'Romantic safari and beach getaways designed for couples seeking unforgettable moments.',
                            'fr' => 'Escapades romantiques safari et plage conçues pour les couples en quête de moments inoubliables.',
                            'de' => 'Romantische Safari- und Strandausflüge für Paare, die unvergessliche Momente suchen.',
                            'es' => 'Escapadas románticas de safari y playa diseñadas para parejas que buscan momentos inolvidables.',
                        ],
                        'icon' => 'heart',
                        'image' => null,
                    ],
                    [
                        'title' => [
                            'en' => 'Family Adventures',
                            'fr' => 'Aventures en Famille',
                            'de' => 'Familienabenteuer',
                            'es' => 'Aventuras Familiares',
                        ],
                        'description' => [
                            'en' => 'Kid-friendly safaris with comfortable pacing, engaging activities, and family-sized accommodations.',
                            'fr' => 'Safaris adaptés aux enfants avec un rythme confortable, des activités engageantes et des hébergements familiaux.',
                            'de' => 'Kinderfreundliche Safaris mit komfortablem Tempo, spannenden Aktivitäten und familiengerechten Unterkünften.',
                            'es' => 'Safaris aptos para niños con ritmo cómodo, actividades atractivas y alojamientos familiares.',
                        ],
                        'icon' => 'users',
                        'image' => null,
                    ],
                    [
                        'title' => [
                            'en' => 'Cultural Tours',
                            'fr' => 'Tours Culturels',
                            'de' => 'Kulturelle Touren',
                            'es' => 'Tours Culturales',
                        ],
                        'description' => [
                            'en' => 'Visit Maasai villages, local markets, and heritage sites for an authentic connection to Tanzanian culture.',
                            'fr' => 'Visitez les villages Maasaï, les marchés locaux et les sites patrimoniaux pour une connexion authentique à la culture tanzanienne.',
                            'de' => 'Besuchen Sie Maasai-Dörfer, lokale Märkte und Kulturerbestätten für eine authentische Verbindung zur tansanischen Kultur.',
                            'es' => 'Visite aldeas Maasai, mercados locales y sitios patrimoniales para una conexión auténtica con la cultura tanzana.',
                        ],
                        'icon' => 'globe',
                        'image' => null,
                    ],
                    [
                        'title' => [
                            'en' => 'Mountain Climbing',
                            'fr' => 'Escalade en Montagne',
                            'de' => 'Bergsteigen',
                            'es' => 'Montañismo',
                        ],
                        'description' => [
                            'en' => 'Conquer Kilimanjaro with expert mountain guides, proper acclimatization, and full logistical support.',
                            'fr' => 'Conquérez le Kilimandjaro avec des guides de montagne experts, une acclimatation appropriée et un soutien logistique complet.',
                            'de' => 'Bezwingen Sie den Kilimandscharo mit erfahrenen Bergführern, ordnungsgemäßer Akklimatisierung und voller logistischer Unterstützung.',
                            'es' => 'Conquiste el Kilimanjaro con guías de montaña expertos, aclimatación adecuada y soporte logístico completo.',
                        ],
                        'icon' => 'mountain',
                        'image' => null,
                    ],
                ],
            ],
        ]);

        // ── SECTION 8 — Final CTA Banner ────────────────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'cta_banner',
            'order'         => 7,
            'is_active'     => true,
            'data'          => [
                'heading' => [
                    'en' => 'Join a Journey That Changes Lives',
                    'fr' => 'Rejoignez un Voyage Qui Change des Vies',
                    'de' => 'Werden Sie Teil einer Reise, Die Leben Verändert',
                    'es' => 'Únete a un Viaje Que Cambia Vidas',
                ],
                'subheading' => [
                    'en' => 'Every safari is more than a trip — it\'s a connection, an experience, and an impact.',
                    'fr' => 'Chaque safari est plus qu\'un voyage — c\'est une connexion, une expérience et un impact.',
                    'de' => 'Jede Safari ist mehr als eine Reise — es ist eine Verbindung, ein Erlebnis und eine Wirkung.',
                    'es' => 'Cada safari es más que un viaje — es una conexión, una experiencia y un impacto.',
                ],
                'button_text' => [
                    'en' => 'Start Planning',
                    'fr' => 'Commencer à Planifier',
                    'de' => 'Jetzt Planen',
                    'es' => 'Empezar a Planificar',
                ],
                'button_url' => '/custom-tour',
                'button2_text' => [
                    'en' => 'Explore Safaris',
                    'fr' => 'Explorer les Safaris',
                    'de' => 'Safaris Erkunden',
                    'es' => 'Explorar Safaris',
                ],
                'button2_url' => '/safaris',
                'bg_color' => '#083321',
            ],
        ]);

        $this->command?->info('✓ About Us page seeded with 8 sections.');
    }
}
