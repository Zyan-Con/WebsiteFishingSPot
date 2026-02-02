<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->default('light')->after('password');
            }
            if (!Schema::hasColumn('users', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true)->after('theme');
            }
            if (!Schema::hasColumn('users', 'weather_notifications')) {
                $table->boolean('weather_notifications')->default(true)->after('email_notifications');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme', 'email_notifications', 'weather_notifications']);
        });
    }
};