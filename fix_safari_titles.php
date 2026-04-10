<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Fix ??? → — in all text columns of safari_packages
$columns = ['title', 'short_description', 'description', 'overview_title', 'highlights_title', 'highlights_intro', 'inclusions_title', 'inclusions_intro'];

$count = 0;
foreach ($columns as $col) {
    $affected = DB::table('safari_packages')
        ->where($col, 'like', '%???%')
        ->update([$col => DB::raw("REPLACE(`{$col}`, '???', '—')")]);
    if ($affected) {
        echo "Fixed {$affected} rows in column '{$col}'\n";
        $count += $affected;
    }
}

// Also fix in itineraries table
foreach (['title', 'description'] as $col) {
    $affected = DB::table('itineraries')
        ->where($col, 'like', '%???%')
        ->update([$col => DB::raw("REPLACE(`{$col}`, '???', '—')")]);
    if ($affected) {
        echo "Fixed {$affected} itinerary rows in '{$col}'\n";
        $count += $affected;
    }
}

echo "\nTotal fixes: {$count}\n";

// Verify
echo "\n=== VERIFIED TITLES ===\n";
$safaris = DB::table('safari_packages')->select('id', 'title')->orderBy('id')->get();
foreach ($safaris as $s) {
    echo "ID:{$s->id} | {$s->title}\n";
}
