<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained()->cascadeOnDelete();
            $table->json('label')->nullable();
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->string('image')->nullable();
            $table->json('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->json('next_up_text')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('bg_image')->nullable();
            $table->string('image_alt')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['page_section_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
