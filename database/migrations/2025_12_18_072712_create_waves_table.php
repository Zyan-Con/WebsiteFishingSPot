<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waves', function (Blueprint $table) {
            $table->id();
            $table->date('forecast_date');
            $table->time('forecast_time');
            $table->decimal('wave_height', 4, 2); // dalam meter
            $table->integer('wave_period'); // dalam detik
            $table->integer('wave_direction'); // dalam derajat
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waves');
    }
};