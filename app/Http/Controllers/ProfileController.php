<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getProfile()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio ?? '',
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'banner' => $user->banner ? asset('storage/' . $user->banner) : null,
                    'rating' => number_format($user->rating ?? 0, 1),
                    'total_reviews' => $user->total_reviews ?? 0,
                ],
                'stats' => [
                    'posts' => $user->fishCatches()->count(),
                    'rating' => number_format($user->rating ?? 0, 1),
                    'total_reviews' => $user->total_reviews ?? 0,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get Profile Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            Log::info('Profile Update Started', [
                'user_id' => Auth::id(),
                'has_avatar' => $request->filled('avatar'),
                'has_banner' => $request->filled('banner'),
            ]);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
                'bio' => 'nullable|string|max:500',
                'avatar' => 'nullable|string',
                'banner' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $user = Auth::user();
            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->bio = $request->bio;

            if ($request->filled('avatar') && strpos($request->avatar, 'data:image') === 0) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $this->saveBase64Image($request->avatar, 'avatars');
            }

            if ($request->filled('banner') && strpos($request->banner, 'data:image') === 0) {
                if ($user->banner && Storage::disk('public')->exists($user->banner)) {
                    Storage::disk('public')->delete($user->banner);
                }
                $user->banner = $this->saveBase64Image($request->banner, 'banners');
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'banner' => $user->banner ? asset('storage/' . $user->banner) : null,
                    'rating' => number_format($user->rating ?? 0, 1),
                    'total_reviews' => $user->total_reviews ?? 0,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Profile Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function saveBase64Image($base64String, $folder)
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64String);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $filename = $folder . '/' . uniqid() . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }
} 