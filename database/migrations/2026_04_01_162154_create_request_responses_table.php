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
        Schema::create('request_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('safari_requests')->cascadeOnDelete();
            $table->string('safari_title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, accepted, declined
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_responses');
    }
};
