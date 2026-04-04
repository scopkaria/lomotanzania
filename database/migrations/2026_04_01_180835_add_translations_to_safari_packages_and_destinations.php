<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Safari Packages — add JSON translation columns
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->json('title_translations')->nullable()->after('title');
            $table->json('short_description_translations')->nullable()->after('short_description');
            $table->json('description_translations')->nullable()->after('description');
            $table->json('highlights_translations')->nullable()->after('highlights');
        });

        // Destinations — add JSON translation columns
        Schema::table('destinations', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->dropColumn([
                'title_translations',
                'short_description_translations',
                'description_translations',
                'highlights_translations',
            ]);
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'name_translations',
                'description_translations',
            ]);
        });
    }
};
