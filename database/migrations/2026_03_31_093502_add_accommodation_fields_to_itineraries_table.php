<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->foreignId('accommodation_id')->nullable()->after('destination_id')->constrained()->nullOnDelete();
            $table->string('image_path')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('accommodation_id');
            $table->dropColumn('image_path');
        });
    }
};