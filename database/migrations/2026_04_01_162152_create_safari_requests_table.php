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
        Schema::create('safari_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->string('country')->nullable();
            $table->date('travel_date');
            $table->unsignedSmallInteger('people')->default(1);
            $table->json('destinations')->nullable();
            $table->json('activities')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('new'); // new, processing, completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_requests');
    }
};
