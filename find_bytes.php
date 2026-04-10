<?php
// Find remaining corrupted bytes in app.blade.php
$c = file_get_contents(__DIR__ . '/resources/views/layouts/app.blade.php');
echo 'File size: ' . strlen($c) . PHP_EOL;

// Search for various mojibake patterns
$patterns = [
    'xC3xA2' => chr(0xC3).chr(0xA2),
    'Ã¢'     => 'Ã¢',
    'â€'     => "\xE2\x80",
    'â€"'    => "\xE2\x80\x93",  // en dash mojibake
    'â€"'    => "\xE2\x80\x94",  // em dash mojibake
    'â€¢'    => "\xE2\x80\xA2",  // bullet mojibake
];

foreach ($patterns as $name => $pat) {
    $count = substr_count($c, $pat);
    if ($count > 0) {
        echo "{$name}: {$count} occurrences\n";
        // Show first occurrence context
        $pos = strpos($c, $pat);
        $lineStart = strrpos(substr($c, 0, $pos), "\n");
        $lineEnd = strpos($c, "\n", $pos);
        $line = substr_count(substr($c, 0, $pos), "\n") + 1;
        $preview = substr($c, $lineStart ?: 0, min(150, ($lineEnd ?: $pos + 50) - ($lineStart ?: 0)));
        echo "  First at line {$line}: " . trim($preview) . "\n";
    }
}

// Also check show.blade.php
$c2 = file_get_contents(__DIR__ . '/resources/views/safaris/show.blade.php');
echo "\n--- show.blade.php ---\n";
foreach ($patterns as $name => $pat) {
    $count = substr_count($c2, $pat);
    if ($count > 0) {
        echo "{$name}: {$count} occurrences\n";
    }
}
if (strpos($c2, "\xE2\x80\x94") !== false) {
    echo "Clean em dash (—): found ✓\n";
}
