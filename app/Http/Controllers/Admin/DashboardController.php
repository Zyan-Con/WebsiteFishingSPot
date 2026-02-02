<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FishCatch;
use App\Models\Spot;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Overview
        $stats = [
            'total_users' => User::count(),
            'total_catches' => FishCatch::count(),
            'total_spots' => Spot::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Chart: User growth (7 hari terakhir)
        $userGrowth = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // Kalau gak ada data growth, buat dummy
        if ($userGrowth->isEmpty()) {
            $userGrowth = collect([
                ['date' => Carbon::now()->subDays(6)->toDateString(), 'total' => 0],
                ['date' => Carbon::now()->subDays(5)->toDateString(), 'total' => 0],
                ['date' => Carbon::now()->subDays(4)->toDateString(), 'total' => 0],
                ['date' => Carbon::now()->subDays(3)->toDateString(), 'total' => 0],
                ['date' => Carbon::now()->subDays(2)->toDateString(), 'total' => 0],
                ['date' => Carbon::now()->subDays(1)->toDateString(), 'total' => 0],
                ['date' => Carbon::now()->toDateString(), 'total' => 0],
            ]);
        }

        // Recent Users (5 terakhir)
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Recent Catches (5 terakhir)
        $recentCatches = FishCatch::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'userGrowth',
            'recentUsers',
            'recentCatches'
        ));
    }
}