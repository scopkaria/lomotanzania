<?php
/**
 * Fix ALL remaining corrupted encoding in app.blade.php:
 * - CSS section comments: "Ã¢â‚¬â€" → "—" (em dash decorators)
 * - Flag emoji on line 960: corrupted → proper 🇹🇿
 */

$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// 1. Fix CSS comment decorators: "Ã¢â‚¬â€" should be "—" (em dash)
// Pattern: /* Ã¢â‚¬â€ Text here Ã¢â‚¬â€ */
$content = str_replace(
    ['Ã¢â‚¬â€ ', ' Ã¢â‚¬â€'],
    ['— ', ' —'],
    $content
);

// 2. Fix corrupted flag emoji (Tanzania 🇹🇿)
// The mojibake "Ã°Å¸â€¡Â¹Ã°Å¸â€¡Â¿" is the corrupted form of the Tanzania flag
$content = str_replace(
    'Ã°Å¸â€¡Â¹Ã°Å¸â€¡Â¿',
    '🇹🇿',
    $content
);

file_put_contents($file, $content);
echo "✓ Fixed app.blade.php CSS comments and flag emoji\n";

// Verify
$verify = file_get_contents($file);
$remaining = substr_count($verify, "\xC3\xA2");
echo "  Remaining \\xC3\\xA2 bytes: {$remaining}\n";
$remaining2 = substr_count($verify, 'Ã¢');
echo "  Remaining 'Ã¢' sequences: {$remaining2}\n";
$remaining3 = substr_count($verify, 'â‚¬');
echo "  Remaining 'â‚¬' sequences: {$remaining3}\n";
