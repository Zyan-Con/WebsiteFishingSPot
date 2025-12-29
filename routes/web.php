<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CatchController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\SpotController;  
use App\Http\Controllers\SettingsController;  
use App\Http\Controllers\FishCatchController;
use App\Http\Controllers\PremiumController; 
use App\Http\Controllers\WaveController; // ✅ HANYA SATU KALI

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Google OAuth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile Routes
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update.profile');
    Route::post('/settings/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.update.appearance');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.update.notifications');

    // Locations
    Route::resource('locations', LocationController::class);
    
    // Fish Catches Management
    Route::get('/catches', [FishCatchController::class, 'index'])->name('catches.index');
    Route::get('/catches/create', [FishCatchController::class, 'create'])->name('catches.create');
    Route::post('/catches', [FishCatchController::class, 'store'])->name('catches.store');
    Route::get('/catches/{catch}', [FishCatchController::class, 'show'])->name('catches.show');
    Route::get('/catches/{catch}/edit', [FishCatchController::class, 'edit'])->name('catches.edit');
    Route::put('/catches/{catch}', [FishCatchController::class, 'update'])->name('catches.update');
    Route::delete('/catches/{catch}', [FishCatchController::class, 'destroy'])->name('catches.destroy');
    
    // Forecast routes - Fish Activity & Weather
        Route::prefix('forecast')->name('forecast.')->group(function () {
        Route::get('/activity', [ForecastController::class, 'activity'])->name('activity');
        Route::get('/weather', [ForecastController::class, 'weather'])->name('weather');
        Route::get('/forecast', [ForecastController::class, 'index']);
Route::get('/forecast/weather', [ForecastController::class, 'weather']);

        
        // Tide Forecast - pakai ForecastController
        Route::get('/tide', [ForecastController::class, 'tide'])->name('tide');
        
        // Wave Forecast - GANTI ke WaveController (BUKAN ForecastController)
        Route::get('/wave', [WaveController::class, 'index'])->name('wave');
    });
    
    // API Routes untuk Tide Data
    Route::get('/api/tide-data', [ForecastController::class, 'getTideDataAjax'])->name('api.tide-data');
    
    // API Routes untuk Wave Data
    Route::get('/api/waves/data', [WaveController::class, 'getData'])->name('api.waves.data');
    Route::get('/api/waves/weekly', [WaveController::class, 'getWeeklyForecast'])->name('api.waves.weekly');
});

// API Routes untuk Spots (Public)
Route::get('/api/spots', [SpotController::class, 'index'])->name('spots.index');
Route::post('/api/spots', [SpotController::class, 'store'])->name('spots.store');
Route::get('/api/spots/{id}', [SpotController::class, 'show'])->name('spots.show');
Route::delete('/api/spots/{id}', [SpotController::class, 'destroy'])->name('spots.destroy');

// Fish Activity API
Route::get('/api/forecast/fish-activity', [ForecastController::class, 'getFishActivity'])->name('forecast.fish-activity');

// API endpoint untuk activity forecast
Route::get('/api/activity-forecast', function(Request $request) {
    $lat = $request->get('lat', -0.947136);
    $lng = $request->get('lng', 100.417419);
    $day = $request->get('day', 0);
    
    // Di sini nanti bisa fetch dari API eksternal atau database
    // Untuk sekarang return demo data
    
    $days = ['TODAY', 'FRI', 'SAT', 'SUN', 'MON', 'TUE', 'WED'];
    $scores = [14, 45, 67, 82, 56, 34, 28];
    
    $hourlyScores = [
        10, 8, 12, 25, 45, 67, 85, 72, 55, 35, 20, 15,
        18, 30, 48, 62, 75, 88, 90, 78, 60, 40, 28, 18, 12
    ];
    
    return response()->json([
        'success' => true,
        'score' => $scores[$day],
        'day' => $days[$day],
        'moonPhase' => 'Waxing Crescent',
        'pressure' => 'Stable',
        'weather' => 'Cloudy',
        'majorTimes' => [
            ['start' => '06:24', 'end' => '08:24', 'duration' => '2 hours'],
            ['start' => '18:42', 'end' => '20:42', 'duration' => '2 hours']
        ],
        'minorTimes' => [
            ['start' => '00:12', 'end' => '01:12', 'duration' => '1 hour'],
            ['start' => '12:36', 'end' => '13:36', 'duration' => '1 hour']
        ],
        'hourlyScores' => $hourlyScores
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/premium', [PremiumController::class, 'index'])->name('premium.index');
    Route::post('/premium/create-payment', [PremiumController::class, 'createPayment'])->name('premium.create');
    Route::get('/premium/verify/{orderId}', [PremiumController::class, 'verifyPayment'])->name('premium.verify');
    Route::post('/premium/confirm/{orderId}', [PremiumController::class, 'confirmPayment'])->name('premium.confirm');
});

// Jika untuk searchung
Route::get('/map', function() {
    return view('map.index');
})->name('map.index');

