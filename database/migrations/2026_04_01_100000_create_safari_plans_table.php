<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safari_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_package_id')->nullable()->constrained()->nullOnDelete();
            $table->json('destinations')->nullable();
            $table->json('months')->nullable();
            $table->string('travel_group')->nullable();
            $table->json('interests')->nullable();
            $table->string('budget_range')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('country_code', 10)->nullable();
            $table->string('phone', 50)->nullable();
            $table->json('contact_methods')->nullable();
            $table->boolean('wants_updates')->default(false);
            $table->string('know_destination')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safari_plans');
    }
};
