<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->enum('status', ['pending', 'replied', 'closed'])->default('pending');
            $table->boolean('is_read_by_user')->default(false);
            $table->boolean('is_read_by_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
};