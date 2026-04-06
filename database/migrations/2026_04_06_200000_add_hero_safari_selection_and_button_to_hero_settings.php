<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            $table->json('hero_safari_ids')->nullable()->after('transition_speed');
            $table->json('button_text')->nullable()->after('hero_safari_ids');
            $table->string('button_link', 500)->nullable()->after('button_text');
        });
    }

    public function down(): void
    {
        Schema::table('hero_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_safari_ids', 'button_text', 'button_link']);
        });
    }
};
