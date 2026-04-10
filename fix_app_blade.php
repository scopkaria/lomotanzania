<?php
/**
 * Fix app.blade.php corrupted CSS comment decorations.
 * Uses line-by-line approach to replace corrupted lines.
 */

$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$lines = file($file);
$fixed = 0;

foreach ($lines as $i => &$line) {
    // Detect lines that are predominantly corrupted bullet patterns
    // These lines contain repeated sequences of non-ASCII bytes followed by "GLOBAL TYPOGRAPHY" text
    if (strpos($line, '/*') !== false && substr_count($line, "\xC3\xA2") > 5) {
        $line = "        /* ======================================\n";
        $fixed++;
    } elseif (strpos($line, '*/') !== false && substr_count($line, "\xC3\xA2") > 5) {
        $line = "        ====================================== */\n";
        $fixed++;
    } elseif (substr_count($line, "\xC3\xA2") > 10 && strpos($line, '/*') === false && strpos($line, '*/') === false) {
        // Pure decoration line with no surrounding comment markers
        // Check if it's the closing decoration line
        if (trim($line) !== '' && substr_count($line, "\xC3\xA2") > 5) {
            $line = "        ======================================\n";
            $fixed++;
        }
    }
}
unset($line);

file_put_contents($file, implode('', $lines));
echo "✓ Fixed {$fixed} corrupted decoration lines in app.blade.php\n";

// Verify
$content = file_get_contents($file);
$remaining = substr_count($content, "\xC3\xA2");
echo "  Remaining \\xC3\\xA2 occurrences: {$remaining}\n";
