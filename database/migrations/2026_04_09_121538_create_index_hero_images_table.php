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
        Schema::create('index_hero_images', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 50)->unique(); // blog, safaris, destinations, experiences, countries
            $table->string('label');
            $table->string('image_path')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('index_hero_images');
    }
};
