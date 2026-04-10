<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$safaris = App\Models\SafariPackage::select('id','title','tour_type_id','safari_type')->get();
foreach($safaris as $s) {
    echo $s->id . ' | ' . $s->title . ' | type_id=' . ($s->tour_type_id ?? 'NULL') . ' | safari_type=' . ($s->safari_type ?? 'NULL') . PHP_EOL;
}

echo PHP_EOL . 'Tour Types:' . PHP_EOL;
$types = App\Models\TourType::withCount('safariPackages')->get();
foreach($types as $t) {
    echo $t->id . ' | ' . $t->name . ' | ' . $t->safari_packages_count . ' safaris' . PHP_EOL;
}
