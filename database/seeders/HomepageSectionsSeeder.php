<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class HomepageSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('is_homepage', true)->first();

        if (!$page) {
            $page = Page::create([
                'title'       => ['en' => 'Home', 'fr' => 'Accueil', 'de' => 'Startseite', 'es' => 'Inicio'],
                'slug'        => 'homepage',
                'type'        => 'page',
                'is_homepage' => true,
                'status'      => 'published',
                'template'    => 'default',
                'layout'      => 'full_width',
                'section_spacing' => 'normal',
            ]);
        }

        // Clear existing sections and hero slides for this page
        $existingSectionIds = $page->pageSections()->pluck('id');
        HeroSlide::whereIn('page_section_id', $existingSectionIds)->delete();
        $page->pageSections()->delete();

        // ─── SECTION 1: HERO SLIDER ─────────────────────────────
        $hero = PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'hero',
            'order'         => 0,
            'is_active'     => true,
            'data'          => [
                'slider_autoplay'  => '1',
                'slider_interval'  => 6000,
            ],
        ]);

        HeroSlide::create([
            'page_section_id' => $hero->id,
            'order'           => 0,
            'label'           => ['en' => 'LUXURY COLLECTION', 'fr' => 'COLLECTION LUXE', 'de' => 'LUXUS-KOLLEKTION', 'es' => 'COLECCIÓN DE LUJO'],
            'title'           => ['en' => 'Discover the Wild Heart of Tanzania', 'fr' => 'Découvrez le Cœur Sauvage de la Tanzanie', 'de' => 'Entdecken Sie das Wilde Herz Tansanias', 'es' => 'Descubra el Corazón Salvaje de Tanzania'],
            'subtitle'        => ['en' => 'Handcrafted luxury safaris through the Serengeti, Ngorongoro Crater, and beyond', 'fr' => 'Safaris de luxe sur mesure à travers le Serengeti, le cratère du Ngorongoro et au-delà', 'de' => 'Handgefertigte Luxus-Safaris durch die Serengeti, den Ngorongoro-Krater und darüber hinaus', 'es' => 'Safaris de lujo artesanales por el Serengeti, el cráter del Ngorongoro y más allá'],
            'button_text'     => ['en' => 'Explore Safaris', 'fr' => 'Explorer les Safaris', 'de' => 'Safaris Entdecken', 'es' => 'Explorar Safaris'],
            'button_link'     => '/en/safaris',
            'bg_color'        => '#083321',
            'bg_image'        => '',
            'image'           => '',
            'image_alt'       => 'Luxury safari in the Serengeti',
            'next_up_text'    => ['en' => 'Luxury Private Journeys', 'fr' => 'Voyages Privés de Luxe', 'de' => 'Luxus-Privatreisen', 'es' => 'Viajes Privados de Lujo'],
        ]);

        HeroSlide::create([
            'page_section_id' => $hero->id,
            'order'           => 1,
            'label'           => ['en' => 'NEW EXPERIENCE', 'fr' => 'NOUVELLE EXPÉRIENCE', 'de' => 'NEUES ERLEBNIS', 'es' => 'NUEVA EXPERIENCIA'],
            'title'           => ['en' => 'Witness the Great Migration', 'fr' => 'Assistez à la Grande Migration', 'de' => 'Erleben Sie die Große Migration', 'es' => 'Sea Testigo de la Gran Migración'],
            'subtitle'        => ['en' => 'Follow millions of wildebeest across the endless Serengeti plains', 'fr' => 'Suivez des millions de gnous à travers les plaines infinies du Serengeti', 'de' => 'Folgen Sie Millionen von Gnus über die endlosen Serengeti-Ebenen', 'es' => 'Siga millones de ñus a través de las interminables llanuras del Serengeti'],
            'button_text'     => ['en' => 'View Migration Safaris', 'fr' => 'Voir les Safaris Migration', 'de' => 'Migrations-Safaris Ansehen', 'es' => 'Ver Safaris de Migración'],
            'button_link'     => '/en/safaris',
            'bg_color'        => '#131414',
            'bg_image'        => '',
            'image'           => '',
            'image_alt'       => 'Great wildebeest migration in Tanzania',
            'next_up_text'    => ['en' => 'Zanzibar Beach Escape', 'fr' => 'Escapade Balnéaire à Zanzibar', 'de' => 'Zanzibar Strandausflug', 'es' => 'Escapada a la Playa de Zanzíbar'],
        ]);

        HeroSlide::create([
            'page_section_id' => $hero->id,
            'order'           => 2,
            'label'           => ['en' => 'EXCLUSIVE', 'fr' => 'EXCLUSIF', 'de' => 'EXKLUSIV', 'es' => 'EXCLUSIVO'],
            'title'           => ['en' => 'Zanzibar — Where Safari Meets the Sea', 'fr' => 'Zanzibar — Où le Safari Rencontre la Mer', 'de' => 'Sansibar — Wo Safari das Meer Trifft', 'es' => 'Zanzíbar — Donde el Safari se Encuentra con el Mar'],
            'subtitle'        => ['en' => 'End your adventure on pristine white-sand beaches and turquoise waters', 'fr' => 'Terminez votre aventure sur des plages de sable blanc immaculé', 'de' => 'Beenden Sie Ihr Abenteuer an unberührten weißen Sandstränden', 'es' => 'Termine su aventura en playas de arena blanca prístina'],
            'button_text'     => ['en' => 'Discover Zanzibar', 'fr' => 'Découvrir Zanzibar', 'de' => 'Sansibar Entdecken', 'es' => 'Descubrir Zanzíbar'],
            'button_link'     => '/en/safaris',
            'bg_color'        => '#083321',
            'bg_image'        => '',
            'image'           => '',
            'image_alt'       => 'Zanzibar beach tropical paradise',
            'next_up_text'    => ['en' => 'Serengeti Luxury Camp', 'fr' => 'Camp de Luxe Serengeti', 'de' => 'Serengeti Luxus-Camp', 'es' => 'Campamento de Lujo Serengeti'],
        ]);

        // ─── SECTION 2: TRUST STRIP (ICON FEATURES) ─────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'icon_features',
            'order'         => 1,
            'is_active'     => true,
            'data'          => [
                'heading'    => ['en' => 'Why Travel With Lomo Tanzania Safaris', 'fr' => 'Pourquoi Voyager Avec Lomo Tanzania Safaris', 'de' => 'Warum Mit Lomo Tanzania Safaris Reisen', 'es' => 'Por Qué Viajar Con Lomo Tanzania Safaris'],
                'subheading' => ['en' => 'Crafting unforgettable safari experiences since day one', 'fr' => 'Créer des expériences de safari inoubliables depuis le premier jour', 'de' => 'Unvergessliche Safari-Erlebnisse seit dem ersten Tag', 'es' => 'Creando experiencias de safari inolvidables desde el primer día'],
                'columns'    => '4',
                'bg_color'   => '',
                'items'      => [
                    [
                        'icon'        => 'heart',
                        'title'       => ['en' => 'Handcrafted Safaris', 'fr' => 'Safaris Sur Mesure', 'de' => 'Handgefertigte Safaris', 'es' => 'Safaris Artesanales'],
                        'description' => ['en' => 'Every journey is tailor-made to your dreams and preferences', 'fr' => 'Chaque voyage est conçu sur mesure selon vos rêves', 'de' => 'Jede Reise wird nach Ihren Träumen maßgeschneidert', 'es' => 'Cada viaje se adapta a sus sueños y preferencias'],
                    ],
                    [
                        'icon'        => 'globe',
                        'title'       => ['en' => 'Local Experts', 'fr' => 'Experts Locaux', 'de' => 'Lokale Experten', 'es' => 'Expertos Locales'],
                        'description' => ['en' => 'Born and raised in Tanzania — we know every hidden gem', 'fr' => 'Nés et élevés en Tanzanie — nous connaissons chaque trésor caché', 'de' => 'Geboren und aufgewachsen in Tansania — wir kennen jeden verborgenen Schatz', 'es' => 'Nacidos y criados en Tanzania — conocemos cada joya oculta'],
                    ],
                    [
                        'icon'        => 'shield',
                        'title'       => ['en' => 'Luxury Lodges', 'fr' => 'Lodges de Luxe', 'de' => 'Luxus-Lodges', 'es' => 'Lodges de Lujo'],
                        'description' => ['en' => 'Hand-picked 5-star lodges and tented camps in the wilderness', 'fr' => 'Lodges 5 étoiles sélectionnés dans la nature sauvage', 'de' => 'Handverlesene 5-Sterne-Lodges und Zeltcamps in der Wildnis', 'es' => 'Lodges de 5 estrellas seleccionados en la naturaleza'],
                    ],
                    [
                        'icon'        => 'clock',
                        'title'       => ['en' => '24/7 Support', 'fr' => 'Support 24/7', 'de' => '24/7 Unterstützung', 'es' => 'Soporte 24/7'],
                        'description' => ['en' => 'Round-the-clock assistance from booking to your last sunset', 'fr' => 'Assistance 24h/24 de la réservation à votre dernier coucher de soleil', 'de' => 'Rund-um-die-Uhr-Betreuung von der Buchung bis zum letzten Sonnenuntergang', 'es' => 'Asistencia las 24 horas desde la reserva hasta su última puesta de sol'],
                    ],
                ],
            ],
        ]);

        // ─── SECTION 3: FEATURED SAFARIS (SAFARI GRID) ──────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'safari_grid',
            'order'         => 2,
            'is_active'     => true,
            'data'          => [
                'heading'       => ['en' => 'Featured Safari Journeys', 'fr' => 'Safaris en Vedette', 'de' => 'Ausgewählte Safari-Reisen', 'es' => 'Safaris Destacados'],
                'subheading'    => ['en' => 'Our most sought-after luxury experiences across Tanzania', 'fr' => 'Nos expériences de luxe les plus recherchées en Tanzanie', 'de' => 'Unsere gefragtesten Luxuserlebnisse in Tansania', 'es' => 'Nuestras experiencias de lujo más buscadas en Tanzania'],
                'count'         => 6,
                'featured_only' => '1',
                'columns'       => '3',
            ],
        ]);

        // ─── SECTION 4: DESTINATIONS (DESTINATION SHOWCASE) ─────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'destination_showcase',
            'order'         => 3,
            'is_active'     => true,
            'data'          => [
                'heading'    => ['en' => 'Explore Tanzania\'s Finest', 'fr' => 'Explorez le Meilleur de la Tanzanie', 'de' => 'Entdecken Sie Tansanias Bestes', 'es' => 'Explore lo Mejor de Tanzania'],
                'subheading' => ['en' => 'From the endless Serengeti to the spice island of Zanzibar', 'fr' => 'Des plaines infinies du Serengeti à l\'île aux épices de Zanzibar', 'de' => 'Von der endlosen Serengeti bis zur Gewürzinsel Sansibar', 'es' => 'Desde el interminable Serengeti hasta la isla de las especias de Zanzíbar'],
                'count'      => 8,
            ],
        ]);

        // ─── SECTION 5: EXPERIENCE / STORY (SPLIT HERO) ─────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'split_hero',
            'order'         => 4,
            'is_active'     => true,
            'data'          => [
                'heading'     => ['en' => 'Experience Africa Beyond Expectations', 'fr' => 'Vivez l\'Afrique Au-Delà des Attentes', 'de' => 'Erleben Sie Afrika Jenseits Aller Erwartungen', 'es' => 'Viva África Más Allá de las Expectativas'],
                'subheading'  => ['en' => 'A journey designed around you', 'fr' => 'Un voyage conçu autour de vous', 'de' => 'Eine Reise, die um Sie herum gestaltet wurde', 'es' => 'Un viaje diseñado en torno a usted'],
                'body'        => ['en' => 'For over a decade, we have been guiding discerning travelers through Tanzania\'s most extraordinary landscapes. Every safari we craft is a deeply personal journey — from private game drives at dawn to candlelit dinners under the stars. This is not just a trip. This is your story, written in the wild.', 'fr' => 'Depuis plus d\'une décennie, nous guidons des voyageurs exigeants à travers les paysages les plus extraordinaires de Tanzanie. Chaque safari que nous créons est un voyage profondément personnel — des safaris privés à l\'aube aux dîners aux chandelles sous les étoiles.', 'de' => 'Seit über einem Jahrzehnt führen wir anspruchsvolle Reisende durch Tansanias außergewöhnlichste Landschaften. Jede Safari, die wir gestalten, ist eine zutiefst persönliche Reise — von privaten Pirschfahrten bei Morgengrauen bis zu Abendessen bei Kerzenschein unter den Sternen.', 'es' => 'Durante más de una década, hemos guiado a viajeros exigentes a través de los paisajes más extraordinarios de Tanzania. Cada safari que creamos es un viaje profundamente personal — desde safaris privados al amanecer hasta cenas a la luz de las velas bajo las estrellas.'],
                'button_text' => ['en' => 'Plan Your Safari', 'fr' => 'Planifiez Votre Safari', 'de' => 'Planen Sie Ihre Safari', 'es' => 'Planifique Su Safari'],
                'button_url'  => '/en/plan-safari',
                'layout'      => 'image_right',
                'image'       => '',
                'bg_color'    => '#083321',
            ],
        ]);

        // ─── SECTION 6: TESTIMONIALS (TESTIMONIAL SLIDER) ───────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'testimonial_slider',
            'order'         => 5,
            'is_active'     => true,
            'data'          => [
                'heading'          => ['en' => 'What Our Guests Say', 'fr' => 'Ce Que Disent Nos Invités', 'de' => 'Was Unsere Gäste Sagen', 'es' => 'Lo Que Dicen Nuestros Huéspedes'],
                'subheading'       => ['en' => 'Real stories from travelers who experienced the magic', 'fr' => 'De vraies histoires de voyageurs qui ont vécu la magie', 'de' => 'Echte Geschichten von Reisenden, die die Magie erlebt haben', 'es' => 'Historias reales de viajeros que experimentaron la magia'],
                'count'            => 6,
                'show_rating'      => '1',
                'slider_autoplay'  => '1',
            ],
        ]);

        // ─── SECTION 7: IMAGE GALLERY ────────────────────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'image_gallery',
            'order'         => 6,
            'is_active'     => true,
            'data'          => [
                'heading'        => ['en' => 'Safari Moments', 'fr' => 'Moments Safari', 'de' => 'Safari-Momente', 'es' => 'Momentos de Safari'],
                'subheading'     => ['en' => 'A glimpse into the extraordinary experiences that await you', 'fr' => 'Un aperçu des expériences extraordinaires qui vous attendent', 'de' => 'Ein Einblick in die außergewöhnlichen Erlebnisse, die Sie erwarten', 'es' => 'Un vistazo a las experiencias extraordinarias que le esperan'],
                'columns'        => '3',
                'gallery_layout' => 'grid',
                'lightbox'       => '1',
                'images'         => '',
            ],
        ]);

        // ─── SECTION 8: CTA BANNER ──────────────────────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'cta_banner',
            'order'         => 7,
            'is_active'     => true,
            'data'          => [
                'heading'     => ['en' => 'Start Planning Your Dream Safari', 'fr' => 'Commencez à Planifier Votre Safari de Rêve', 'de' => 'Beginnen Sie mit der Planung Ihrer Traumsafari', 'es' => 'Comience a Planificar Su Safari Soñado'],
                'subheading'  => ['en' => 'Let our experts craft a bespoke journey tailored to your every wish. No templates — just your story, written in the wild.', 'fr' => 'Laissez nos experts créer un voyage sur mesure adapté à chacun de vos souhaits.', 'de' => 'Lassen Sie unsere Experten eine maßgeschneiderte Reise nach Ihren Wünschen gestalten.', 'es' => 'Deje que nuestros expertos elaboren un viaje a medida adaptado a cada uno de sus deseos.'],
                'button_text' => ['en' => 'Plan My Safari', 'fr' => 'Planifier Mon Safari', 'de' => 'Meine Safari Planen', 'es' => 'Planificar Mi Safari'],
                'button_url'  => '/en/plan-safari',
                'bg_color'    => '#083321',
                'bg_image'    => '',
            ],
        ]);

        // ─── SECTION 9: BLOG / INSIGHTS ─────────────────────────
        PageSection::create([
            'page_id'      => $page->id,
            'section_type'  => 'blog',
            'order'         => 8,
            'is_active'     => true,
            'data'          => [
                'heading'    => ['en' => 'Safari Insights & Stories', 'fr' => 'Aperçus & Histoires de Safari', 'de' => 'Safari-Einblicke & Geschichten', 'es' => 'Perspectivas e Historias de Safari'],
                'subheading' => ['en' => 'Expert tips, travel guides, and tales from the African bush', 'fr' => 'Conseils d\'experts, guides de voyage et récits de la brousse africaine', 'de' => 'Expertentipps, Reiseführer und Geschichten aus dem afrikanischen Busch', 'es' => 'Consejos de expertos, guías de viaje e historias de la sabana africana'],
                'count'      => 3,
            ],
        ]);

        echo "Homepage built with 9 sections + 3 hero slides.\n";
    }
}
