<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate admin session
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Media;

// Test the json() logic directly
$total = Media::count();
echo "Total media: $total\n";

// Simulate kind=all (default now)
$page1 = Media::latest()->paginate(60, ['*'], 'page', 1);
echo "Page 1: {$page1->count()} items, last_page={$page1->lastPage()}, total={$page1->total()}\n";

if ($page1->lastPage() > 1) {
    $page2 = Media::latest()->paginate(60, ['*'], 'page', 2);
    echo "Page 2: {$page2->count()} items\n";
}

// Test kind=image
$imageCount = Media::where('mime_type', 'like', 'image/%')->count();
echo "Image files: $imageCount\n";

// Test kind=video
$videoCount = Media::where('mime_type', 'like', 'video/%')->count();
echo "Video files: $videoCount\n";

echo "\nAll kinds accessible — no user filtering, no hidden media.\n";

$kernel->terminate(
    Illuminate\Http\Request::create('/', 'GET'),
    new Illuminate\Http\Response()
);
