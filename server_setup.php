<?php
/**
 * ONE-TIME SERVER SETUP SCRIPT
 * 
 * Upload this to your server root and visit it once in the browser:
 *   https://yourdomain.com/server_setup.php
 * 
 * After running successfully, DELETE THIS FILE from the server for security.
 */

// Prevent running if not accessed directly
if (php_sapi_name() === 'cli') {
    echo "Run this from the browser, not CLI.\n";
    exit(1);
}

$results = [];

// 1. Create storage symlink (public/storage -> storage/app/public)
$publicStorage = __DIR__ . '/public/storage';
$storageTarget = __DIR__ . '/storage/app/public';

if (is_link($publicStorage) || is_dir($publicStorage)) {
    $results[] = '✅ public/storage symlink already exists';
} else {
    if (symlink($storageTarget, $publicStorage)) {
        $results[] = '✅ Created public/storage symlink';
    } else {
        $results[] = '❌ Failed to create symlink — try SSH: ln -s ../storage/app/public public/storage';
    }
}

// 2. Ensure writable directories exist
$dirs = [
    'storage/app/public',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];

foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
        $results[] = "✅ Created $dir";
    }
    if (is_writable($path)) {
        $results[] = "✅ $dir is writable";
    } else {
        chmod($path, 0775);
        $results[] = is_writable($path)
            ? "✅ Fixed permissions on $dir"
            : "❌ $dir is NOT writable — set permissions to 775 manually";
    }
}

// 3. Check .env exists
if (file_exists(__DIR__ . '/.env')) {
    $results[] = '✅ .env file exists';
} else {
    if (file_exists(__DIR__ . '/.env.production')) {
        copy(__DIR__ . '/.env.production', __DIR__ . '/.env');
        $results[] = '✅ Copied .env.production → .env (edit your DB credentials!)';
    } else {
        $results[] = '❌ .env file missing — create it from .env.production template';
    }
}

// 4. Check vendor/autoload.php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $results[] = '✅ vendor/autoload.php exists';
} else {
    $results[] = '❌ vendor directory missing — git pull may have failed';
}

// 5. Check public/build/manifest.json
if (file_exists(__DIR__ . '/public/build/manifest.json')) {
    $results[] = '✅ Build assets present';
} else {
    $results[] = '❌ public/build missing — git pull may have failed';
}

// 6. Try to clear Laravel caches (requires working .env + vendor)
if (file_exists(__DIR__ . '/.env') && file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        require __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        $results[] = '✅ Config cache cleared';
        
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $results[] = '✅ Route cache cleared';
        
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $results[] = '✅ View cache cleared';
        
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        $results[] = '✅ Config cached for production';
        
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        $results[] = '✅ Routes cached for production';
        
        \Illuminate\Support\Facades\Artisan::call('view:cache');
        $results[] = '✅ Views cached for production';
        
    } catch (\Throwable $e) {
        $results[] = '⚠️ Cache commands failed: ' . $e->getMessage();
    }
}

// Output results
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><title>Server Setup</title>';
echo '<style>body{font-family:monospace;max-width:700px;margin:40px auto;background:#111;color:#eee;padding:20px}';
echo 'h1{color:#FEBC11}.r{padding:6px 0;border-bottom:1px solid #333}</style></head>';
echo '<body><h1>Lomo Server Setup</h1>';
foreach ($results as $r) {
    echo '<div class="r">' . htmlspecialchars($r, ENT_QUOTES, 'UTF-8') . '</div>';
}
echo '<br><p style="color:#f66"><strong>⚠️ DELETE THIS FILE after setup is complete!</strong></p>';
echo '</body></html>';
