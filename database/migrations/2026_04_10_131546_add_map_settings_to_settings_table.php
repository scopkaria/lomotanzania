<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('map_latitude', 30)->nullable()->after('tripadvisor_url');
            $table->string('map_longitude', 30)->nullable()->after('map_latitude');
            $table->text('map_embed')->nullable()->after('map_longitude');
            $table->string('office_location', 255)->nullable()->after('map_embed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['map_latitude', 'map_longitude', 'map_embed', 'office_location']);
        });
    }
};
