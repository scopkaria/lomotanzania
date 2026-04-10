<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Media;

$files = Storage::disk('public')->allFiles('editor-images');
echo "Total editor-images on disk: " . count($files) . "\n";

$inDb = Media::whereIn('path', $files)->pluck('path')->all();
echo "Already in Media table: " . count($inDb) . "\n";

$missing = array_diff($files, $inDb);
echo "Missing from Media table: " . count($missing) . "\n";

if (count($missing)) {
    $imported = 0;
    foreach ($missing as $path) {
        $fullPath = Storage::disk('public')->path($path);
        if (!file_exists($fullPath)) continue;
        Media::create([
            'filename'  => basename($path),
            'path'      => $path,
            'mime_type' => mime_content_type($fullPath),
            'size'      => filesize($fullPath),
            'disk'      => 'public',
        ]);
        $imported++;
    }
    echo "Imported $imported orphaned editor images into Media table.\n";
} else {
    echo "No orphaned images to import.\n";
}

// Also check total media count
$total = Media::count();
echo "\nTotal Media records now: $total\n";
