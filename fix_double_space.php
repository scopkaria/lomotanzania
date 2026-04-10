<?php
// Fix double space before em dash in show.blade.php map labels
$file = __DIR__ . '/resources/views/safaris/show.blade.php';
$content = file_get_contents($file);

// Replace double space + em dash with single space + em dash
$content = str_replace("'  — '", "' — '", $content);
$content = str_replace("'  —'", "' —'", $content);

file_put_contents($file, $content);
echo "✓ Fixed double space before em dashes in show.blade.php\n";
