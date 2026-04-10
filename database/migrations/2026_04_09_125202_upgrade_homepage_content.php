<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $homePage = DB::table('pages')->where('is_homepage', true)->first();
        if (! $homePage) {
            return;
        }
        $pageId = $homePage->id;

        // ─── 1. UPDATE "ABOUT / INTRO" (split_hero, id 17) ──────────────
        DB::table('page_sections')->where('id', 17)->update([
            'order' => 1,
            'data'  => json_encode([
                'heading' => [
                    'en' => 'Meet Lomo Tanzania Safaris',
                    'fr' => 'Découvrez Lomo Tanzania Safaris',
                    'de' => 'Lernen Sie Lomo Tanzania Safaris Kennen',
                    'es' => 'Conozca Lomo Tanzania Safaris',
                ],
                'subheading' => [
                    'en' => 'Less on Ourselves, More on Others',
                    'fr' => 'Moins pour Nous, Plus pour les Autres',
                    'de' => 'Weniger für Uns, Mehr für Andere',
                    'es' => 'Menos para Nosotros, Más para los Demás',
                ],
                'body' => [
                    'en' => '<p>Lomo Tanzania Safaris specializes in unforgettable journeys across Tanzania\'s Northern Safari Circuit, including Tarangire, Serengeti, Ngorongoro Crater, and Lake Manyara. Each safari is thoughtfully designed to deliver authentic wildlife encounters, cultural depth, and exceptional comfort.</p><p>From your first inquiry to your final day in Tanzania, our dedicated team ensures every detail is seamless. Whether you\'re witnessing the Great Migration, exploring remote landscapes, or relaxing on the beaches of Zanzibar, every experience is crafted to be truly unforgettable.</p><p>The name <strong>LOMO</strong> stands for "Less on Ourselves, More on Others." This principle guides our commitment to giving back through education, healthcare, and improving local living standards.</p>',
                    'fr' => '<p>Lomo Tanzania Safaris se spécialise dans les voyages inoubliables à travers le circuit safari nord de la Tanzanie, notamment le Tarangire, le Serengeti, le Cratère du Ngorongoro et le Lac Manyara. Chaque safari est soigneusement conçu pour offrir des rencontres authentiques avec la faune, une profondeur culturelle et un confort exceptionnel.</p><p>De votre première demande à votre dernier jour en Tanzanie, notre équipe dévouée veille à ce que chaque détail soit parfait. Que vous assistiez à la Grande Migration, exploriez des paysages reculés ou vous détendiez sur les plages de Zanzibar, chaque expérience est conçue pour être véritablement inoubliable.</p><p>Le nom <strong>LOMO</strong> signifie «Less on Ourselves, More on Others» (Moins pour Nous, Plus pour les Autres). Ce principe guide notre engagement à redonner à travers l\'éducation, la santé et l\'amélioration du niveau de vie local.</p>',
                    'de' => '<p>Lomo Tanzania Safaris ist spezialisiert auf unvergessliche Reisen durch Tansanias nördlichen Safari-Circuit, darunter Tarangire, Serengeti, Ngorongoro-Krater und Lake Manyara. Jede Safari ist durchdacht gestaltet, um authentische Wildtierbegegnungen, kulturelle Tiefe und außergewöhnlichen Komfort zu bieten.</p><p>Von Ihrer ersten Anfrage bis zu Ihrem letzten Tag in Tansania sorgt unser engagiertes Team dafür, dass jedes Detail nahtlos ist. Ob Sie die Große Migration erleben, abgelegene Landschaften erkunden oder an den Stränden von Sansibar entspannen – jedes Erlebnis ist so gestaltet, dass es unvergesslich wird.</p><p>Der Name <strong>LOMO</strong> steht für „Less on Ourselves, More on Others". Dieses Prinzip leitet unser Engagement, durch Bildung, Gesundheitsversorgung und die Verbesserung der lokalen Lebensstandards etwas zurückzugeben.</p>',
                    'es' => '<p>Lomo Tanzania Safaris se especializa en viajes inolvidables por el Circuito Safari del Norte de Tanzania, incluyendo Tarangire, Serengeti, Cráter del Ngorongoro y Lago Manyara. Cada safari está cuidadosamente diseñado para ofrecer encuentros auténticos con la vida silvestre, profundidad cultural y un confort excepcional.</p><p>Desde su primera consulta hasta su último día en Tanzania, nuestro equipo dedicado se asegura de que cada detalle sea perfecto. Ya sea que presencie la Gran Migración, explore paisajes remotos o se relaje en las playas de Zanzíbar, cada experiencia está diseñada para ser verdaderamente inolvidable.</p><p>El nombre <strong>LOMO</strong> significa "Less on Ourselves, More on Others" (Menos para Nosotros, Más para los Demás). Este principio guía nuestro compromiso de retribuir a través de la educación, la salud y la mejora del nivel de vida local.</p>',
                ],
                'button_text' => [
                    'en' => 'Plan Your Safari',
                    'fr' => 'Planifiez Votre Safari',
                    'de' => 'Planen Sie Ihre Safari',
                    'es' => 'Planifique Su Safari',
                ],
                'button_url' => '/en/plan-safari',
                'layout' => 'image_right',
                'image' => 'safaris/Sy6BdtdamfKGcVioraGTB6dbwtjfjZwRwVyYibru.png',
                'bg_color' => '#083321',
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // ─── 2. UPDATE "WHY CHOOSE US" (icon_features, id 14) ───────────
        DB::table('page_sections')->where('id', 14)->update([
            'order' => 2,
            'data'  => json_encode([
                'heading' => [
                    'en' => 'Why Travel With Lomo Tanzania Safaris',
                    'fr' => 'Pourquoi Voyager Avec Lomo Tanzania Safaris',
                    'de' => 'Warum Mit Lomo Tanzania Safaris Reisen',
                    'es' => 'Por Qué Viajar Con Lomo Tanzania Safaris',
                ],
                'subheading' => [
                    'en' => 'Crafting unforgettable luxury safari experiences with purpose and passion',
                    'fr' => 'Créer des expériences de safari de luxe inoubliables avec passion et engagement',
                    'de' => 'Unvergessliche Luxus-Safari-Erlebnisse mit Leidenschaft und Sinn gestalten',
                    'es' => 'Creando experiencias de safari de lujo inolvidables con propósito y pasión',
                ],
                'columns' => '3',
                'bg_color' => '#000000',
                'items' => [
                    [
                        'icon' => 'heart',
                        'title' => [
                            'en' => 'Authentic & Personalized Safaris',
                            'fr' => 'Safaris Authentiques et Personnalisés',
                            'de' => 'Authentische & Personalisierte Safaris',
                            'es' => 'Safaris Auténticos y Personalizados',
                        ],
                        'description' => [
                            'en' => 'Every journey is tailor-made to match your travel style, ensuring a private and meaningful safari experience.',
                            'fr' => 'Chaque voyage est conçu sur mesure selon votre style de voyage, garantissant une expérience de safari privée et significative.',
                            'de' => 'Jede Reise wird maßgeschneidert nach Ihrem Reisestil gestaltet und gewährleistet ein privates und bedeutungsvolles Safari-Erlebnis.',
                            'es' => 'Cada viaje está hecho a medida para coincidir con su estilo de viaje, garantizando una experiencia de safari privada y significativa.',
                        ],
                    ],
                    [
                        'icon' => 'globe',
                        'title' => [
                            'en' => 'Passion With a Purpose',
                            'fr' => 'Passion Avec un But',
                            'de' => 'Leidenschaft Mit Einem Zweck',
                            'es' => 'Pasión Con Propósito',
                        ],
                        'description' => [
                            'en' => 'Your journey supports local communities through our commitment to education, healthcare, and sustainable development.',
                            'fr' => 'Votre voyage soutient les communautés locales grâce à notre engagement envers l\'éducation, la santé et le développement durable.',
                            'de' => 'Ihre Reise unterstützt lokale Gemeinschaften durch unser Engagement für Bildung, Gesundheitsversorgung und nachhaltige Entwicklung.',
                            'es' => 'Su viaje apoya a las comunidades locales a través de nuestro compromiso con la educación, la salud y el desarrollo sostenible.',
                        ],
                    ],
                    [
                        'icon' => 'shield',
                        'title' => [
                            'en' => 'Experienced Local Team',
                            'fr' => 'Équipe Locale Expérimentée',
                            'de' => 'Erfahrenes Lokales Team',
                            'es' => 'Equipo Local Experimentado',
                        ],
                        'description' => [
                            'en' => 'With over 19 years of experience, our expert guides ensure safety, comfort, and unforgettable moments on every safari.',
                            'fr' => 'Avec plus de 19 ans d\'expérience, nos guides experts assurent sécurité, confort et moments inoubliables à chaque safari.',
                            'de' => 'Mit über 19 Jahren Erfahrung sorgen unsere erfahrenen Guides für Sicherheit, Komfort und unvergessliche Momente bei jeder Safari.',
                            'es' => 'Con más de 19 años de experiencia, nuestros guías expertos garantizan seguridad, comodidad y momentos inolvidables en cada safari.',
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // ─── 3. UPDATE "DESTINATIONS" (destination_showcase, id 16) ─────
        DB::table('page_sections')->where('id', 16)->update([
            'order' => 4,
            'data'  => json_encode([
                'heading' => [
                    'en' => 'Top Safari & Adventure Destinations in Tanzania',
                    'fr' => 'Meilleures Destinations Safari et Aventure en Tanzanie',
                    'de' => 'Top Safari- und Abenteuer-Reiseziele in Tansania',
                    'es' => 'Mejores Destinos de Safari y Aventura en Tanzania',
                ],
                'subheading' => [
                    'en' => 'Explore Tanzania\'s most iconic landscapes, from wildlife-rich plains to towering mountains and breathtaking natural wonders.',
                    'fr' => 'Explorez les paysages les plus emblématiques de la Tanzanie, des plaines riches en faune aux montagnes majestueuses et merveilles naturelles.',
                    'de' => 'Entdecken Sie Tansanias ikonischste Landschaften, von wildreichen Ebenen bis zu hohen Bergen und atemberaubenden Naturwundern.',
                    'es' => 'Explore los paisajes más icónicos de Tanzania, desde llanuras ricas en vida silvestre hasta montañas imponentes y maravillas naturales.',
                ],
                'count' => 6,
                'featured_only' => '1',
                'columns' => '3',
                'category_filter' => null,
                'show_rating' => '1',
                'slider_autoplay' => '1',
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // ─── 4. UPDATE "FEATURED TOURS" (safari_grid, id 15) ────────────
        DB::table('page_sections')->where('id', 15)->update([
            'order' => 3,
            'data'  => json_encode([
                'heading' => [
                    'en' => 'Featured Safari Journeys',
                    'fr' => 'Safaris en Vedette',
                    'de' => 'Ausgewählte Safari-Reisen',
                    'es' => 'Safaris Destacados',
                ],
                'subheading' => [
                    'en' => 'Our most sought-after luxury experiences across Tanzania',
                    'fr' => 'Nos expériences de luxe les plus recherchées en Tanzanie',
                    'de' => 'Unsere gefragtesten Luxuserlebnisse in Tansania',
                    'es' => 'Nuestras experiencias de lujo más buscadas en Tanzania',
                ],
                'count' => 6,
                'featured_only' => '1',
                'columns' => '3',
                'category_filter' => null,
                'show_rating' => '1',
                'slider_autoplay' => '1',
            ], JSON_UNESCAPED_UNICODE),
        ]);

        // ─── 5. ADD "EXPERIENCES" SECTION ────────────────────────────────
        // Check if it already exists
        $existing = DB::table('page_sections')
            ->where('page_id', $pageId)
            ->where('section_type', 'experience_grid')
            ->first();

        if (! $existing) {
            DB::table('page_sections')->insert([
                'page_id'      => $pageId,
                'section_type' => 'experience_grid',
                'order'        => 5,
                'is_active'    => true,
                'data'         => json_encode([
                    'heading' => [
                        'en' => 'Best Experiences in Tanzania',
                        'fr' => 'Meilleures Expériences en Tanzanie',
                        'de' => 'Beste Erlebnisse in Tansania',
                        'es' => 'Mejores Experiencias en Tanzania',
                    ],
                    'subheading' => [
                        'en' => 'Discover the heart of Africa through unforgettable adventures. From witnessing the Great Migration to sunrise game drives across endless savannahs, every experience connects you deeply with nature.',
                        'fr' => 'Découvrez le cœur de l\'Afrique à travers des aventures inoubliables. De la Grande Migration aux safaris au lever du soleil dans les savanes infinies, chaque expérience vous connecte profondément à la nature.',
                        'de' => 'Entdecken Sie das Herz Afrikas durch unvergessliche Abenteuer. Von der Großen Migration bis zu Pirschfahrten bei Sonnenaufgang durch endlose Savannen – jedes Erlebnis verbindet Sie tief mit der Natur.',
                        'es' => 'Descubra el corazón de África a través de aventuras inolvidables. Desde presenciar la Gran Migración hasta safaris al amanecer por sabanas interminables, cada experiencia lo conecta profundamente con la naturaleza.',
                    ],
                    'items' => [
                        [
                            'icon' => 'compass',
                            'title' => ['en' => 'Wildlife Safari', 'fr' => 'Safari Animalier', 'de' => 'Wildlife Safari', 'es' => 'Safari de Vida Silvestre'],
                            'description' => ['en' => 'Witness the Big Five and countless species in their natural habitats across Tanzania\'s world-renowned national parks.', 'fr' => 'Observez les Big Five et d\'innombrables espèces dans leurs habitats naturels à travers les parcs nationaux mondialement connus de Tanzanie.', 'de' => 'Erleben Sie die Big Five und unzählige Arten in ihren natürlichen Lebensräumen in Tansanias weltbekannten Nationalparks.', 'es' => 'Observe los Cinco Grandes y numerosas especies en sus hábitats naturales en los parques nacionales de fama mundial de Tanzania.'],
                            'image' => null,
                            'link' => '/en/experiences',
                        ],
                        [
                            'icon' => 'globe',
                            'title' => ['en' => 'Great Wildebeest Migration', 'fr' => 'Grande Migration des Gnous', 'de' => 'Große Gnu-Wanderung', 'es' => 'Gran Migración del Ñu'],
                            'description' => ['en' => 'Experience one of nature\'s most spectacular events as millions of wildebeest traverse the Serengeti plains.', 'fr' => 'Vivez l\'un des événements les plus spectaculaires de la nature alors que des millions de gnous traversent les plaines du Serengeti.', 'de' => 'Erleben Sie eines der spektakulärsten Naturereignisse, wenn Millionen von Gnus die Serengeti-Ebenen durchqueren.', 'es' => 'Experimente uno de los eventos más espectaculares de la naturaleza mientras millones de ñus atraviesan las llanuras del Serengeti.'],
                            'image' => null,
                            'link' => '/en/experiences',
                        ],
                        [
                            'icon' => 'camera',
                            'title' => ['en' => 'Hot Air Balloon Safari', 'fr' => 'Safari en Montgolfière', 'de' => 'Heißluftballon-Safari', 'es' => 'Safari en Globo Aerostático'],
                            'description' => ['en' => 'Soar above the Serengeti at sunrise for a breathtaking bird\'s-eye view of the African wilderness.', 'fr' => 'Survolez le Serengeti au lever du soleil pour une vue panoramique à couper le souffle de la nature africaine.', 'de' => 'Schweben Sie bei Sonnenaufgang über die Serengeti für einen atemberaubenden Panoramablick auf die afrikanische Wildnis.', 'es' => 'Sobrevuele el Serengeti al amanecer para una vista panorámica impresionante de la naturaleza africana.'],
                            'image' => null,
                            'link' => '/en/experiences',
                        ],
                        [
                            'icon' => 'shield',
                            'title' => ['en' => 'Hiking & Trekking Adventures', 'fr' => 'Aventures de Randonnée et Trekking', 'de' => 'Wander- und Trekking-Abenteuer', 'es' => 'Aventuras de Senderismo y Trekking'],
                            'description' => ['en' => 'Conquer Mount Kilimanjaro, explore the Ngorongoro highlands, or trek through lush rainforests teeming with life.', 'fr' => 'Conquérez le Mont Kilimandjaro, explorez les hauts plateaux du Ngorongoro, ou randonnez à travers des forêts tropicales luxuriantes.', 'de' => 'Bezwingen Sie den Kilimandscharo, erkunden Sie das Ngorongoro-Hochland oder wandern Sie durch üppige Regenwälder voller Leben.', 'es' => 'Conquiste el Monte Kilimanjaro, explore las tierras altas del Ngorongoro, o haga trekking por selvas tropicales exuberantes.'],
                            'image' => null,
                            'link' => '/en/experiences',
                        ],
                    ],
                ], JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ─── 6. UPDATE REMAINING SECTION ORDERS ─────────────────────────
        // tripadvisor_reviews (id 36) → order 6
        DB::table('page_sections')->where('id', 36)->update(['order' => 6]);
        // image_gallery (id 19) → order 7
        DB::table('page_sections')->where('id', 19)->update(['order' => 7]);
        // cta_banner (id 20) → order 8
        DB::table('page_sections')->where('id', 20)->update(['order' => 8]);

        // ─── 7. DEACTIVATE BLOG SECTION ─────────────────────────────────
        DB::table('page_sections')->where('id', 21)->update(['is_active' => false]);

        // ─── 8. UPDATE HOMEPAGE SEO META ─────────────────────────────────
        DB::table('pages')->where('id', $pageId)->update([
            'meta_title'       => 'Luxury Tanzania Safaris | Serengeti, Ngorongoro & Zanzibar | Lomo Tanzania Safaris',
            'meta_description' => 'Discover unforgettable Tanzania safaris with Lomo Tanzania Safaris. Explore Serengeti, Ngorongoro Crater, Tarangire, and Zanzibar with tailor-made luxury experiences.',
            'meta_keywords'    => 'Tanzania safari, Serengeti safari, Ngorongoro crater safari, luxury safari Tanzania, Zanzibar holidays, African safari tours',
        ]);
    }

    public function down(): void
    {
        // Revert blog section
        DB::table('page_sections')->where('id', 21)->update(['is_active' => true]);

        // Remove experiences section
        $homePage = DB::table('pages')->where('is_homepage', true)->first();
        if ($homePage) {
            DB::table('page_sections')
                ->where('page_id', $homePage->id)
                ->where('section_type', 'experience_grid')
                ->delete();
        }
    }
};
