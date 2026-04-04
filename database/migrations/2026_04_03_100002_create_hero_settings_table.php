<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('background_video', 500)->nullable();
            $table->string('video_poster', 500)->nullable();
            $table->decimal('overlay_opacity', 3, 2)->default(0.50);
            $table->boolean('autoplay')->default(true);
            $table->unsignedInteger('transition_speed')->default(5000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
