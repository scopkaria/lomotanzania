<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ADDED: Blog comments with admin approval
        if (!Schema::hasTable('blog_comments')) {
            Schema::create('blog_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
                $table->string('name', 100);
                $table->string('email', 150);
                $table->string('phone', 30)->nullable();
                $table->text('body');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->string('honeypot')->nullable(); // spam trap
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();

                $table->index(['post_id', 'status']);
                $table->index('email');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
