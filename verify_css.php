<?php
// Verify no more unclosed CSS comments in app.blade.php
$lines = file(__DIR__ . '/resources/views/layouts/app.blade.php');
$inComment = false;
$commentStart = 0;
$issues = [];

for ($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];
    
    // Track comment opens/closes
    if (strpos($line, '/*') !== false && strpos($line, '*/') === false) {
        // Comment opened but not closed on same line
        if ($inComment) {
            $issues[] = "Line " . ($i+1) . ": Nested comment open (already in comment from line {$commentStart})";
        }
        $inComment = true;
        $commentStart = $i + 1;
    }
    if (strpos($line, '*/') !== false) {
        $inComment = false;
    }
}

if ($inComment) {
    $issues[] = "Unclosed comment starting at line {$commentStart}";
}

if (empty($issues)) {
    echo "✓ No CSS comment issues found\n";
} else {
    foreach ($issues as $issue) {
        echo "⚠ {$issue}\n";
    }
}

// Quick functional test
echo "\nTesting page load...\n";
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::create('/en', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();

// Check that key CSS classes are NOT inside comments (i.e., they appear in the rendered output)
$checks = [
    '.nav-link' => 'Nav link styles',
    '.mega-panel' => 'Mega panel styles',
    '.mobile-menu' => 'Mobile menu styles',
    'input[type=' => 'Form input styles',
];
foreach ($checks as $css => $label) {
    if (strpos($content, $css) !== false) {
        echo "  ✓ {$label} present\n";
    } else {
        echo "  ⚠ {$label} MISSING!\n";
    }
}
echo "  Status: " . $response->getStatusCode() . "\n";
