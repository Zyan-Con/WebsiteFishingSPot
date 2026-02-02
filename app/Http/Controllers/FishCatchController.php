<?php

namespace App\Http\Controllers;

use App\Models\FishCatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FishCatchController extends Controller
{
    
    public function index()
    {
        $catches = FishCatch::where('user_id', auth()->id())
            ->orderBy('caught_at', 'desc')
            ->paginate(12);

        $stats = [
            'total_catches' => FishCatch::where('user_id', auth()->id())->count(),
            'total_weight' => FishCatch::where('user_id', auth()->id())->sum('weight'),
            'species_count' => FishCatch::where('user_id', auth()->id())->distinct('fish_type')->count(),
            'this_month' => FishCatch::where('user_id', auth()->id())
                ->whereMonth('caught_at', now()->month)
                ->count(),
        ];

        return view('catches.index', compact('catches', 'stats'));
    }

    public function create()
    {
        return view('catches.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi
            $validated = $request->validate([
                'fish_species' => 'required|string|max:255',
                'weight' => 'required|numeric|min:0',
                'quantity' => 'nullable|integer|min:1',
                'catch_date' => 'required|date',
                'catch_time' => 'nullable',
                'location_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'fishing_method' => 'nullable|string',
                'notes' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
                'weather' => 'nullable|string',
                'water_temp' => 'nullable|numeric',
            ]);

            // Mapping ke struktur fish_catches table
            $data = [
                'user_id' => auth()->id(),
                'fish_type' => $validated['fish_species'],
                'weight' => $validated['weight'],
                'length' => null,
                'quantity' => $validated['quantity'] ?? 1,
                'location' => $validated['location_name'] ?? 'Unknown Location',
                'location_name' => $validated['location_name'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'caught_at' => $validated['catch_date'] . ($validated['catch_time'] ? ' ' . $validated['catch_time'] : ' 00:00:00'),
                'catch_time' => $validated['catch_time'] ?? null,
                'fishing_method' => $validated['fishing_method'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'weather' => $validated['weather'] ?? null,
                'water_temp' => $validated['water_temp'] ?? null,
            ];

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('catches', 'public');
            }

            // Simpan ke database
            $catch = FishCatch::create($data);

            Log::info('Fish catch created successfully', ['id' => $catch->id]);

            return redirect()->route('catches.index')
                ->with('success', '✅ Data penangkapan berhasil disimpan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', '⚠️ Validasi gagal! Periksa input Anda.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Failed to create fish catch', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return back()
                ->withInput()
                ->with('error', '❌ Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(FishCatch $catch)
    {
        if ($catch->user_id !== auth()->id()) {
            abort(403);
        }
        return view('catches.show', compact('catch'));
    }

    public function edit(FishCatch $catch)
    {
        if ($catch->user_id !== auth()->id()) {
            abort(403);
        }
        return view('catches.edit', compact('catch'));
    }

    public function update(Request $request, FishCatch $catch)
    {
        if ($catch->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'fish_species' => 'required|string|max:255',
                'weight' => 'required|numeric|min:0',
                'quantity' => 'nullable|integer|min:1',
                'catch_date' => 'required|date',
                'catch_time' => 'nullable',
                'location_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'fishing_method' => 'nullable|string',
                'notes' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
                'weather' => 'nullable|string',
                'water_temp' => 'nullable|numeric',
            ]);

            $data = [
                'fish_type' => $validated['fish_species'],
                'weight' => $validated['weight'],
                'quantity' => $validated['quantity'] ?? 1,
                'location' => $validated['location_name'] ?? 'Unknown Location',
                'location_name' => $validated['location_name'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'caught_at' => $validated['catch_date'] . ($validated['catch_time'] ? ' ' . $validated['catch_time'] : ' 00:00:00'),
                'catch_time' => $validated['catch_time'] ?? null,
                'fishing_method' => $validated['fishing_method'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'weather' => $validated['weather'] ?? null,
                'water_temp' => $validated['water_temp'] ?? null,
            ];

            if ($request->hasFile('photo')) {
                if ($catch->photo) {
                    Storage::disk('public')->delete($catch->photo);
                }
                $data['photo'] = $request->file('photo')->store('catches', 'public');
            }

            $catch->update($data);

            return redirect()->route('catches.index')
                ->with('success', '✅ Data penangkapan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Failed to update fish catch', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', '❌ Gagal memperbarui data: ' . $e->getMessage());
        }
    }

//     public function update(Request $request, Catch $catch)
// {
//     $request->validate([
//         'fish_type' => 'required|string|max:255',
//         'weight' => 'nullable|numeric|min:0',
//         'length' => 'nullable|numeric|min:0',
//         'quantity' => 'nullable|integer|min:1',
//         'location' => 'required|string|max:255',
//         'latitude' => 'nullable|numeric',
//         'longitude' => 'nullable|numeric',
//         'caught_at' => 'required|date',
//         'fishing_method' => 'nullable|string',
//         'weather' => 'nullable|string',
//         'water_temp' => 'nullable|numeric',
//         'notes' => 'nullable|string',
//         'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
//     ]);

//     try {
//         $data = $request->except('photo');

//         // Handle photo upload if exists
//         if ($request->hasFile('photo')) {
//             // Delete old photo
//             if ($catch->photo && Storage::exists('public/' . $catch->photo)) {
//                 Storage::delete('public/' . $catch->photo);
//             }
            
//             // Store new photo
//             $data['photo'] = $request->file('photo')->store('catches', 'public');
//         }

//         $catch->update($data);

//         return redirect()->route('catches.index')
//             ->with('success', 'Tangkapan berhasil diperbarui!');

//     } catch (\Exception $e) {
//         return back()
//             ->withInput()
//             ->withErrors(['error' => 'Gagal memperbarui tangkapan: ' . $e->getMessage()]);
//     }
// }

    public function destroy(FishCatch $catch)
    {
        if ($catch->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            if ($catch->photo) {
                Storage::disk('public')->delete($catch->photo);
            }

            $catch->delete();

            return redirect()->route('catches.index')
                ->with('success', '✅ Data penangkapan berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Failed to delete fish catch', ['error' => $e->getMessage()]);
            return back()->with('error', '❌ Gagal menghapus data: ' . $e->getMessage());
        }
    }
} 