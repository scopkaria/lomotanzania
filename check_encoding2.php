<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\SafariPackage;

$s = SafariPackage::find(4);
$raw = $s->getRawOriginal('title');
echo "Raw title: {$raw}\n";
echo "Hex: " . bin2hex($raw) . "\n";
echo "Valid UTF-8: " . (mb_check_encoding($raw, 'UTF-8') ? 'YES' : 'NO') . "\n";

// Check if it's JSON (translatable)
$decoded = json_decode($raw, true);
if ($decoded) {
    echo "JSON decoded:\n";
    foreach ($decoded as $k => $v) {
        echo "  {$k}: {$v}\n";
        echo "  {$k} hex: " . bin2hex($v) . "\n";
    }
} else {
    // Check for em dash
    if (str_contains($raw, "\xE2\x80\x94")) {
        echo "Contains proper em dash\n";
    }
    // Check for ???
    if (str_contains($raw, '???')) {
        echo "Contains literal ???\n";
    }
    // Check for other sequences
    $bad = ["\xC3\xA2\xC2\x80\xC2\x94", "\xE2\x80\x93", "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C"];
    foreach ($bad as $b) {
        if (str_contains($raw, $b)) {
            echo "Contains bad sequence: " . bin2hex($b) . "\n";
        }
    }
}

// Also check highlights, included, excluded for ID 4
echo "\nHighlights: " . ($s->getRawOriginal('highlights') ?: 'NULL') . "\n";
echo "Included: " . ($s->getRawOriginal('included') ?: 'NULL') . "\n";
echo "Excluded: " . ($s->getRawOriginal('excluded') ?: 'NULL') . "\n";
echo "Description length: " . strlen($s->getRawOriginal('description') ?? '') . "\n";
