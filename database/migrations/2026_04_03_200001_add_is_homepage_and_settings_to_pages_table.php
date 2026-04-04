<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('is_homepage')->default(false)->after('type');
            $table->string('layout', 50)->default('full_width')->after('template');
            $table->string('bg_color', 20)->nullable()->after('layout');
            $table->string('section_spacing', 20)->default('normal')->after('bg_color');
        });

        // Mark existing homepage page
        \App\Models\Page::where('type', 'homepage')->update(['is_homepage' => true]);
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['is_homepage', 'layout', 'bg_color', 'section_spacing']);
        });
    }
};
