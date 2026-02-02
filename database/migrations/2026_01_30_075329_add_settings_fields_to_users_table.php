<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'banner')) {
                $table->string('banner')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('banner');
            }
            if (!Schema::hasColumn('users', 'total_reviews')) {
                $table->integer('total_reviews')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->default('light')->after('total_reviews');
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
            $columns = ['bio', 'avatar', 'banner', 'rating', 'total_reviews', 'theme', 'email_notifications', 'weather_notifications'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};