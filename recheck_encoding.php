<?php
// Re-check both files after all fixes
echo "=== app.blade.php ===\n";
$c = file_get_contents(__DIR__ . '/resources/views/layouts/app.blade.php');
echo "Size: " . strlen($c) . "\n";

// Search for common mojibake sequences
$checks = [
    'â€'      => "\xC3\xA2\xE2\x82\xAC",  // double-encoded â€
    'Ã¢â€¢Â'  => "Ã¢â€¢Â",
    'xC3xA2'  => chr(0xC3) . chr(0xA2),
    'â (raw)' => "\xC3\xA2",
    'Ã (raw)' => "\xC3\x83",
];

foreach ($checks as $name => $pat) {
    echo "{$name}: " . substr_count($c, $pat) . "\n";
}

// Now let's check the actual corrupted area (around line 137)
$lines = file(__DIR__ . '/resources/views/layouts/app.blade.php');
for ($i = 134; $i < 145 && $i < count($lines); $i++) {
    $hex = bin2hex(rtrim($lines[$i]));
    echo "\nLine " . ($i+1) . " hex: " . substr($hex, 0, 200) . (strlen($hex) > 200 ? '...' : '') . "\n";
    echo "Line " . ($i+1) . " txt: " . rtrim($lines[$i]) . "\n";
}

echo "\n\n=== show.blade.php ===\n";
$c2 = file_get_contents(__DIR__ . '/resources/views/safaris/show.blade.php');
echo "Size: " . strlen($c2) . "\n";
foreach ($checks as $name => $pat) {
    echo "{$name}: " . substr_count($c2, $pat) . "\n";
}

// Check the fixed map label lines
$lines2 = file(__DIR__ . '/resources/views/safaris/show.blade.php');
for ($i = 498; $i < 516 && $i < count($lines2); $i++) {
    if (stripos($lines2[$i], 'Day') !== false || stripos($lines2[$i], 'label') !== false) {
        echo "Line " . ($i+1) . ": " . trim($lines2[$i]) . "\n";
    }
}
