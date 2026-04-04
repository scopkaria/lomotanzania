<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SEO Domination System — all new tables for programmatic pages,
 * keyword strategy, internal linking, image SEO, GEO markets.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ----- Part 2: Programmatic SEO Pages -----
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type', 50);                   // country, destination, duration, combo, market
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('intro_content')->nullable();     // dynamic intro paragraph
            $table->text('body_content')->nullable();      // full page body (AI-generated or manual)
            $table->string('featured_image')->nullable();
            $table->json('filter_criteria')->nullable();   // {country_slug, destination_slug, tour_type_slug, duration_range, etc.}
            $table->json('title_translations')->nullable();
            $table->json('intro_translations')->nullable();
            $table->json('body_translations')->nullable();
            $table->boolean('is_auto_generated')->default(true);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('type');
            $table->index('is_published');
        });

        // ----- Part 6: Keyword Strategy Engine -----
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('intent', 30)->default('informational'); // informational, transactional, local, navigational
            $table->unsignedInteger('volume')->nullable();          // search volume estimate
            $table->unsignedTinyInteger('difficulty')->nullable();   // 0-100
            $table->string('target_url', 500)->nullable();          // the page targeting this keyword
            $table->string('country', 50)->nullable();              // country context
            $table->string('group', 100)->nullable();               // grouping label
            $table->unsignedTinyInteger('priority')->default(50);   // 0-100
            $table->timestamps();

            $table->index(['intent', 'priority']);
            $table->index('keyword');
        });

        // ----- Part 3: Internal Linking Dictionary -----
        Schema::create('seo_link_rules', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');                    // e.g. "Serengeti"
            $table->string('url', 500);                   // e.g. /en/destinations/serengeti
            $table->string('anchor_text')->nullable();     // optional custom anchor
            $table->unsignedTinyInteger('priority')->default(50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('keyword');
        });

        // ----- Part 8: Image SEO Metadata -----
        Schema::create('seo_image_meta', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);                  // storage path
            $table->string('alt_text', 500)->nullable();
            $table->string('seo_filename')->nullable();    // optimized filename
            $table->string('caption')->nullable();
            $table->boolean('is_optimized')->default(false);
            $table->unsignedInteger('original_size')->nullable();  // bytes
            $table->unsignedInteger('optimized_size')->nullable();
            $table->timestamps();

            $table->index('path');
        });

        // ----- GEO SEO: Market Pages -----
        Schema::create('seo_markets', function (Blueprint $table) {
            $table->id();
            $table->string('slug');                        // e.g. "tanzania-from-uk"
            $table->string('target_country', 100);         // e.g. "Tanzania"
            $table->string('source_market', 100);          // e.g. "UK"
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->text('intro_content')->nullable();
            $table->text('flights_info')->nullable();
            $table->text('visa_info')->nullable();
            $table->text('travel_tips')->nullable();
            $table->text('best_routes')->nullable();
            $table->text('pricing_info')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('title_translations')->nullable();
            $table->json('intro_translations')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->unique('slug');
            $table->index(['target_country', 'source_market']);
        });

        // ----- Part 11: E-E-A-T Author Profiles -----
        Schema::create('author_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('title')->nullable();           // e.g. "Safari Expert"
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('expertise')->nullable();       // comma-separated
            $table->unsignedInteger('years_experience')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ----- Part 7: Rank Alerts -----
        Schema::create('seo_rank_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_ranking_id')->constrained('seo_rankings')->cascadeOnDelete();
            $table->string('type', 30);                    // drop, gain, new_ranking, lost
            $table->unsignedSmallInteger('old_position')->nullable();
            $table->unsignedSmallInteger('new_position')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index('is_read');
        });

        // Add author_profile_id to posts
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('author_profile_id')->nullable()->after('user_id')->constrained('author_profiles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('author_profile_id');
        });
        Schema::dropIfExists('seo_rank_alerts');
        Schema::dropIfExists('author_profiles');
        Schema::dropIfExists('seo_markets');
        Schema::dropIfExists('seo_image_meta');
        Schema::dropIfExists('seo_link_rules');
        Schema::dropIfExists('seo_keywords');
        Schema::dropIfExists('seo_pages');
    }
};
