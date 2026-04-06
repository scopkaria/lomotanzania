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
