<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Global SEO settings
        Schema::table('settings', function (Blueprint $table) {
            $table->string('meta_description', 500)->nullable()->after('tagline');
            $table->string('default_og_image')->nullable()->after('meta_description');
            $table->string('google_analytics_id')->nullable()->after('default_og_image');
            $table->string('google_search_console')->nullable()->after('google_analytics_id');
        });

        // Safari packages
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('status');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });

        // Destinations
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('longitude');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });

        // Countries
        Schema::table('countries', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('longitude');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });

        // Posts (blog) — already have json 'meta' column, add explicit SEO columns
        Schema::table('posts', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('meta');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });

        // Pages — already have json 'meta' column, add explicit SEO columns
        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('meta');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('meta_keywords');
        });
    }

    public function down(): void
    {
        $tables = ['settings', 'safari_packages', 'destinations', 'countries', 'posts', 'pages'];

        foreach ($tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                if ($tbl === 'settings') {
                    $table->dropColumn(['meta_description', 'default_og_image', 'google_analytics_id', 'google_search_console']);
                } else {
                    $table->dropColumn(['meta_title', 'meta_description', 'meta_keywords', 'og_image']);
                }
            });
        }
    }
};
