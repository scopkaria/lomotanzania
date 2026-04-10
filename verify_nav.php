<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::create('/en', 'GET'));
$html = $response->getContent();

echo "Status: " . $response->getStatusCode() . "\n";

// Check translation rendered
echo "Has 'Popular Safaris': " . (str_contains($html, 'Popular Safaris') ? 'YES' : 'NO') . "\n";
echo "Has 'MESSAGES.POPULAR_SAFARIS': " . (str_contains($html, 'MESSAGES.POPULAR_SAFARIS') ? 'BUG!' : 'CLEAN') . "\n";
echo "Has 'messages.popular_safaris': " . (str_contains($html, 'messages.popular_safaris') ? 'BUG!' : 'CLEAN') . "\n";

// Check underline CSS
echo "Has 'width: 24px': " . (str_contains($html, 'width: 24px') ? 'YES' : 'NO') . "\n";
echo "Has 'translateX(-50%)': " . (str_contains($html, 'translateX(-50%)') ? 'YES' : 'NO') . "\n";

// Check logo mr-auto
echo "Has 'mr-auto': " . (str_contains($html, 'mr-auto') ? 'YES' : 'NO') . "\n";

// Check border-r dividers
echo "Has 'border-r border-gray-100': " . (str_contains($html, 'border-r border-gray-100') ? 'YES' : 'NO') . "\n";

$kernel->terminate($request, $response);
