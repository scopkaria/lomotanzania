<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::create('/en', 'GET'));
$status = $response->getStatusCode();
$html = $response->getContent();

echo "Status: $status\n";
echo "cinema-section: " . (str_contains($html, 'exp-cinema-section') ? 'YES' : 'NO') . "\n";
echo "cinema-card: " . (str_contains($html, 'exp-cinema-card') ? 'YES' : 'NO') . "\n";
echo "cinema-img: " . (str_contains($html, 'exp-cinema-img') ? 'YES' : 'NO') . "\n";
echo "expHeadUp: " . (str_contains($html, 'expHeadUp') ? 'YES' : 'NO') . "\n";
echo "IntersectionObserver: " . (str_contains($html, 'IntersectionObserver') ? 'YES' : 'NO') . "\n";
echo "exp-txt-item: " . (str_contains($html, 'exp-txt-item') ? 'YES' : 'NO') . "\n";

// Count how many slides rendered
preg_match_all('/exp-cinema-card/', $html, $m);
echo "Slide count: " . count($m[0]) . "\n";

$kernel->terminate($request, $response);
