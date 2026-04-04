<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->unsignedInteger('featured_order')->default(0)->after('featured');
            $table->string('featured_label', 100)->nullable()->after('featured_order');
        });
    }

    public function down(): void
    {
        Schema::table('safari_packages', function (Blueprint $table) {
            $table->dropColumn(['featured_order', 'featured_label']);
        });
    }
};
