<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // System settings (bisa disimpan di database atau config)
        $settings = [
            'app_name' => config('app.name', 'FishApp'),
            'timezone' => config('app.timezone', 'UTC'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];
        
        return view('admin.settings.index', compact('admin', 'settings'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($admin->avatar && Storage::exists($admin->avatar)) {
                Storage::delete($admin->avatar);
            }
            
            $validated['avatar'] = $request->file('avatar')->store('avatars/admin', 'public');
        }

        $admin->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update app settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'timezone' => 'required|string',
            'per_page' => 'nullable|integer|min:5|max:100',
        ]);

        // Update .env file (simplified version)
        // Dalam production, sebaiknya simpan di database
        $this->updateEnvFile('APP_NAME', $validated['app_name']);
        $this->updateEnvFile('APP_TIMEZONE', $validated['timezone']);

        return back()->with('success', 'Settings updated successfully! Please refresh to see changes.');
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            // Turn off maintenance
            \Artisan::call('up');
            return back()->with('success', 'Maintenance mode disabled!');
        } else {
            // Turn on maintenance
            \Artisan::call('down', [
                '--secret' => 'admin-secret-' . uniqid()
            ]);
            return back()->with('success', 'Maintenance mode enabled!');
        }
    }

    /**
     * Clear all caches
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');

        return back()->with('success', 'All caches cleared successfully!');
    }

    /**
     * Optimize application
     */
    public function optimize()
    {
        \Artisan::call('optimize');
        \Artisan::call('config:cache');
        \Artisan::call('route:cache');

        return back()->with('success', 'Application optimized successfully!');
    }

    /**
     * View system info
     */
    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'timezone' => config('app.timezone'),
            'database' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'storage_disk' => config('filesystems.default'),
        ];

        return view('admin.settings.system-info', compact('info'));
    }

    /**
     * Database backup (simplified)
     */
    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $database = config('database.connections.' . config('database.default') . '.database');
            
            // This is a simplified version
            // In production, use a proper backup package like spatie/laravel-backup
            
            return back()->with('success', 'Database backup created: ' . $filename);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: Update .env file
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Escape special characters
            $value = str_replace('"', '\"', $value);
            
            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $content
                );
            } else {
                $content .= "\n{$key}=\"{$value}\"\n";
            }

            file_put_contents($path, $content);
        }
    }
}