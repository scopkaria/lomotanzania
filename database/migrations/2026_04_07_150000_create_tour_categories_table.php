<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ADDED: Tour categories taxonomy (Safari, Trekking, Beach)
        if (!Schema::hasTable('tour_categories')) {
            Schema::create('tour_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug', 100)->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('featured_image')->nullable();
                $table->integer('display_order')->default(0);
                $table->timestamps();
            });
        }

        // ADDED: Pivot table for many-to-many (tours can belong to multiple categories = combo)
        if (!Schema::hasTable('safari_package_tour_category')) {
            Schema::create('safari_package_tour_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('safari_package_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tour_category_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['safari_package_id', 'tour_category_id'], 'sp_tc_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('safari_package_tour_category');
        Schema::dropIfExists('tour_categories');
    }
};
