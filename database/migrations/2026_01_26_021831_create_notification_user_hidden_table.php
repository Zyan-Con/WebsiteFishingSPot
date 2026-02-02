<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_user_hidden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_notification_id')->constrained('admin_notifications')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Kombinasi unik: 1 user hanya bisa hide 1 notifikasi 1x
            $table->unique(['admin_notification_id', 'user_id'], 'notification_user_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_user_hidden');
    }
}; 