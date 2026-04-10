<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

Schema::table('safari_packages', function ($table) {
    $table->text('short_description')->nullable()->change();
});

echo "✓ short_description column changed to TEXT\n";
