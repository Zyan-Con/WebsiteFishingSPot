<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Info
            $table->enum('type', ['lokasi', 'rawai', 'tonda'])->default('lokasi');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            
            // Coordinates
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('end_latitude', 10, 7)->nullable(); // untuk rawai
            $table->decimal('end_longitude', 10, 7)->nullable(); // untuk rawai
            
            // Common Fields
            $table->integer('rating')->nullable();
            $table->timestamp('last_visited_at')->nullable();
            
            // Lokasi Specific
            $table->decimal('depth', 8, 2)->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->json('fish_types')->nullable();
            
            // Rawai Specific
            $table->integer('hooks_count')->nullable();
            $table->integer('total_catch')->nullable();
            $table->decimal('success_rate', 5, 2)->nullable();
            $table->string('bait_type')->nullable();
            $table->time('set_time')->nullable();
            $table->time('haul_time')->nullable();
            $table->decimal('rawai_distance', 8, 2)->nullable();
            
            // Tonda Specific
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('avg_speed', 5, 2)->nullable();
            $table->string('lure_type')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};