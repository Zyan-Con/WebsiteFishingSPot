<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $email = $request->email;
        $password = $request->password;

        // 1. CEK EMAIL DI DATABASE
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            Log::error("Admin Login Failed: Email not found - {$email}");
            
            return back()
                ->withErrors(['email' => 'Email tidak terdaftar di sistem'])
                ->withInput($request->except('password'));
        }

        // 2. CEK PASSWORD
        if (!Hash::check($password, $admin->password)) {
            Log::error("Admin Login Failed: Wrong password for - {$email}");
            
            return back()
                ->withErrors(['password' => 'Password yang Anda masukkan salah'])
                ->withInput($request->except('password'));
        }

        // 3. LOGIN MENGGUNAKAN AUTH GUARD ADMIN
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            // Regenerate session
            $request->session()->regenerate();
            
            Log::info("Admin Login Success: {$admin->name} ({$email})");
            
            // Redirect ke dashboard
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Selamat datang, ' . $admin->name . '!');
        }

        // 4. JIKA MASIH GAGAL (SEHARUSNYA TIDAK SAMPAI SINI)
        Log::error("Admin Login Failed: Auth attempt failed for {$email}");
        
        return back()
            ->withErrors(['email' => 'Gagal login. Silakan coba lagi.'])
            ->withInput($request->except('password'));
    }

    // Logout
    public function logout(Request $request)
    {
        $adminName = Auth::guard('admin')->user()->name;
        
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info("Admin Logout: {$adminName}");

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda telah berhasil logout');
    }
}
