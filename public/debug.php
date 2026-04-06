<?php
/**
 * DIAGNOSTIC SCRIPT — Visit this in browser to find the 500 error cause.
 * DELETE THIS FILE after debugging!
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: text/html; charset=utf-8');
echo '<html><head><title>Debug</title><style>body{font-family:monospace;background:#111;color:#eee;padding:20px;max-width:900px;margin:0 auto}';
echo '.ok{color:#4f4}.err{color:#f55}.warn{color:#fc5}h1{color:#FEBC11}pre{background:#222;padding:12px;border-radius:6px;overflow-x:auto;font-size:13px;max-height:400px;overflow-y:auto}</style></head><body>';
echo '<h1>Lomo Diagnostics</h1>';

$checks = [];

// 1. PHP version
$phpVer = PHP_VERSION;
$checks[] = version_compare($phpVer, '8.2', '>=')
    ? "<span class='ok'>✅ PHP $phpVer</span>"
    : "<span class='err'>❌ PHP $phpVer — Laravel requires 8.2+</span>";

// 2. Required extensions
foreach (['pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'curl'] as $ext) {
    $checks[] = extension_loaded($ext)
        ? "<span class='ok'>✅ ext-$ext loaded</span>"
        : "<span class='err'>❌ ext-$ext MISSING</span>";
}

// 3. .env file
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $checks[] = "<span class='ok'>✅ .env exists</span>";
    $env = file_get_contents($envPath);

    // Check APP_KEY
    if (preg_match('/^APP_KEY=(.+)$/m', $env, $m)) {
        $key = trim($m[1]);
        $checks[] = strlen($key) > 10
            ? "<span class='ok'>✅ APP_KEY is set (" . substr($key, 0, 15) . "...)</span>"
            : "<span class='err'>❌ APP_KEY looks empty or short</span>";
    } else {
        $checks[] = "<span class='err'>❌ APP_KEY not found in .env</span>";
    }

    // Check APP_URL
    if (preg_match('/^APP_URL=(.+)$/m', $env, $m)) {
        $checks[] = "<span class='ok'>✅ APP_URL = " . htmlspecialchars(trim($m[1])) . "</span>";
    }

    // Check DB
    if (preg_match('/^DB_DATABASE=(.+)$/m', $env, $m)) {
        $checks[] = "<span class='ok'>✅ DB_DATABASE = " . htmlspecialchars(trim($m[1])) . "</span>";
    }

    // Check APP_DEBUG
    if (preg_match('/^APP_DEBUG=(.+)$/m', $env, $m)) {
        $val = trim($m[1]);
        $checks[] = $val === 'true'
            ? "<span class='warn'>⚠️ APP_DEBUG=true (shows errors, good for now)</span>"
            : "<span class='warn'>⚠️ APP_DEBUG=false (won't show errors — set to true temporarily to debug)</span>";
    }
} else {
    if (file_exists(__DIR__ . '/../.env.production')) {
        copy(__DIR__ . '/../.env.production', __DIR__ . '/../.env');
        $checks[] = "<span class='ok'>✅ Copied .env.production → .env (edit your DB credentials!)</span>";
    } else {
        $checks[] = "<span class='err'>❌ .env file MISSING — create it from .env.production template</span>";
    }
}

// 4. Vendor
$checks[] = file_exists(__DIR__ . '/../vendor/autoload.php')
    ? "<span class='ok'>✅ vendor/autoload.php exists</span>"
    : "<span class='err'>❌ vendor/autoload.php MISSING — git pull may have failed</span>";

// 5. Build assets
$checks[] = file_exists(__DIR__ . '/build/manifest.json')
    ? "<span class='ok'>✅ public/build/manifest.json exists</span>"
    : "<span class='err'>❌ public/build/manifest.json MISSING</span>";

// 6. Storage symlink
$publicStorage = __DIR__ . '/storage';
if (is_link($publicStorage) || is_dir($publicStorage)) {
    $checks[] = "<span class='ok'>✅ public/storage symlink exists</span>";
} else {
    $checks[] = "<span class='err'>❌ public/storage symlink MISSING</span>";
}

// 7. Writable directories
foreach (['storage/logs', 'storage/framework/views', 'storage/framework/sessions', 'storage/framework/cache', 'bootstrap/cache'] as $dir) {
    $path = __DIR__ . '/../' . $dir;
    if (!is_dir($path)) {
        $checks[] = "<span class='err'>❌ $dir directory MISSING</span>";
    } elseif (!is_writable($path)) {
        $checks[] = "<span class='err'>❌ $dir NOT WRITABLE — chmod 775</span>";
    } else {
        $checks[] = "<span class='ok'>✅ $dir writable</span>";
    }
}

// 8. Try to boot Laravel
echo '<h2>Environment Checks</h2>';
foreach ($checks as $c) echo "<div>$c</div>";

echo '<h2>Laravel Boot Test</h2>';
try {
    require __DIR__ . '/../vendor/autoload.php';

    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "<span class='ok'>✅ Laravel booted successfully!</span><br>";

    // Test DB
    try {
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "<span class='ok'>✅ Database connection OK (" . \Illuminate\Support\Facades\DB::connection()->getDatabaseName() . ")</span><br>";
    } catch (\Throwable $e) {
        echo "<span class='err'>❌ Database connection FAILED: " . htmlspecialchars($e->getMessage()) . "</span><br>";
    }

    // Check for cached config pointing to wrong paths
    $configCache = __DIR__ . '/../bootstrap/cache/config.php';
    if (file_exists($configCache)) {
        $cached = file_get_contents($configCache);
        if (strpos($cached, 'wamp64') !== false || strpos($cached, 'C:\\') !== false) {
            echo "<span class='err'>❌ bootstrap/cache/config.php has LOCAL Windows paths! Deleting it...</span><br>";
            unlink($configCache);
            echo "<span class='ok'>✅ Deleted stale config cache</span><br>";
        }
    }

    // Check route cache
    $routeCache = __DIR__ . '/../bootstrap/cache/routes-v7.php';
    if (file_exists($routeCache)) {
        $cached = file_get_contents($routeCache);
        if (strpos($cached, 'wamp64') !== false || strpos($cached, 'C:\\') !== false) {
            echo "<span class='err'>❌ bootstrap/cache/routes cache has LOCAL paths! Deleting it...</span><br>";
            unlink($routeCache);
            echo "<span class='ok'>✅ Deleted stale route cache</span><br>";
        }
    }

} catch (\Throwable $e) {
    echo "<span class='err'>❌ Laravel FAILED to boot:</span><br>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// 9. Check Laravel log
echo '<h2>Recent Log Errors</h2>';
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile) && filesize($logFile) > 0) {
    $lines = file($logFile);
    $tail = array_slice($lines, -80);
    echo '<pre>' . htmlspecialchars(implode('', $tail)) . '</pre>';
} else {
    echo "<span class='warn'>⚠️ No log file yet (storage/logs/laravel.log)</span>";
}

echo '<br><p style="color:#f66"><strong>⚠️ DELETE THIS FILE after debugging!</strong></p>';
echo '</body></html>';
