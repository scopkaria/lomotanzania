<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SafariPackage;
use App\Models\Itinerary;

echo "=== SAFARI PACKAGES ===\n";
$safaris = SafariPackage::withCount('itineraries')->orderBy('id')->get();
foreach ($safaris as $s) {
    $title = is_array($s->getRawOriginal('title')) ? json_encode($s->getRawOriginal('title')) : $s->getRawOriginal('title');
    echo "ID:{$s->id} | Itin:{$s->itineraries_count} | Title: {$title}\n";
}

echo "\n=== ITINERARIES (first 20) ===\n";
$itins = Itinerary::with(['destination', 'accommodationRelation'])->orderBy('safari_package_id')->orderBy('day_number')->limit(20)->get();
foreach ($itins as $it) {
    $rawTitle = $it->getRawOriginal('title');
    echo "Safari:{$it->safari_package_id} Day:{$it->day_number} | Dest:{$it->destination?->name} | Accom:{$it->accommodationRelation?->name} | Title: {$rawTitle}\n";
}

echo "\n=== SAFARIS WITH 0 ITINERARIES ===\n";
$empty = SafariPackage::withCount('itineraries')->having('itineraries_count', 0)->get();
foreach ($empty as $s) {
    echo "ID:{$s->id} | {$s->title}\n";
}
