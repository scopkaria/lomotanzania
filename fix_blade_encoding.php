<?php
/**
 * Fix corrupted UTF-8 bytes in Blade template files.
 * Replaces mojibake em dashes and bullet characters with clean ASCII/UTF-8.
 */

// 1. Fix show.blade.php — corrupted em dashes in map labels
$showFile = __DIR__ . '/resources/views/safaris/show.blade.php';
$content = file_get_contents($showFile);

// The corrupted sequence for em dash: â€ (or variants) followed by space
// Replace all instances of the corrupted em-dash pattern in label strings
// Pattern: "â€  (the â€ followed by zero-width or regular space)
$before = substr_count($content, "\xE2\x80");  // count raw bytes

// Direct byte replacement: replace corrupted "â€ with proper em dash
// The file has: ' "â€ ' which should be ' — '
$content = str_replace('" â€ ', ' — ', $content);  // with leading quote
$content = str_replace('"â€ ', ' — ', $content);   // without space after quote
$content = str_replace('"â€', ' —', $content);     // catch remaining

// Fix the CSS comment with corrupted bullets
$content = preg_replace(
    '/\/\*\s*(?:Ã¢â€¢Â)+\s*\n/u',
    "/* ======================================\n",
    $content
);
$content = preg_replace(
    '/\s*(?:Ã¢â€¢Â)+\s*\*\//u',
    "\n        ====================================== */",
    $content
);

file_put_contents($showFile, $content);
echo "✓ Fixed show.blade.php\n";

// Verify
$verify = file_get_contents($showFile);
if (strpos($verify, 'â€') === false && strpos($verify, 'Ã¢â€¢') === false) {
    echo "  ✓ No corrupted sequences remain\n";
} else {
    echo "  ⚠ Some corrupted sequences may remain — manual check needed\n";
}

// 2. Fix app.blade.php — corrupted bullet decoration in CSS comment
$appFile = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($appFile);

$content = preg_replace(
    '/\/\*\s*(?:Ã¢â€¢Â)+\s*\n/u',
    "/* ======================================\n",
    $content
);
$content = preg_replace(
    '/\s*(?:Ã¢â€¢Â)+\s*\*\//u',
    "\n        ====================================== */",
    $content
);

file_put_contents($appFile, $content);
echo "✓ Fixed app.blade.php\n";

$verify = file_get_contents($appFile);
if (strpos($verify, 'Ã¢â€¢') === false) {
    echo "  ✓ No corrupted sequences remain\n";
} else {
    echo "  ⚠ Some corrupted sequences may remain\n";
}
