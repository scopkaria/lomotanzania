<?php
/**
 * Fix remaining mojibake in all Blade template files.
 * Targets: contact.blade.php (user-visible), blog/show, safaris/index, _filters (comments)
 */

$files = [
    __DIR__ . '/resources/views/contact.blade.php',
    __DIR__ . '/resources/views/blog/show.blade.php',
    __DIR__ . '/resources/views/safaris/index.blade.php',
    __DIR__ . '/resources/views/safaris/_filters.blade.php',
];

// Mojibake → correct replacements
$replacements = [
    // Em dash: â€" (0xE2 0x80 0x94 interpreted as Windows-1252 → â€")
    "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C" => '–', // en dash variant
    "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9D" => '—', // em dash variant
    'â€"' => '–',
    'â€"' => '—',
    // Box drawing chars (used in comments)
    'â"€' => '─',  // horizontal line
    'â•' => '═',   // double horizontal line
    'â–ˆ' => '█',  // full block
    'â–'  => '▒',  // medium shade
    'â€¢' => '•',  // bullet
    // Clean up any remaining edge cases
    'â€™' => "'",  // right single quote
    'â€˜' => "'",  // left single quote
    'â€œ' => '"',  // left double quote
];

$totalFixed = 0;
foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    foreach ($replacements as $bad => $good) {
        $content = str_replace($bad, $good, $content);
    }
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $name = str_replace(__DIR__ . '/', '', $file);
        $changes = strlen($original) - strlen($content);
        echo "✓ Fixed: {$name}\n";
        $totalFixed++;
    }
}

echo $totalFixed === 0 
    ? "  No changes needed\n"
    : "\n✓ Fixed {$totalFixed} files\n";

// Verify contact.blade.php specifically
$contact = file_get_contents(__DIR__ . '/resources/views/contact.blade.php');
if (strpos($contact, 'â€') !== false) {
    echo "⚠ contact.blade.php still has corrupted text\n";
    // Show remaining issues
    $lines = explode("\n", $contact);
    foreach ($lines as $i => $line) {
        if (strpos($line, 'â€') !== false) {
            echo "  Line " . ($i+1) . ": " . trim(substr($line, 0, 120)) . "\n";
        }
    }
} else {
    echo "✓ contact.blade.php is clean\n";
}
