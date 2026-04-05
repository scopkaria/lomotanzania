<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Departments table
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->string('color', 7)->default('#083321');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Add department_id to users
        if (!Schema::hasColumn('users', 'department_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('department_id')->nullable()->after('role');
                $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            });
        }

        // Add whisper + transfer fields to chat_messages
        if (!Schema::hasColumn('chat_messages', 'message_type')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->string('message_type', 20)->default('normal')->after('sender_type');
                $table->unsignedBigInteger('whisper_to')->nullable()->after('message_type');
                $table->foreign('whisper_to')->references('id')->on('users')->nullOnDelete();
            });
        }

        // Add transfer tracking to chat_sessions
        if (!Schema::hasColumn('chat_sessions', 'transferred_from')) {
            Schema::table('chat_sessions', function (Blueprint $table) {
                $table->unsignedBigInteger('transferred_from')->nullable()->after('assigned_to');
                $table->foreign('transferred_from')->references('id')->on('users')->nullOnDelete();
                $table->text('transfer_note')->nullable()->after('transferred_from');
                $table->unsignedBigInteger('department_id')->nullable()->after('transfer_note');
                $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropForeign(['transferred_from']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['transferred_from', 'transfer_note', 'department_id']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['whisper_to']);
            $table->dropColumn(['message_type', 'whisper_to']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        Schema::dropIfExists('departments');
    }
};
