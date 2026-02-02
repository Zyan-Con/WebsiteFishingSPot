<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data admin yang sedang login
        $admin = Auth::guard('admin')->user();
        
        // Ambil statistik (opsional, bisa disesuaikan)
        $stats = [
            'total_users' => User::count(),
            'total_admins' => Admin::count(),
            'total_spots' => 0, // Sesuaikan dengan model Anda
            'total_catches' => 0, // Sesuaikan dengan model Anda
        ];
        
        return view('admin.dashboard', compact('admin', 'stats'));
    }
}