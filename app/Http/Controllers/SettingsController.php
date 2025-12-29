<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
     * Update profile settings
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('settings')->with('success', 'Profil berhasil diperbarui!');
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
        $user->email_notifications = $request->has('email_notifications') ? 1 : 0;
        $user->weather_notifications = $request->has('weather_notifications') ? 1 : 0;
        $user->save();

        return redirect()->route('settings')->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
    }
}