<?php
// Use file_get_contents and manual search
$content = file_get_contents(__DIR__ . '/resources/views/layouts/app.blade.php');
$pat = "\xC3\xA2";
$pos = 0;
$found = 0;

while (($pos = strpos($content, $pat, $pos)) !== false) {
    $found++;
    $lineNum = substr_count(substr($content, 0, $pos), "\n") + 1;
    // Get the line containing this position
    $lineStart = strrpos(substr($content, 0, $pos), "\n");
    $lineEnd = strpos($content, "\n", $pos);
    $lineContent = substr($content, ($lineStart !== false ? $lineStart + 1 : 0), ($lineEnd !== false ? $lineEnd : $pos + 50) - ($lineStart !== false ? $lineStart + 1 : 0));
    if ($found <= 10) {
        echo "#{$found} at line {$lineNum}, byte {$pos}:\n";
        echo "  Context (first 120 chars): " . mb_substr(trim($lineContent), 0, 120) . "\n";
        echo "  Hex around: " . bin2hex(substr($content, max(0, $pos - 5), 20)) . "\n\n";
    }
    $pos++;
}
echo "Total: {$found}\n";
