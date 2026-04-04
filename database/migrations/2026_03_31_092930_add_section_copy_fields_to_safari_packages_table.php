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
            $table->string('highlights_title')->nullable()->after('excluded');
            $table->string('highlights_intro', 500)->nullable()->after('highlights_title');
            $table->string('inclusions_title')->nullable()->after('highlights_intro');
            $table->string('inclusions_intro', 500)->nullable()->after('inclusions_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->dropColumn([
                'highlights_title',
                'highlights_intro',
                'inclusions_title',
                'inclusions_intro',
            ]);
        });
    }
};
