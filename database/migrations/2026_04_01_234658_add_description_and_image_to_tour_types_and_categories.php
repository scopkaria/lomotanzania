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
        Schema::table('tour_types', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('featured_image')->nullable()->after('description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('featured_image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_types', function (Blueprint $table) {
            $table->dropColumn(['description', 'featured_image']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['description', 'featured_image']);
        });
    }
};
