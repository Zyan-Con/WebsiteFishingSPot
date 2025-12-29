<?php

namespace App\Http\Controllers;

use App\Models\FishCatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FishActivityController extends Controller
{
    public function store(Request $request)
    {
        try {
            // ✅ Log request data untuk debugging
            Log::info('Fish catch store request', [
                'all_data' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // ✅ Pastikan user sudah login
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', '⚠️ Anda harus login terlebih dahulu');
            }

            // Validasi
            $validated = $request->validate([
                'fish_species' => 'required|string|max:255',
                'weight' => 'required|numeric|min:0',
                'quantity' => 'nullable|integer|min:1',
                'catch_date' => 'required|date',
                'catch_time' => 'nullable|date_format:H:i',
                'location_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'fishing_method' => 'nullable|string',
                'notes' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
                'weather' => 'nullable|string',
                'water_temp' => 'nullable|numeric|between:-10,50',
            ]);

            // ✅ Format caught_at dengan benar
            $caughtAt = $validated['catch_date'];
            if (!empty($validated['catch_time'])) {
                $caughtAt .= ' ' . $validated['catch_time'] . ':00';
            } else {
                $caughtAt .= ' 00:00:00';
            }

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
                'caught_at' => $caughtAt,
                'catch_time' => $validated['catch_time'] ?? null,
                'fishing_method' => $validated['fishing_method'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'weather' => $validated['weather'] ?? null,
                'water_temp' => $validated['water_temp'] ?? null,
            ];

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('catches', 'public');
                $data['photo'] = $photoPath;
                
                Log::info('Photo uploaded', ['path' => $photoPath]);
            }

            // ✅ Log data sebelum insert
            Log::info('Data to be inserted', $data);

            // ✅ Gunakan DB transaction untuk safety
            DB::beginTransaction();
            
            try {
                $catch = FishCatch::create($data);
                
                DB::commit();
                
                Log::info('Fish catch created successfully', [
                    'id' => $catch->id,
                    'data' => $catch->toArray()
                ]);

                return redirect()->route('catches.index')
                    ->with('success', '✅ Data penangkapan berhasil disimpan!');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', '⚠️ Validasi gagal! Periksa input Anda.');

        } catch (\Exception $e) {
            Log::error('Failed to create fish catch', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', '❌ Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}