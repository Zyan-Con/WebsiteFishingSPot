<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query terlalu pendek'
            ]);
        }

        $results = [
            'locations' => [],
            'saved_locations' => [],
            'catches' => [],
            'forecasts' => []
        ];

        // 1. Search dari OpenWeather Geocoding API
        try {
            $apiKey = env('OPENWEATHER_API_KEY');
            $response = Http::get("http://api.openweathermap.org/geo/1.0/direct", [
                'q' => $query,
                'limit' => 5,
                'appid' => $apiKey
            ]);

            if ($response->successful()) {
                $locations = $response->json();
                
                foreach ($locations as $location) {
                    $results['locations'][] = [
                        'type' => 'location',
                        'name' => $location['name'],
                        'country' => $location['country'] ?? '',
                        'state' => $location['state'] ?? '',
                        'lat' => $location['lat'],
                        'lon' => $location['lon'],
                        'display_name' => $this->formatLocationName($location)
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('OpenWeather API Error: ' . $e->getMessage());
        }

        // 2. Search dari lokasi tersimpan user
        if (auth()->check()) {
            $savedLocations = Location::where('user_id', auth()->id())
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get();

            foreach ($savedLocations as $location) {
                $results['saved_locations'][] = [
                    'type' => 'saved_location',
                    'id' => $location->id,
                    'name' => $location->name,
                    'description' => $location->description,
                    'lat' => $location->latitude,
                    'lon' => $location->longitude,
                    'is_favorite' => $location->is_favorite
                ];
            }
        }

        // 3. Search dari tangkapan (catches)
        if (auth()->check()) {
            $catches = \App\Models\Catch::where('user_id', auth()->id())
                ->where(function($q) use ($query) {
                    $q->where('species', 'LIKE', "%{$query}%")
                      ->orWhere('location_name', 'LIKE', "%{$query}%");
                })
                ->limit(3)
                ->get();

            foreach ($catches as $catch) {
                $results['catches'][] = [
                    'type' => 'catch',
                    'id' => $catch->id,
                    'species' => $catch->species,
                    'location' => $catch->location_name,
                    'date' => $catch->caught_at->format('d M Y'),
                    'lat' => $catch->latitude,
                    'lon' => $catch->longitude
                ];
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'total' => count($results['locations']) + 
                      count($results['saved_locations']) + 
                      count($results['catches'])
        ]);
    }

    private function formatLocationName($location)
    {
        $parts = [$location['name']];
        
        if (isset($location['state']) && $location['state']) {
            $parts[] = $location['state'];
        }
        
        if (isset($location['country']) && $location['country']) {
            $parts[] = $location['country'];
        }
        
        return implode(', ', $parts);
    }
}