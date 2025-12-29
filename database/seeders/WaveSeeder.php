<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wave;
use Carbon\Carbon;

class WaveSeeder extends Seeder
{
    public function run()
    {
        // Generate data untuk 7 hari ke depan
        for ($day = 0; $day < 7; $day++) {
            $date = Carbon::now()->addDays($day);
            
            // Generate data setiap 4 jam
            for ($hour = 0; $hour < 24; $hour += 4) {
                Wave::create([
                    'forecast_date' => $date->format('Y-m-d'),
                    'forecast_time' => sprintf('%02d:00:00', $hour),
                    'wave_height' => rand(30, 80) / 100, // 0.3 - 0.8 meter
                    'wave_period' => rand(6, 12), // 6-12 detik
                    'wave_direction' => rand(180, 240), // 180-240 derajat (S-SW)
                    'location' => 'Padang',
                    'latitude' => -0.824992,
                    'longitude' => 100.250308,
                ]);
            }
        }
    }
} 