<?php
/**
 * Final verification: test key pages for encoding issues.
 * Checks response headers and content for mojibake patterns.
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$pages = [
    '/en' => 'Homepage',
    '/en/safaris' => 'Safaris listing',
    '/en/safaris/8-day-tanzania-luxury-safari-experience' => 'Safari #2 detail',
    '/en/safaris/6-day-tanzania-wildlife-safari-adventure' => 'Safari #3 detail',
    '/en/destinations' => 'Destinations',
    '/en/contact' => 'Contact',
];

$issues = 0;

foreach ($pages as $url => $name) {
    echo "Testing: {$name} ({$url})\n";
    
    $request = \Illuminate\Http\Request::create($url, 'GET');
    
    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $contentType = $response->headers->get('Content-Type', '');
        $content = $response->getContent();
        
        // Check status
        if ($status !== 200) {
            echo "  ⚠ Status: {$status}\n";
            $issues++;
            continue;
        }
        
        // Check Content-Type header
        $hasCharset = str_contains($contentType, 'charset=UTF-8') || str_contains($contentType, 'charset=utf-8');
        echo "  Content-Type: {$contentType} " . ($hasCharset ? '✓' : '⚠ missing charset') . "\n";
        if (!$hasCharset) $issues++;
        
        // Check for mojibake patterns in rendered output
        $mojibake = [
            'â€"', 'â€"', 'â€™', 'â€˜', 'â€œ',
            'Ã©', 'Ã¨', 'Ãª', 'Ã§', 'Ã®', 'Ã±', 'Ã³',
            'Ã¢â‚¬', 'Ã¢â€¢',
        ];
        
        $foundMojibake = [];
        foreach ($mojibake as $pat) {
            if (strpos($content, $pat) !== false) {
                $foundMojibake[] = $pat;
            }
        }
        
        if (empty($foundMojibake)) {
            echo "  ✓ No mojibake detected (content: " . number_format(strlen($content)) . " bytes)\n";
        } else {
            echo "  ⚠ Mojibake found: " . implode(', ', $foundMojibake) . "\n";
            $issues++;
        }
        
        // Check meta charset
        if (strpos($content, '<meta charset="UTF-8">') !== false || strpos($content, '<meta charset="utf-8">') !== false) {
            echo "  ✓ Meta charset present\n";
        }
        
    } catch (\Throwable $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
        $issues++;
    }
    
    echo "\n";
    
    // Reset app state for next request
    $app->make('Illuminate\Contracts\Http\Kernel')->terminate($request, $response ?? null);
}

echo "═══════════════════════════════════════\n";
echo $issues === 0 
    ? "✓ All pages pass encoding verification!\n" 
    : "⚠ {$issues} issues found — review above\n";
echo "═══════════════════════════════════════\n";
