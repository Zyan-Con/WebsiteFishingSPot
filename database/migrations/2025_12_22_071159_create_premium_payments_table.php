<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('premium_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'expired'])->default('pending');
            $table->string('qris_url')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Add premium fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_premium')->default(false)->after('email');
            $table->timestamp('premium_until')->nullable()->after('is_premium');
        });
    }

    public function down()
    {
        Schema::dropIfExists('premium_payments');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_premium', 'premium_until']);
        });
    }
};