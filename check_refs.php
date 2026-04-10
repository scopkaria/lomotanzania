<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DESTINATIONS ===\n";
$dests = App\Models\Destination::select('id','name','slug','latitude','longitude')->orderBy('id')->get();
foreach ($dests as $d) {
    echo "{$d->id} | {$d->name} | lat:{$d->latitude} lng:{$d->longitude}\n";
}

echo "\n=== ACCOMMODATIONS ===\n";
$accs = App\Models\Accommodation::select('id','name','category')->orderBy('id')->get();
foreach ($accs as $a) {
    echo "{$a->id} | {$a->name} | {$a->category}\n";
}

echo "\n=== SAFARI DETAILS (IDs 4-19) ===\n";
$safaris = App\Models\SafariPackage::whereIn('id', range(4,19))->get();
foreach ($safaris as $s) {
    echo "\nID:{$s->id} | {$s->title}\n";
    echo "  Duration: {$s->duration}\n";
    echo "  Destinations: " . $s->destinations->pluck('name')->join(', ') . "\n";
}
