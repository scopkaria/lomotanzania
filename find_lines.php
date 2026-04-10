<?php
// Find all lines with \xC3\xA2 byte sequence
$lines = file(__DIR__ . '/resources/views/layouts/app.blade.php');
$pat = chr(0xC3) . chr(0xA2);

foreach ($lines as $i => $line) {
    $cnt = substr_count($line, $pat);
    if ($cnt > 0) {
        $num = $i + 1;
        $hex = bin2hex(substr(trim($line), 0, 40));
        echo "Line {$num} ({$cnt}x): " . mb_substr(trim($line), 0, 100) . "\n";
        echo "  HEX(first 40 chars): {$hex}\n\n";
    }
}
