<?php
/**
 * Dump current data for Safari IDs 2 and 3
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SafariPackage;

foreach ([2, 3] as $id) {
    $s = SafariPackage::with(['itineraries.destination', 'destinations', 'tourType', 'categoryRelation'])->find($id);
    if (!$s) { echo "Safari #{$id} not found\n"; continue; }

    echo "\n═══════════════════════════════════════\n";
    echo "SAFARI #{$s->id}: {$s->title}\n";
    echo "═══════════════════════════════════════\n";
    echo "slug: {$s->slug}\n";
    echo "duration: {$s->duration}\n";
    echo "difficulty: {$s->difficulty}\n";
    echo "price: {$s->price} {$s->currency}\n";
    echo "status: {$s->status}\n";
    echo "safari_type: {$s->safari_type}\n";
    echo "tour_type_id: {$s->tour_type_id} (" . ($s->tourType->name ?? 'N/A') . ")\n";
    echo "category_id: {$s->category_id} (" . ($s->categoryRelation->name ?? 'N/A') . ")\n";
    echo "featured: " . ($s->featured ? 'yes' : 'no') . "\n";
    echo "is_popular: " . ($s->is_popular ? 'yes' : 'no') . "\n";
    echo "\n--- TITLE ---\n{$s->title}\n";
    echo "\n--- SHORT DESCRIPTION ---\n{$s->short_description}\n";
    echo "\n--- DESCRIPTION ---\n{$s->description}\n";
    echo "\n--- OVERVIEW TITLE ---\n{$s->overview_title}\n";
    echo "\n--- HIGHLIGHTS TITLE ---\n{$s->highlights_title}\n";
    echo "\n--- HIGHLIGHTS INTRO ---\n{$s->highlights_intro}\n";
    echo "\n--- HIGHLIGHTS (JSON) ---\n" . json_encode($s->highlights, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "\n--- INCLUSIONS TITLE ---\n{$s->inclusions_title}\n";
    echo "\n--- INCLUSIONS INTRO ---\n{$s->inclusions_intro}\n";
    echo "\n--- INCLUDED (JSON) ---\n" . json_encode($s->included, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "\n--- EXCLUDED (JSON) ---\n" . json_encode($s->excluded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "\n--- SEASONAL PRICING ---\n" . json_encode($s->seasonal_pricing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "\n--- SEO ---\n";
    echo "meta_title: {$s->meta_title}\n";
    echo "meta_description: {$s->meta_description}\n";
    echo "meta_keywords: {$s->meta_keywords}\n";

    echo "\n--- TRANSLATIONS ---\n";
    foreach (['title', 'short_description', 'description', 'overview_title', 'highlights_title', 'highlights_intro', 'inclusions_title', 'inclusions_intro'] as $f) {
        $col = $f . '_translations';
        $val = $s->$col;
        if ($val && is_array($val)) {
            foreach ($val as $locale => $text) {
                echo "{$f}[{$locale}]: " . mb_substr($text, 0, 80) . (mb_strlen($text) > 80 ? '...' : '') . "\n";
            }
        }
    }

    echo "\n--- DESTINATIONS ---\n";
    foreach ($s->destinations as $d) {
        echo "  - {$d->id}: {$d->name}\n";
    }

    echo "\n--- ITINERARIES ---\n";
    foreach ($s->itineraries as $it) {
        echo "  Day {$it->day_number}: {$it->title}";
        echo " | dest=" . ($it->destination->name ?? 'none') . "\n";
        echo "    " . mb_substr($it->description, 0, 100) . "\n";
    }
}
