<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$page = App\Models\Page::where('slug', 'destinations')->first();
if ($page) {
    $sections = $page->pageSections()->where('is_active', 1)->orderBy('order')->get();
    foreach ($sections as $sec) {
        echo $sec->id . ' | ' . $sec->section_type . ' | exists=' . (view()->exists('sections.' . str_replace('_', '-', $sec->section_type)) ? 'Y' : 'N') . PHP_EOL;
    }
} else {
    echo "No destinations page found\n";
}
