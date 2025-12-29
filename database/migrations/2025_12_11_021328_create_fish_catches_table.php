<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Jika tabel belum ada, buat tabel baru
        if (!Schema::hasTable('fish_catches')) {
            Schema::create('fish_catches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('fish_type');
                $table->decimal('weight', 8, 2);
                $table->decimal('length', 8, 2)->nullable();
                $table->integer('quantity')->default(1);
                $table->string('location');
                $table->string('location_name')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('photo')->nullable();
                $table->datetime('caught_at');
                $table->time('catch_time')->nullable();
                $table->string('fishing_method')->nullable();
                $table->text('notes')->nullable();
                $table->string('weather')->nullable();
                $table->decimal('water_temp', 5, 2)->nullable();
                $table->timestamps();
            });
        } else {
            // ✅ Jika tabel sudah ada, tambahkan kolom yang belum ada
            Schema::table('fish_catches', function (Blueprint $table) {
                if (!Schema::hasColumn('fish_catches', 'quantity')) {
                    $table->integer('quantity')->default(1)->after('length');
                }
                if (!Schema::hasColumn('fish_catches', 'catch_time')) {
                    $table->time('catch_time')->nullable()->after('caught_at');
                }
                if (!Schema::hasColumn('fish_catches', 'location_name')) {
                    $table->string('location_name')->nullable()->after('location');
                }
                if (!Schema::hasColumn('fish_catches', 'fishing_method')) {
                    $table->string('fishing_method')->nullable()->after('longitude');
                }
                if (!Schema::hasColumn('fish_catches', 'notes')) {
                    $table->text('notes')->nullable()->after('fishing_method');
                }
                if (!Schema::hasColumn('fish_catches', 'weather')) {
                    $table->string('weather')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('fish_catches', 'water_temp')) {
                    $table->decimal('water_temp', 5, 2)->nullable()->after('weather');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fish_catches');
    }
};