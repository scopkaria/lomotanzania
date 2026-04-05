<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('google_search_console');
            $table->string('phone_number')->nullable()->after('whatsapp_number');
            $table->string('chat_greeting')->nullable()->after('phone_number');
            $table->boolean('chat_enabled')->default(true)->after('chat_greeting');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_number', 'phone_number', 'chat_greeting', 'chat_enabled']);
        });
    }
};
