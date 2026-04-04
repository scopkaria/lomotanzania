<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable');   // seoable_type + seoable_id (indexed)
            $table->string('locale', 5)->default('en');
            $table->string('focus_keyword', 255)->nullable();
            $table->string('secondary_keywords', 500)->nullable(); // comma-separated
            $table->string('slug_preview', 255)->nullable();
            $table->unsignedTinyInteger('seo_score')->default(0);           // 0-100
            $table->unsignedTinyInteger('readability_score')->default(0);   // 0-100
            $table->json('analysis_data')->nullable();   // cached analysis results
            $table->timestamp('last_analyzed_at')->nullable();
            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
