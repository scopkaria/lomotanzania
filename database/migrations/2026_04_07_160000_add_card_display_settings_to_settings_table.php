<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('show_card_price_badge')->default(true)->after('notification_sound_volume');
            $table->string('card_price_season', 10)->default('low')->after('show_card_price_badge');
            $table->string('card_price_pax', 10)->default('pax_6')->after('card_price_season');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['show_card_price_badge', 'card_price_season', 'card_price_pax']);
        });
    }
};
