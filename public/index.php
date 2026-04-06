<?php

// === TEMPORARY DEBUG — remove after fixing ===
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Quick raw diagnostic (bypasses Laravel entirely)
if (isset($_GET['raw_debug'])) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "PHP " . PHP_VERSION . "\n";
    echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
    echo "Doc Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . "\n";
    echo "Script: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'unknown') . "\n";
    echo "CWD: " . getcwd() . "\n";
    echo "index.php dir: " . __DIR__ . "\n";
    echo "Parent dir: " . realpath(__DIR__ . '/..') . "\n\n";

    echo "--- File Checks ---\n";
    echo ".env exists: " . (file_exists(__DIR__ . '/../.env') ? 'YES' : 'NO') . "\n";
    echo "vendor/autoload.php: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'YES' : 'NO') . "\n";
    echo "bootstrap/app.php: " . (file_exists(__DIR__ . '/../bootstrap/app.php') ? 'YES' : 'NO') . "\n";
    echo "build/manifest.json: " . (file_exists(__DIR__ . '/build/manifest.json') ? 'YES' : 'NO') . "\n";
    echo "storage symlink: " . (is_dir(__DIR__ . '/storage') ? 'YES' : 'NO') . "\n\n";

    echo "--- Directory Permissions ---\n";
    foreach (['storage', 'storage/logs', 'storage/framework', 'storage/framework/views', 'storage/framework/sessions', 'storage/framework/cache', 'bootstrap/cache'] as $d) {
        $p = __DIR__ . '/../' . $d;
        $exists = is_dir($p) ? 'exists' : 'MISSING';
        $writable = is_writable($p) ? 'writable' : 'NOT writable';
        echo "$d: $exists, $writable\n";
    }

    echo "\n--- .env Contents ---\n";
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $env = file_get_contents($envFile);

        // Show DB_PASSWORD debug (length + first/last char only)
        if (preg_match('/^DB_PASSWORD=(.*)$/m', $env, $pm)) {
            $pw = $pm[1];
            $pwLen = strlen($pw);
            echo "\n--- DB PASSWORD DEBUG ---\n";
            echo "Raw DB_PASSWORD line: DB_PASSWORD=$pw\n";
            echo "Length: $pwLen\n";
            echo "Starts with quote: " . (str_starts_with($pw, '"') || str_starts_with($pw, "'") ? 'YES' : 'NO') . "\n";
            echo "Has special chars: " . (preg_match('/[#$(){}!@&|<> ]/', $pw) ? 'YES — MUST wrap in double quotes' : 'NO') . "\n\n";
        } else {
            echo "\n--- DB PASSWORD DEBUG ---\n";
            echo "DB_PASSWORD line NOT FOUND in .env!\n\n";
        }

        // Hide sensitive values for display
        $env = preg_replace('/^(DB_PASSWORD|APP_KEY|MAIL_PASSWORD|AWS_SECRET)=(.+)$/m', '$1=***HIDDEN***', $env);
        echo $env;
    } else {
        echo ".env NOT FOUND\n";
    }

    // Check for cached config
    echo "\n--- Config Cache Check ---\n";
    $configCache = __DIR__ . '/../bootstrap/cache/config.php';
    if (file_exists($configCache)) {
        echo "⚠️  bootstrap/cache/config.php EXISTS — this overrides .env!\n";
        echo "Deleting it now...\n";
        unlink($configCache);
        echo "Deleted. Reload the page.\n";
    } else {
        echo "No config cache (good)\n";
    }

    echo "\n--- Laravel Log (ERRORS only, last 500 lines scanned) ---\n";
    $log = __DIR__ . '/../storage/logs/laravel.log';
    if (file_exists($log) && filesize($log) > 0) {
        $lines = file($log);
        $tail = array_slice($lines, -500);
        // Extract just the ERROR lines (not full traces)
        $errors = [];
        foreach ($tail as $line) {
            if (preg_match('/production\.ERROR:|local\.ERROR:|EMERGENCY:|CRITICAL:/', $line)) {
                $errors[] = trim($line);
            }
        }
        if ($errors) {
            echo implode("\n\n", array_slice($errors, -10));
        } else {
            echo "No ERROR lines found in last 500 lines\n";
            echo "\n--- Raw last 80 lines ---\n";
            echo implode('', array_slice($lines, -80));
        }
    } else {
        echo "No log file\n";
    }
    exit;
}

