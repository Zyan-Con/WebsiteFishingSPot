<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $type = $request->get('type', 'lokasi');
            
            // Jika table locations belum ada, return empty collection
            if (!Auth::check()) {
                $locations = collect();
                $counts = ['lokasi' => 0, 'rawai' => 0, 'tonda' => 0];
            } else {
                // Get filtered locations
                $locations = Location::where('user_id', Auth::id())
                    ->when($type !== 'all', function($query) use ($type) {
                        return $query->where('type', $type);
                    })
                    ->latest()
                    ->get();
                
                // ✅ Get counts for ALL types (tidak di-filter)
                $counts = [
                    'lokasi' => Location::where('user_id', Auth::id())->where('type', 'lokasi')->count(),
                    'rawai' => Location::where('user_id', Auth::id())->where('type', 'rawai')->count(),
                    'tonda' => Location::where('user_id', Auth::id())->where('type', 'tonda')->count(),
                ];
            }
            
            return view('locations.index', compact('locations', 'type', 'counts'));
            
        } catch (\Exception $e) {
            Log::error('LocationController@index error: ' . $e->getMessage());
            
            // Return empty collection if error
            $locations = collect();
            $type = 'lokasi';
            $counts = ['lokasi' => 0, 'rawai' => 0, 'tonda' => 0];
            return view('locations.index', compact('locations', 'type', 'counts'));
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:lokasi,rawai,tonda',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'photo' => 'nullable|image|max:2048',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'end_latitude' => 'nullable|numeric',
                'end_longitude' => 'nullable|numeric',
                'rating' => 'nullable|integer|min:1|max:5',
                'depth' => 'nullable|numeric',
                'difficulty' => 'nullable|in:easy,medium,hard',
                'fish_types' => 'nullable|string',
                'hooks_count' => 'nullable|integer',
                'total_catch' => 'nullable|integer',
                'bait_type' => 'nullable|string',
                'set_time' => 'nullable|date_format:H:i',
                'haul_time' => 'nullable|date_format:H:i',
                'distance_km' => 'nullable|numeric',
                'duration_minutes' => 'nullable|integer',
                'avg_speed' => 'nullable|numeric',
                'lure_type' => 'nullable|string',
            ]);

            $validated['user_id'] = Auth::id();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('locations', 'public');
            }

            // Parse fish_types
            if ($request->has('fish_types')) {
                $validated['fish_types'] = json_decode($request->fish_types, true);
            }

            // Calculate rawai distance
            if ($request->type === 'rawai' && $request->end_latitude && $request->end_longitude) {
                $validated['rawai_distance'] = $this->calculateDistance(
                    $validated['latitude'],
                    $validated['longitude'],
                    $validated['end_latitude'],
                    $validated['end_longitude']
                );
            }

            Location::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil ditambahkan!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('LocationController@store error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $location = Location::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($location->photo) {
                Storage::disk('public')->delete($location->photo);
            }

            $location->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus lokasi'
            ], 500);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }
}