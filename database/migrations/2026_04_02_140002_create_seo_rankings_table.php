<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_rankings', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 255);
            $table->string('url', 500);
            $table->unsignedSmallInteger('position')->nullable();     // current rank
            $table->unsignedSmallInteger('previous_position')->nullable();
            $table->string('search_engine', 20)->default('google');
            $table->string('locale', 5)->default('en');
            $table->json('history')->nullable();      // [{date, position}, ...]
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();

            $table->index(['keyword', 'locale']);
            $table->index('last_checked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_rankings');
    }
};
