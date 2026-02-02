<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id'); // Admin yang kirim
            $table->unsignedBigInteger('user_id')->nullable(); // NULL = broadcast ke semua
            $table->string('type')->default('info'); // info, warning, success, danger
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable(); // Link kalau ada
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};