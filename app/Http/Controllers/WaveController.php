<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaveController extends Controller
{
    // ⚠️ JANGAN HAPUS METHOD YANG SUDAH ADA (index, getData, getWeeklyForecast)
    // ✅ TAMBAHKAN METHOD BARU INI JIKA BELUM ADA
    
    /**
     * Get Wave Data for API (AJAX)
     * Route: /api/forecast/wave-data
     */
    public function getWaveData(Request $request)
    {
        $lat = $request->input('lat', -0.947136);
        $lon = $request->input('lon', 100.417419);
        $day = $request->input('day', 0);
        $apiKey = env('STORMGLASS_API_KEY');
        
        try {
            // Try to fetch from Stormglass API
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->get("https://api.stormglass.io/v2/weather/point", [
                'lat' => $lat,
                'lng' => $lon,
                'params' => 'waveHeight,wavePeriod,waveDirection,windSpeed,windDirection',
                'start' => now()->timestamp,
                'end' => now()->addDay()->timestamp,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $current = $data['hours'][0] ?? null;
                
                if ($current) {
                    return response()->json([
                        'success' => true,
                        'current' => [
                            'height' => $current['waveHeight']['sg'] ?? 1.5,
                            'period' => $current['wavePeriod']['sg'] ?? 8,
                            'direction' => $current['waveDirection']['sg'] ?? 225,
                            'windSpeed' => $current['windSpeed']['sg'] ?? 15,
                            'windDirection' => $current['windDirection']['sg'] ?? 225,
                        ],
                        'hourly' => $this->processHourlyWaveData($data['hours']),
                        'maxHeight' => $this->getMaxWaveHeight($data['hours'])
                    ]);
                }
            }
            
            // Fallback to demo data
            return $this->getDemoWaveData();
            
        } catch (\Exception $e) {
            // Return demo data on error
            return $this->getDemoWaveData();
        }
    }
    
    /**
     * Process hourly wave data
     */
    private function processHourlyWaveData($hours)
    {
        $hourlyData = [];
        
        foreach (array_slice($hours, 0, 24) as $hour) {
            $hourlyData[] = [
                'time' => date('H:00', strtotime($hour['time'])),
                'height' => $hour['waveHeight']['sg'] ?? 1.5,
                'period' => $hour['wavePeriod']['sg'] ?? 8,
            ];
        }
        
        return $hourlyData;
    }
    
    /**
     * Get maximum wave height from hourly data
     */
    private function getMaxWaveHeight($hours)
    {
        $heights = array_map(function($hour) {
            return $hour['waveHeight']['sg'] ?? 1.5;
        }, $hours);
        
        return max($heights);
    }
    
    /**
     * Get demo wave data as fallback
     */
    private function getDemoWaveData()
    {
        // Demo hourly data
        $hourlyHeights = [
            1.2, 1.4, 1.6, 1.8, 2.0, 2.2, 2.5, 2.3, 2.0, 1.7, 1.5, 1.3,
            1.2, 1.3, 1.5, 1.7, 1.9, 2.1, 2.2, 2.0, 1.8, 1.5, 1.3, 1.2, 1.1
        ];
        
        return response()->json([
            'success' => true,
            'current' => [
                'height' => 1.8,
                'period' => 8,
                'direction' => 225,
                'windSpeed' => 15,
                'windDirection' => 225,
            ],
            'hourly' => $hourlyHeights,
            'maxHeight' => 2.5
        ]);
    }
    
    /**
     * Get direction name from degrees
     */
    private function getDirectionName($degrees)
    {
        $directions = [
            'Utara', 'Timur Laut', 'Timur', 'Tenggara',
            'Selatan', 'Barat Daya', 'Barat', 'Barat Laut'
        ];
        
        $index = round($degrees / 45) % 8;
        return $directions[$index];
    }
}