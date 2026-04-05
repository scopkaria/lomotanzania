<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('slug')->nullable()->unique();
            $table->string('url')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('menu_items')->insert([
            ['label' => 'Home', 'slug' => 'home', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Destinations', 'slug' => 'destinations', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Safaris', 'slug' => 'safaris', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Experiences', 'slug' => 'experiences', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Blog', 'slug' => 'blog', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'About', 'slug' => 'about', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Contact', 'slug' => 'contact', 'is_enabled' => true, 'open_in_new_tab' => false, 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
