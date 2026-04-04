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
        Schema::create('country_safari_package', function (Blueprint $table) {
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('safari_package_id')->constrained()->cascadeOnDelete();
            $table->primary(['country_id', 'safari_package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_safari_package');
    }
};
