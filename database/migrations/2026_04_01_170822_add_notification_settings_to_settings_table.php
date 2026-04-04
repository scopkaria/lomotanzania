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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('notification_email')->nullable()->after('logo_path');
            $table->boolean('notify_inquiry')->default(true)->after('notification_email');
            $table->boolean('notify_safari_request')->default(true)->after('notify_inquiry');
            $table->boolean('notify_safari_plan')->default(true)->after('notify_safari_request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['notification_email', 'notify_inquiry', 'notify_safari_request', 'notify_safari_plan']);
        });
    }
};
