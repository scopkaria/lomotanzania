<?php
// Find all remaining corrupted byte positions in app.blade.php
$lines = file(__DIR__ . '/resources/views/layouts/app.blade.php');
$pat = "\xC3\xA2";  // raw â byte

$found = 0;
foreach ($lines as $i => $line) {
    $cnt = substr_count($line, $pat);
    if ($cnt > 0) {
        $found += $cnt;
        $num = $i + 1;
        echo "Line {$num} ({$cnt}x): " . rtrim(mb_substr($line, 0, 150)) . "\n\n";
    }
}
echo "Total lines with corrupted bytes: {$found}\n";
