<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tour Types — translation columns + SEO fields
        Schema::table('tour_types', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
        });

        // Categories — translation columns + SEO fields
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
        });

        // Accommodations — translation columns + SEO fields + slug
        Schema::table('accommodations', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
        });

        // Itineraries — translation columns for title and description
        Schema::table('itineraries', function (Blueprint $table) {
            $table->json('title_translations')->nullable()->after('title');
            $table->json('description_translations')->nullable()->after('description');
        });

        // Safari Packages — translation columns for section headings
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->json('highlights_title_translations')->nullable()->after('highlights_title');
            $table->json('highlights_intro_translations')->nullable()->after('highlights_intro');
            $table->json('inclusions_title_translations')->nullable()->after('inclusions_title');
            $table->json('inclusions_intro_translations')->nullable()->after('inclusions_intro');
            $table->json('overview_title_translations')->nullable()->after('overview_title');
        });
    }

    public function down(): void
    {
        Schema::table('tour_types', function (Blueprint $table) {
            $table->dropColumn(['name_translations', 'description_translations', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_translations', 'description_translations', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        });

        Schema::table('accommodations', function (Blueprint $table) {
            $table->dropColumn(['slug', 'name_translations', 'description_translations', 'meta_title', 'meta_description', 'meta_keywords', 'og_image']);
        });

        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn(['title_translations', 'description_translations']);
        });

        Schema::table('safari_packages', function (Blueprint $table) {
            $table->dropColumn(['highlights_title_translations', 'highlights_intro_translations', 'inclusions_title_translations', 'inclusions_intro_translations', 'overview_title_translations']);
        });
    }
};
