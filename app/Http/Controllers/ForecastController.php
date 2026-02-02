<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ForecastController extends Controller
{
    // ========================================
    // MAIN INDEX - Return view dengan semua tab
    // ========================================
    
    public function index()
    {
        return view('forecast.index');
    }
    
    // ========================================
    // 1. WEATHER FORECAST (Cuaca) ☀️
    // ========================================
    
    public function weather(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lng = $request->input('lng', 100.417419);
            $location = $request->input('location', 'Padang');
            
            $weatherApiKey = env('OPENWEATHER_API_KEY');
            
            // Get weather data
            $currentWeather = $this->getCurrentWeather($lat, $lng, $weatherApiKey);
            $forecast = $this->getWeatherForecast($lat, $lng, $weatherApiKey);
            
            // Get maritime data dari Stormglass
            $maritimeData = $this->getWeatherFromStormglass($lat, $lng);
            
            return view('forecast.weather', compact(
                'currentWeather', 
                'forecast', 
                'maritimeData',
                'lat', 
                'lng', 
                'location'
            ));
            
        } catch (\Exception $e) {
            Log::error('Weather forecast error: ' . $e->getMessage());
            return view('forecast.weather')->with('error', 'Gagal mengambil data cuaca');
        }
    }
    
    private function getCurrentWeather($lat, $lng, $apiKey)
    {
        try {
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $lat,
                'lon' => $lng,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'temp' => round($data['main']['temp']),
                    'feels_like' => round($data['main']['feels_like']),
                    'temp_min' => round($data['main']['temp_min']),
                    'temp_max' => round($data['main']['temp_max']),
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'wind_speed' => $data['wind']['speed'],
                    'wind_deg' => $data['wind']['deg'],
                    'clouds' => $data['clouds']['all'],
                    'description' => $data['weather'][0]['description'],
                    'icon' => $data['weather'][0]['icon'],
                    'visibility' => isset($data['visibility']) ? $data['visibility'] / 1000 : null,
                    'sunrise' => Carbon::createFromTimestamp($data['sys']['sunrise'])->format('H:i'),
                    'sunset' => Carbon::createFromTimestamp($data['sys']['sunset'])->format('H:i'),
                ];
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Current weather API error: ' . $e->getMessage());
            return null;
        }
    }
    
    private function getWeatherForecast($lat, $lng, $apiKey)
    {
        try {
            $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
                'lat' => $lat,
                'lon' => $lng,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                $forecast = [];
                foreach ($data['list'] as $item) {
                    $date = Carbon::createFromTimestamp($item['dt'])->format('Y-m-d');
                    
                    if (!isset($forecast[$date])) {
                        $forecast[$date] = [
                            'date' => $date,
                            'day' => Carbon::createFromTimestamp($item['dt'])->locale('id')->isoFormat('dddd'),
                            'temp_min' => $item['main']['temp_min'],
                            'temp_max' => $item['main']['temp_max'],
                            'description' => $item['weather'][0]['description'],
                            'icon' => $item['weather'][0]['icon'],
                            'humidity' => $item['main']['humidity'],
                            'wind_speed' => $item['wind']['speed'],
                            'rain_chance' => isset($item['pop']) ? round($item['pop'] * 100) : 0,
                            'hourly' => []
                        ];
                    }
                    
                    $forecast[$date]['temp_min'] = min($forecast[$date]['temp_min'], $item['main']['temp_min']);
                    $forecast[$date]['temp_max'] = max($forecast[$date]['temp_max'], $item['main']['temp_max']);
                    
                    $forecast[$date]['hourly'][] = [
                        'time' => Carbon::createFromTimestamp($item['dt'])->format('H:i'),
                        'temp' => round($item['main']['temp']),
                        'icon' => $item['weather'][0]['icon'],
                        'description' => $item['weather'][0]['description']
                    ];
                }
                
                return array_values($forecast);
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Weather forecast API error: ' . $e->getMessage());
            return null;
        }
    }
    
    private function getWeatherFromStormglass($lat, $lng)
    {
        try {
            $apiKey = env('STORMGLASS_API_KEY');
            
            if (!$apiKey) {
                return null;
            }
            
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->get('https://api.stormglass.io/v2/weather/point', [
                'lat' => $lat,
                'lng' => $lng,
                'params' => 'waveHeight,seaLevel,waterTemperature,windSpeed,windDirection'
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Stormglass API error: ' . $e->getMessage());
            return null;
        }
    }
    
    // ========================================
    // 2. TIDE FORECAST (Pasang Surut) 🌊
    // ========================================
    
    public function tide(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lng = $request->input('lng', 100.417419);
            
            $stormglassApiKey = env('STORMGLASS_API_KEY');
            
            $tideData = $this->getTideData($lat, $lng, $stormglassApiKey);
            $tideTable = $this->formatTideTable($tideData);
            
            return view('forecast.tide', compact('tideData', 'tideTable', 'lat', 'lng'));
            
        } catch (\Exception $e) {
            Log::error('Tide forecast error: ' . $e->getMessage());
            return view('forecast.tide')->with('error', 'Gagal mengambil data pasang surut');
        }
    }
    
    private function getTideData($lat, $lng, $apiKey)
    {
        try {
            if (!$apiKey) {
                return $this->getDummyTideData();
            }
            
            $start = now()->startOfDay()->timestamp;
            $end = now()->addDays(7)->endOfDay()->timestamp;
            
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->get('https://api.stormglass.io/v2/tide/extremes/point', [
                'lat' => $lat,
                'lng' => $lng,
                'start' => $start,
                'end' => $end
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return $this->getDummyTideData();
            
        } catch (\Exception $e) {
            Log::error('Storm Glass API error: ' . $e->getMessage());
            return $this->getDummyTideData();
        }
    }
    
    private function formatTideTable($tideData)
    {
        if (!$tideData || !isset($tideData['data'])) {
            return [];
        }
        
        $table = [];
        foreach ($tideData['data'] as $tide) {
            $date = Carbon::parse($tide['time'])->format('Y-m-d');
            
            if (!isset($table[$date])) {
                $table[$date] = [
                    'date' => $date,
                    'day' => Carbon::parse($tide['time'])->locale('id')->isoFormat('dddd, D MMMM'),
                    'tides' => []
                ];
            }
            
            $table[$date]['tides'][] = [
                'time' => Carbon::parse($tide['time'])->format('H:i'),
                'height' => round($tide['height'], 2),
                'type' => $tide['type']
            ];
        }
        
        return array_values($table);
    }
    
    private function getDummyTideData()
    {
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i);
            
            $data[] = [
                'time' => $date->setTime(6, 30)->toIso8601String(),
                'height' => rand(160, 190) / 100,
                'type' => 'high'
            ];
            $data[] = [
                'time' => $date->setTime(12, 45)->toIso8601String(),
                'height' => rand(30, 60) / 100,
                'type' => 'low'
            ];
            $data[] = [
                'time' => $date->setTime(18, 20)->toIso8601String(),
                'height' => rand(170, 200) / 100,
                'type' => 'high'
            ];
            $data[] = [
                'time' => $date->setTime(0, 15)->toIso8601String(),
                'height' => rand(20, 50) / 100,
                'type' => 'low'
            ];
        }
        
        return ['data' => $data];
    }
    
    // ========================================
    // 3. WAVE FORECAST (Gelombang) 🌊
    // ========================================
    
    public function wave(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lng = $request->input('lng', 100.417419);
            
            $stormglassApiKey = env('STORMGLASS_API_KEY');
            
            $waveData = $this->getWaveData($lat, $lng, $stormglassApiKey);
            $waveForecast = $this->formatWaveForecast($waveData);
            
            return view('forecast.wave', compact('waveData', 'waveForecast', 'lat', 'lng'));
            
        } catch (\Exception $e) {
            Log::error('Wave forecast error: ' . $e->getMessage());
            return view('forecast.wave')->with('error', 'Gagal mengambil data gelombang');
        }
    }
    
    private function getWaveData($lat, $lng, $apiKey)
    {
        try {
            if (!$apiKey) {
                return $this->getDummyWaveData();
            }
            
            $start = now()->timestamp;
            $end = now()->addDays(7)->timestamp;
            
            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->get('https://api.stormglass.io/v2/weather/point', [
                'lat' => $lat,
                'lng' => $lng,
                'start' => $start,
                'end' => $end,
                'params' => 'waveHeight,wavePeriod,waveDirection,swellHeight,swellPeriod,swellDirection,windWaveHeight'
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return $this->getDummyWaveData();
            
        } catch (\Exception $e) {
            Log::error('Wave API error: ' . $e->getMessage());
            return $this->getDummyWaveData();
        }
    }
    
    private function formatWaveForecast($waveData)
    {
        if (!$waveData || !isset($waveData['hours'])) {
            return [];
        }
        
        $forecast = [];
        foreach ($waveData['hours'] as $hour) {
            $date = Carbon::parse($hour['time'])->format('Y-m-d');
            
            if (!isset($forecast[$date])) {
                $forecast[$date] = [
                    'date' => $date,
                    'day' => Carbon::parse($hour['time'])->locale('id')->isoFormat('dddd, D MMMM'),
                    'wave_height_max' => 0,
                    'wave_height_min' => 999,
                    'wave_period_avg' => 0,
                    'swell_height_avg' => 0,
                    'condition' => '',
                    'hourly' => [],
                    'count' => 0
                ];
            }
            
            $waveHeight = $hour['waveHeight']['sg'] ?? 0;
            $wavePeriod = $hour['wavePeriod']['sg'] ?? 0;
            $swellHeight = $hour['swellHeight']['sg'] ?? 0;
            
            $forecast[$date]['wave_height_max'] = max($forecast[$date]['wave_height_max'], $waveHeight);
            $forecast[$date]['wave_height_min'] = min($forecast[$date]['wave_height_min'], $waveHeight);
            $forecast[$date]['wave_period_avg'] += $wavePeriod;
            $forecast[$date]['swell_height_avg'] += $swellHeight;
            $forecast[$date]['count']++;
            
            $forecast[$date]['hourly'][] = [
                'time' => Carbon::parse($hour['time'])->format('H:i'),
                'wave_height' => round($waveHeight, 1),
                'wave_period' => round($wavePeriod, 1),
                'wave_direction' => $hour['waveDirection']['sg'] ?? 0,
                'swell_height' => round($swellHeight, 1)
            ];
        }
        
        foreach ($forecast as &$day) {
            if ($day['count'] > 0) {
                $day['wave_period_avg'] = round($day['wave_period_avg'] / $day['count'], 1);
                $day['swell_height_avg'] = round($day['swell_height_avg'] / $day['count'], 1);
            }
            
            $maxHeight = $day['wave_height_max'];
            if ($maxHeight < 0.5) {
                $day['condition'] = 'Tenang';
                $day['condition_class'] = 'success';
            } elseif ($maxHeight < 1.25) {
                $day['condition'] = 'Bergelombang Ringan';
                $day['condition_class'] = 'info';
            } elseif ($maxHeight < 2.5) {
                $day['condition'] = 'Bergelombang Sedang';
                $day['condition_class'] = 'warning';
            } else {
                $day['condition'] = 'Bergelombang Tinggi';
                $day['condition_class'] = 'danger';
            }
            
            unset($day['count']);
        }
        
        return array_values($forecast);
    }
    
    private function getDummyWaveData()
    {
        $hours = [];
        for ($i = 0; $i < 7 * 24; $i += 3) {
            $time = now()->addHours($i);
            $hours[] = [
                'time' => $time->toIso8601String(),
                'waveHeight' => ['sg' => rand(50, 250) / 100],
                'wavePeriod' => ['sg' => rand(5, 12)],
                'waveDirection' => ['sg' => rand(180, 240)],
                'swellHeight' => ['sg' => rand(40, 180) / 100],
                'swellPeriod' => ['sg' => rand(8, 15)],
                'swellDirection' => ['sg' => rand(200, 250)]
            ];
        }
        
        return ['hours' => $hours];
    }
    
    // ========================================
    // 4. FISH ACTIVITY FORECAST (Aktivitas Ikan) 🐟
    // ========================================
    
    public function activity(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lng = $request->input('lng', 100.417419);
            $date = $request->input('date', now()->format('Y-m-d'));
            
            $solunarData = $this->calculateSolunar($lat, $lng, $date);
            $weekForecast = $this->getWeekActivityForecast($lat, $lng);
            
            return view('forecast.activity', compact('solunarData', 'weekForecast', 'lat', 'lng', 'date'));
            
        } catch (\Exception $e) {
            Log::error('Activity forecast error: ' . $e->getMessage());
            return view('forecast.activity')->with('error', 'Gagal menghitung aktivitas ikan');
        }
    }
    
    private function calculateSolunar($lat, $lng, $date)
    {
        $carbon = Carbon::parse($date);
        
        $moonPhase = $this->getMoonPhase($carbon);
        $sunrise = $this->getSunrise($lat, $lng, $carbon);
        $sunset = $this->getSunset($lat, $lng, $carbon);
        $moonrise = $sunrise->copy()->addHours(rand(1, 3));
        $moonset = $sunset->copy()->addHours(rand(1, 3));
        
        $periods = $this->calculateFishingPeriods($sunrise, $sunset, $moonrise, $moonset);
        $rating = $this->calculateFishingRating($moonPhase, count($periods['major']));
        
        return [
            'date' => $carbon->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'moon_phase' => $moonPhase,
            'moon_illumination' => $this->getMoonIllumination($carbon),
            'sunrise' => $sunrise->format('H:i'),
            'sunset' => $sunset->format('H:i'),
            'moonrise' => $moonrise->format('H:i'),
            'moonset' => $moonset->format('H:i'),
            'rating' => $rating,
            'rating_text' => $this->getRatingText($rating),
            'rating_color' => $this->getRatingColor($rating),
            'major_periods' => $periods['major'],
            'minor_periods' => $periods['minor'],
            'best_times' => $periods['best'],
            'tips' => $this->getFishingTips($rating, $moonPhase)
        ];
    }
    
    private function getMoonPhase($date)
    {
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;
        
        if ($month < 3) {
            $year--;
            $month += 12;
        }
        
        ++$month;
        $c = 365.25 * $year;
        $e = 30.6 * $month;
        $jd = $c + $e + $day - 694039.09;
        $jd /= 29.5305882;
        $b = (int) $jd;
        $jd -= $b;
        $b = round($jd * 8);
        
        if ($b >= 8) {
            $b = 0;
        }
        
        $phases = [
            'Bulan Baru', 'Bulan Sabit Awal', 'Kuartir Awal', 'Bulan Cembung Awal',
            'Bulan Purnama', 'Bulan Cembung Akhir', 'Kuartir Akhir', 'Bulan Sabit Akhir'
        ];
        
        return $phases[$b];
    }
    
    private function getMoonIllumination($date)
    {
        $age = $this->getMoonAge($date);
        $illumination = (1 - cos($age * 2 * pi() / 29.53)) / 2 * 100;
        return round($illumination);
    }
    
    private function getMoonAge($date)
    {
        $knownNewMoon = Carbon::create(2000, 1, 6, 18, 14);
        $daysSince = $date->diffInDays($knownNewMoon);
        return $daysSince % 29.53;
    }
    
    private function getSunrise($lat, $lng, $date)
    {
        $sunInfo = date_sun_info($date->timestamp, $lat, $lng);
        return Carbon::createFromTimestamp($sunInfo['sunrise']);
    }
    
    private function getSunset($lat, $lng, $date)
    {
        $sunInfo = date_sun_info($date->timestamp, $lat, $lng);
        return Carbon::createFromTimestamp($sunInfo['sunset']);
    }
    
    private function calculateFishingPeriods($sunrise, $sunset, $moonrise, $moonset)
    {
        $major = [
            [
                'start' => $moonrise->format('H:i'),
                'end' => $moonrise->copy()->addHours(2)->format('H:i'),
                'label' => 'Major Period 1'
            ],
            [
                'start' => $moonset->format('H:i'),
                'end' => $moonset->copy()->addHours(2)->format('H:i'),
                'label' => 'Major Period 2'
            ]
        ];
        
        $minor = [
            [
                'start' => $sunrise->format('H:i'),
                'end' => $sunrise->copy()->addHours(1.5)->format('H:i'),
                'label' => 'Minor Period (Sunrise)'
            ],
            [
                'start' => $sunset->format('H:i'),
                'end' => $sunset->copy()->addHours(1.5)->format('H:i'),
                'label' => 'Minor Period (Sunset)'
            ]
        ];
        
        $best = [
            $sunrise->copy()->subMinutes(30)->format('H:i') . ' - ' . $sunrise->copy()->addHours(2)->format('H:i'),
            $sunset->copy()->subMinutes(30)->format('H:i') . ' - ' . $sunset->copy()->addHours(2)->format('H:i')
        ];
        
        return compact('major', 'minor', 'best');
    }
    
    private function calculateFishingRating($moonPhase, $majorPeriodCount)
    {
        $rating = 3;
        
        if (in_array($moonPhase, ['Bulan Purnama', 'Bulan Baru'])) {
            $rating = 5;
        } elseif (in_array($moonPhase, ['Kuartir Awal', 'Kuartir Akhir'])) {
            $rating = 4;
        } elseif (in_array($moonPhase, ['Bulan Cembung Awal', 'Bulan Cembung Akhir'])) {
            $rating = 3.5;
        }
        
        return $rating;
    }
    
    private function getRatingText($rating)
    {
        if ($rating >= 4.5) return 'Sangat Baik';
        if ($rating >= 3.5) return 'Baik';
        if ($rating >= 2.5) return 'Cukup';
        return 'Kurang';
    }
    
    private function getRatingColor($rating)
    {
        if ($rating >= 4.5) return 'success';
        if ($rating >= 3.5) return 'info';
        if ($rating >= 2.5) return 'warning';
        return 'danger';
    }
    
    private function getFishingTips($rating, $moonPhase)
    {
        $tips = [];
        
        if ($rating >= 4) {
            $tips[] = 'Hari ini sangat bagus untuk memancing!';
            $tips[] = 'Aktivitas ikan diprediksi sangat tinggi';
        }
        
        if (in_array($moonPhase, ['Bulan Purnama', 'Bulan Baru'])) {
            $tips[] = 'Fase bulan ' . strtolower($moonPhase) . ' meningkatkan aktivitas ikan';
            $tips[] = 'Perhatikan perubahan pasang surut yang signifikan';
        }
        
        $tips[] = 'Waktu terbaik adalah saat sunrise dan sunset';
        $tips[] = 'Gunakan umpan yang sesuai dengan kondisi cuaca';
        $tips[] = 'Perhatikan perubahan angin dan arus';
        
        return $tips;
    }
    
    private function getWeekActivityForecast($lat, $lng)
    {
        $forecast = [];
        
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i);
            $solunar = $this->calculateSolunar($lat, $lng, $date->format('Y-m-d'));
            
            $forecast[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->locale('id')->isoFormat('ddd'),
                'date_full' => $date->locale('id')->isoFormat('D MMM'),
                'rating' => $solunar['rating'],
                'rating_text' => $solunar['rating_text'],
                'rating_color' => $solunar['rating_color'],
                'moon_phase' => $solunar['moon_phase'],
                'moon_illumination' => $solunar['moon_illumination'],
                'best_time' => $solunar['best_times'][0] ?? '-'
            ];
        }
        
        return $forecast;
    }
    
    // ========================================
    // AJAX ENDPOINTS untuk Data Tab
    // ========================================
    
    /**
     * Get Activity Data for AJAX
     */
    public function getActivityData(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lon = $request->input('lon', 100.417419);
            $day = $request->input('day', 0);
            
            $score = $this->calculateFishActivity($lat, $lon);
            
            $days = ['HARI INI', 'JUM', 'SAB', 'MIN', 'SEN', 'SEL', 'RAB'];
            $scores = [65, 45, 72, 82, 56, 38, 28];
            
            $hourlyScores = [
                30, 25, 28, 35, 50, 68, 75, 70, 55, 45, 40, 38,
                42, 48, 60, 72, 80, 85, 78, 65, 52, 42, 35, 30, 28
            ];
            
            return response()->json([
                'success' => true,
                'score' => $scores[$day] ?? $score,
                'day' => $days[$day] ?? 'HARI INI',
                'moonPhase' => $this->getMoonPhaseName(now()),
                'pressure' => 'Stabil',
                'weather' => 'Berawan',
                'majorTimes' => [
                    ['start' => '06:15', 'end' => '08:15', 'duration' => '2 jam'],
                    ['start' => '18:30', 'end' => '20:30', 'duration' => '2 jam']
                ],
                'minorTimes' => [
                    ['start' => '00:15', 'end' => '01:15', 'duration' => '1 jam'],
                    ['start' => '12:00', 'end' => '13:00', 'duration' => '1 jam']
                ],
                'hourlyScores' => $hourlyScores
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get Tide Data for AJAX
     */
    public function getTideDataAjax(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lon = $request->input('lon', 100.417419);
            
            $tideData = [
                'current' => [
                    'height' => 1.2,
                    'status' => 'rising',
                    'nextHigh' => '14:45',
                    'nextLow' => '20:30',
                    'timeToNext' => '6 jam 55 menit'
                ],
                'hourly' => [
                    -0.5, 0.3, 1.0, 1.5, 1.8, 1.6, 1.0, 0.2, -0.4, -0.8, -0.6, 0.1,
                    0.8, 1.4, 1.7, 1.5, 0.9, 0.3, -0.3, -0.7, -0.5, 0.2, 0.9, 1.3, 1.2
                ],
                'highTides' => [
                    ['time' => '06:45', 'height' => 1.8],
                    ['time' => '19:20', 'height' => 1.6]
                ],
                'lowTides' => [
                    ['time' => '00:30', 'height' => -0.5],
                    ['time' => '13:15', 'height' => -0.8]
                ],
                'weekly' => [
                    ['date' => '25 Des', 'time' => '06:45', 'type' => 'high', 'height' => 1.8],
                    ['date' => '25 Des', 'time' => '13:15', 'type' => 'low', 'height' => -0.8],
                    ['date' => '25 Des', 'time' => '19:20', 'type' => 'high', 'height' => 1.6],
                    ['date' => '26 Des', 'time' => '00:30', 'type' => 'low', 'height' => -0.5],
                ]
            ];
            
            return response()->json(['success' => true, 'data' => $tideData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get Weather Data for AJAX
     */
    public function getWeatherData(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lon = $request->input('lon', 100.417419);
            $apiKey = env('OPENWEATHER_API_KEY');
            
            $current = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);
            
            $forecast = Http::get("https://api.openweathermap.org/data/2.5/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'id'
            ]);
            
            if ($current->successful() && $forecast->successful()) {
                $currentData = $current->json();
                $forecastData = $forecast->json();
                
                $iconMap = [
                    '01d' => '☀️', '01n' => '🌙', '02d' => '⛅', '02n' => '☁️',
                    '03d' => '☁️', '03n' => '☁️', '04d' => '☁️', '04n' => '☁️',
                    '09d' => '🌧️', '09n' => '🌧️', '10d' => '🌦️', '10n' => '🌧️',
                    '11d' => '⛈️', '11n' => '⛈️', '13d' => '🌨️', '13n' => '🌨️',
                    '50d' => '🌫️', '50n' => '🌫️'
                ];
                
                return response()->json([
                    'success' => true,
                    'current' => [
                        'temp' => round($currentData['main']['temp']),
                        'feelsLike' => round($currentData['main']['feels_like']),
                        'humidity' => $currentData['main']['humidity'],
                        'description' => ucfirst($currentData['weather'][0]['description']),
                        'icon' => $iconMap[$currentData['weather'][0]['icon']] ?? '☀️',
                        'wind' => round($currentData['wind']['speed'] * 3.6),
                        'pressure' => $currentData['main']['pressure'],
                        'visibility' => round($currentData['visibility'] / 1000, 1),
                        'clouds' => $currentData['clouds']['all']
                    ],
                    'hourly' => array_slice($forecastData['list'], 0, 8),
                    'daily' => $this->processDailyForecast($forecastData['list'])
                ]);
            }
            
            return $this->getDemoWeatherData();
            
        } catch (\Exception $e) {
            return $this->getDemoWeatherData();
        }
    }
    
    /**
     * Get Wave Data for AJAX
     */
    public function getWaveDataAjax(Request $request)
    {
        try {
            $lat = $request->input('lat', -0.947136);
            $lng = $request->input('lng', 100.417419);
            
            $stormglassApiKey = env('STORMGLASS_API_KEY');
            $waveData = $this->getWaveData($lat, $lng, $stormglassApiKey);
            
            return response()->json(['success' => true, 'data' => $waveData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // ========================================
    // HELPER METHODS
    // ========================================
    
    private function calculateFishActivity($lat, $lon)
    {
        $hour = now()->hour;
        $moonPhase = $this->getMoonPhase(now());
        
        $timeScore = 0;
        if (($hour >= 5 && $hour <= 7) || ($hour >= 17 && $hour <= 19)) {
            $timeScore = 80;
        } elseif (($hour >= 4 && $hour < 5) || ($hour > 7 && $hour <= 9) || 
                  ($hour >= 16 && $hour < 17) || ($hour > 19 && $hour <= 21)) {
            $timeScore = 60;
        } else {
            $timeScore = 30;
        }
        
        $moonScore = abs(array_search($moonPhase, [
            'Bulan Baru', 'Bulan Sabit Awal', 'Kuartir Awal', 'Bulan Cembung Awal',
            'Bulan Purnama', 'Bulan Cembung Akhir', 'Kuartir Akhir', 'Bulan Sabit Akhir'
        ]) - 4) * 5;
        
        return min(100, $timeScore + $moonScore);
    }
    
    private function getMoonPhaseName($date)
    {
        return $this->getMoonPhase($date);
    }
    
    private function processDailyForecast($hourlyList)
    {
        $daily = [];
        $currentDate = '';
        $dayData = [];
        
        foreach ($hourlyList as $item) {
            $date = date('Y-m-d', $item['dt']);
            
            if ($date !== $currentDate) {
                if (!empty($dayData)) {
                    $daily[] = [
                        'date' => $currentDate,
                        'temp_max' => max(array_column($dayData, 'temp')),
                        'temp_min' => min(array_column($dayData, 'temp')),
                        'icon' => $dayData[0]['icon'],
                        'description' => $dayData[0]['description']
                    ];
                }
                $currentDate = $date;
                $dayData = [];
            }
            
            $dayData[] = [
                'temp' => $item['main']['temp'],
                'icon' => $item['weather'][0]['icon'],
                'description' => $item['weather'][0]['description']
            ];
        }
        
        return array_slice($daily, 0, 5);
    }
    
    private function getDemoWeatherData()
    {
        return response()->json([
            'success' => true,
            'current' => [
                'temp' => 28,
                'feelsLike' => 30,
                'humidity' => 65,
                'description' => 'Cerah',
                'icon' => '☀️',
                'wind' => 12,
                'pressure' => 1013,
                'visibility' => 10,
                'clouds' => 20
            ],
            'hourly' => [],
            'daily' => []
        ]);
    }
} 