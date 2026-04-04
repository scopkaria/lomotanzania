<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);          // English, French, etc.
            $table->string('code', 5)->unique();  // en, fr, de, es
            $table->string('native_name', 50)->nullable(); // English, Français, Deutsch, Español
            $table->string('flag', 10)->nullable();  // emoji flag
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default languages
        DB::table('languages')->insert([
            ['name' => 'English',  'code' => 'en', 'native_name' => 'English',  'flag' => '🇬🇧', 'is_default' => true,  'is_active' => true, 'sort_order' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'French',   'code' => 'fr', 'native_name' => 'Français', 'flag' => '🇫🇷', 'is_default' => false, 'is_active' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'German',   'code' => 'de', 'native_name' => 'Deutsch',  'flag' => '🇩🇪', 'is_default' => false, 'is_active' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spanish',  'code' => 'es', 'native_name' => 'Español',  'flag' => '🇪🇸', 'is_default' => false, 'is_active' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
