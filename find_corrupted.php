<?php
/**
 * Find all lines in app.blade.php that still contain \xC3\xA2 bytes.
 */
$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$lines = file($file);

foreach ($lines as $i => $line) {
    if (strpos($line, "\xC3\xA2") !== false) {
        $lineNum = $i + 1;
        $count = substr_count($line, "\xC3\xA2");
        $preview = substr(trim($line), 0, 120);
        echo "Line {$lineNum} ({$count}x): {$preview}\n";
    }
}
