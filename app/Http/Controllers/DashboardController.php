<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */public function index()
{
    $user = Auth::user();
    
    if (!$user->profile) {
        $user->profile()->create([]);
    }
    
    return view('dashboard', [
        'user' => $user,
        'profile' => $user->profile
    ]);
}
}