// Storage / image diagnostic
if (isset($_GET['storage_debug'])) {
    header('Content-Type: text/plain; charset=utf-8');
    $base = realpath(__DIR__ . '/..');
    $publicStorage = __DIR__ . '/storage';
    $actualStorage = $base . '/storage/app/public';

    echo "=== STORAGE SYMLINK DIAGNOSTIC ===\n\n";

    // 1. Check public/storage
    echo "1. public/storage exists: " . (file_exists($publicStorage) ? 'YES' : 'NO') . "\n";
    echo "   Is symlink: " . (is_link($publicStorage) ? 'YES' : 'NO') . "\n";
    echo "   Is directory: " . (is_dir($publicStorage) ? 'YES' : 'NO') . "\n";
    if (is_link($publicStorage)) {
        echo "   Symlink target: " . readlink($publicStorage) . "\n";
        echo "   Resolved path: " . realpath($publicStorage) . "\n";
    }

    // 2. Check storage/app/public
    echo "\n2. storage/app/public exists: " . (is_dir($actualStorage) ? 'YES' : 'NO') . "\n";
    echo "   Writable: " . (is_writable($actualStorage) ? 'YES' : 'NO') . "\n";

    // 3. Check media subdirectory
    $mediaDir = $actualStorage . '/media';
    echo "\n3. storage/app/public/media/ exists: " . (is_dir($mediaDir) ? 'YES' : 'NO') . "\n";
    if (is_dir($mediaDir)) {
        echo "   Writable: " . (is_writable($mediaDir) ? 'YES' : 'NO') . "\n";
        $files = scandir($mediaDir);
        $files = array_diff($files, ['.', '..', '.gitignore']);
        echo "   Files (" . count($files) . "):\n";
        foreach ($files as $f) {
            $fp = $mediaDir . '/' . $f;
            $size = filesize($fp);
            $readable = is_readable($fp) ? 'readable' : 'NOT readable';
            echo "     - $f ($size bytes, $readable)\n";
        }
    }

    // 4. Check the specific broken file
    $brokenFile = 'media/LYqkjVtuKY1ZoinnvAMVEeXsNgsXVn4o1oqhnURf.png';
    echo "\n4. Broken file check: $brokenFile\n";
    echo "   In storage/app/public: " . (file_exists($actualStorage . '/' . $brokenFile) ? 'EXISTS' : 'MISSING') . "\n";
    echo "   Via public/storage: " . (file_exists($publicStorage . '/' . $brokenFile) ? 'EXISTS' : 'MISSING') . "\n";

    // 5. Compare: list ALL subdirs in storage/app/public
    echo "\n5. All directories in storage/app/public/:\n";
    if (is_dir($actualStorage)) {
        $items = scandir($actualStorage);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $p = $actualStorage . '/' . $item;
            $type = is_dir($p) ? 'DIR' : 'FILE';
            $rp = is_readable($p) ? '' : ' [NOT READABLE]';
            echo "   [$type] $item$rp\n";
        }
    }

    // 6. Test symlink by writing and reading
    echo "\n6. Symlink write test:\n";
    $testFile = $actualStorage . '/.symlink_test_' . time();
    $testContent = 'symlink_test';
    if (@file_put_contents($testFile, $testContent)) {
        echo "   Wrote test file to storage/app/public/ : OK\n";
        $viaPublic = $publicStorage . '/' . basename($testFile);
        if (file_exists($viaPublic) && file_get_contents($viaPublic) === $testContent) {
            echo "   Read via public/storage/ : OK — SYMLINK WORKS\n";
        } else {
            echo "   Read via public/storage/ : FAILED — SYMLINK IS BROKEN!\n";
            echo "   public/storage is likely a real directory, not a symlink.\n";
        }
        @unlink($testFile);
    } else {
        echo "   Could not write test file — storage not writable!\n";
    }

    // 7. Fix option
    if (isset($_GET['fix_symlink'])) {
        echo "\n=== ATTEMPTING SYMLINK FIX ===\n";
        if (is_dir($publicStorage) && !is_link($publicStorage)) {
            // It's a real directory — need to remove it and create symlink
            // First backup any files that exist only here
            echo "public/storage is a real directory. Removing and creating symlink...\n";
            // Remove the directory (it should only have git-tracked files that also exist in storage/app/public)
            $cmd = 'rm -rf ' . escapeshellarg($publicStorage);
            exec($cmd, $output, $ret);
            echo "Removed directory: " . ($ret === 0 ? 'OK' : "FAILED (code $ret)") . "\n";
        }
        if (file_exists($publicStorage)) {
            @unlink($publicStorage); // remove if it's a broken symlink
        }
        if (!file_exists($publicStorage)) {
            if (symlink($actualStorage, $publicStorage)) {
                echo "Created symlink: public/storage -> $actualStorage\n";
                echo "TEST: " . (is_dir($publicStorage) ? 'WORKING' : 'FAILED') . "\n";
            } else {
                echo "symlink() failed. Trying relative path...\n";
                if (symlink('../storage/app/public', $publicStorage)) {
                    echo "Created relative symlink: public/storage -> ../storage/app/public\n";
                    echo "TEST: " . (is_dir($publicStorage) ? 'WORKING' : 'FAILED') . "\n";
                } else {
                    echo "FAILED — symlink() is not available. Contact hosting support.\n";
                }
            }
        } else {
            echo "public/storage still exists, cannot recreate.\n";
        }
    } else {
        echo "\n--- To fix, visit: ?storage_debug&fix_symlink ---\n";
    }

    exit;
}
// === END TEMPORARY DEBUG ===

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
