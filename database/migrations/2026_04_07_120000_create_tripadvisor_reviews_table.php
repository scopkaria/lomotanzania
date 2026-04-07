<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tripadvisor_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('tripadvisor_id', 64)->unique();
            $table->string('reviewer_name');
            $table->string('reviewer_location')->nullable();
            $table->string('reviewer_avatar')->nullable();
            $table->text('title')->nullable();
            $table->text('review_text');
            $table->unsignedTinyInteger('rating');
            $table->date('review_date')->nullable();
            $table->string('trip_type')->nullable();
            $table->boolean('published')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('published');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tripadvisor_reviews');
    }
};
