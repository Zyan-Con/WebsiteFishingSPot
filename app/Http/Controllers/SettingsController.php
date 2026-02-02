<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('settings')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update avatar (with crop)
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'crop_x' => 'nullable|numeric',
            'crop_y' => 'nullable|numeric',
            'crop_width' => 'nullable|numeric',
            'crop_height' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Upload and crop image
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = 'avatars/' . uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            
            // Create image instance
            $img = Image::make($image);
            
            // Apply crop if crop data provided
            if ($request->filled('crop_x')) {
                $img->crop(
                    (int)$request->crop_width,
                    (int)$request->crop_height,
                    (int)$request->crop_x,
                    (int)$request->crop_y
                );
            }
            
            // Resize to 300x300
            $img->fit(300, 300);
            
            // Save to storage
            Storage::disk('public')->put($filename, (string) $img->encode());
            
            $user->avatar = $filename;
            $user->save();
        }

        return redirect()->route('settings')->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Update banner (with crop)
     */
    public function updateBanner(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'crop_x' => 'nullable|numeric',
            'crop_y' => 'nullable|numeric',
            'crop_width' => 'nullable|numeric',
            'crop_height' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        // Delete old banner if exists
        if ($user->banner && Storage::disk('public')->exists($user->banner)) {
            Storage::disk('public')->delete($user->banner);
        }

        // Upload and crop image
        if ($request->hasFile('banner')) {
            $image = $request->file('banner');
            $filename = 'banners/' . uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            
            // Create image instance
            $img = Image::make($image);
            
            // Apply crop if crop data provided
            if ($request->filled('crop_x')) {
                $img->crop(
                    (int)$request->crop_width,
                    (int)$request->crop_height,
                    (int)$request->crop_x,
                    (int)$request->crop_y
                );
            }
            
            // Resize to 1200x400 (banner size)
            $img->fit(1200, 400);
            
            // Save to storage
            Storage::disk('public')->put($filename, (string) $img->encode());
            
            $user->banner = $filename;
            $user->save();
        }

        return redirect()->route('settings')->with('success', 'Banner berhasil diperbarui!');
    }

    /**
     * Update appearance settings
     */
    public function updateAppearance(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $user = Auth::user();
        $user->theme = $request->theme;
        $user->save();

        return redirect()->route('settings')->with('success', 'Tema berhasil diperbarui!');
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $user->email_notifications = $request->email_notifications ?? 0;
        $user->weather_notifications = $request->weather_notifications ?? 0;
        $user->save();

        return redirect()->route('settings')->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }
}