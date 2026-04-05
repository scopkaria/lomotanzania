<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'favicon_path')) {
                $table->string('favicon_path')->nullable()->after('logo_path');
            }

            if (! Schema::hasColumn('settings', 'logo_width')) {
                $table->unsignedInteger('logo_width')->default(176)->after('favicon_path');
            }

            if (! Schema::hasColumn('settings', 'header_color')) {
                $table->string('header_color', 20)->default('#083321')->after('logo_width');
            }

            if (! Schema::hasColumn('settings', 'bing_webmaster_code')) {
                $table->string('bing_webmaster_code')->nullable()->after('google_search_console');
            }

            if (! Schema::hasColumn('settings', 'yandex_verification_code')) {
                $table->string('yandex_verification_code')->nullable()->after('bing_webmaster_code');
            }

            if (! Schema::hasColumn('settings', 'baidu_verification_code')) {
                $table->string('baidu_verification_code')->nullable()->after('yandex_verification_code');
            }

            if (! Schema::hasColumn('settings', 'tripadvisor_url')) {
                $table->string('tripadvisor_url', 500)->nullable()->after('whatsapp_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'favicon_path',
                'logo_width',
                'header_color',
                'bing_webmaster_code',
                'yandex_verification_code',
                'baidu_verification_code',
                'tripadvisor_url',
            ];

            $existing = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('settings', $column)));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
