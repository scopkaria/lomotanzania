<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->string('overview_title')->nullable()->after('description');
            $table->json('seasonal_pricing')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->dropColumn(['overview_title', 'seasonal_pricing']);
        });
    }
};