<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('profile_image')->nullable()->after('phone');
            $table->string('pending_email')->nullable()->after('profile_image');
            $table->string('email_change_token')->nullable()->after('pending_email');
            $table->string('language', 10)->default('en')->after('role');
            $table->string('theme', 20)->default('light')->after('language');
            $table->json('notification_preferences')->nullable()->after('theme');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'profile_image', 'pending_email',
                'email_change_token', 'language', 'theme',
                'notification_preferences',
            ]);
        });
    }
};
