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
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->foreignId('tour_type_id')->nullable()->constrained('tour_types')->nullOnDelete()->after('difficulty');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->after('tour_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->dropForeign(['tour_type_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['tour_type_id', 'category_id']);
        });
    }
};
