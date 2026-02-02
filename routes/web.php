<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ============================================
// IMPORT CONTROLLERS - USER
// ============================================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CatchController;
use App\Http\Controllers\SpotController;  
use App\Http\Controllers\SettingsController;  
use App\Http\Controllers\FishCatchController;
use App\Http\Controllers\PremiumController; 
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;


// ============================================
// IMPORT CONTROLLERS - ADMIN
// ============================================
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

Route::middleware(['auth'])->group(function () {
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/data', [ProfileController::class, 'getProfile'])->name('profile.data');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/data', [ProfileController::class, 'getProfile'])->name('profile.data');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/users/{id}/update-rating', [App\Http\Controllers\Admin\UserController::class, 'updateRating'])->name('users.update-rating');
    // Route profile - HARUS ada middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/data', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
});
});

// ============================================
// GUEST ROUTES (Belum Login)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ============================================
// GOOGLE OAUTH ROUTES
// ============================================
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// ============================================
// USER ROUTES (Sudah Login - User Biasa)
// ============================================
Route::middleware('auth')->group(function () {
    // Dashboard User
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
    Route::resource('catches', FishCatchController::class);
    Route::post('/catches/{catch}/update-photo', [FishCatchController::class, 'updatePhoto'])->name('catches.updatePhoto');
    
    // Forecast Routes - Fish Activity & Weather
    Route::prefix('forecast')->name('forecast.')->group(function () {
        Route::get('/', [ForecastController::class, 'index'])->name('index');
        Route::get('/activity', [ForecastController::class, 'activity'])->name('activity');
        Route::get('/weather', [ForecastController::class, 'weather'])->name('weather');
        Route::get('/tide', [ForecastController::class, 'tide'])->name('tide');
        Route::get('/wave', [ForecastController::class, 'wave'])->name('wave');
    });
    
    // Premium Routes
    Route::get('/premium', [PremiumController::class, 'index'])->name('premium.index');
    Route::post('/premium/create-payment', [PremiumController::class, 'createPayment'])->name('premium.create');
    Route::get('/premium/verify/{orderId}', [PremiumController::class, 'verifyPayment'])->name('premium.verify');
    Route::post('/premium/confirm/{orderId}', [PremiumController::class, 'confirmPayment'])->name('premium.confirm');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');

        // ✅ USER FEEDBACK ROUTES - FIXED
        Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::post('/', [FeedbackController::class, 'store'])->name('store');
        Route::post('/{id}/read', [FeedbackController::class, 'markAsRead'])->name('read');
        Route::get('/unread-count', [FeedbackController::class, 'getUnreadCount'])->name('unread');
        });

    // Map
    Route::get('/map', function() {
        return view('map.index');
    })->name('map.index');
});

// ============================================
// API ROUTES (Public & Authenticated)
// ============================================

// API Spots (Public)
Route::get('/api/spots', [SpotController::class, 'index'])->name('spots.index');
Route::post('/api/spots', [SpotController::class, 'store'])->name('spots.store');
Route::get('/api/spots/{id}', [SpotController::class, 'show'])->name('spots.show');
Route::delete('/api/spots/{id}', [SpotController::class, 'destroy'])->name('spots.destroy');

// API Fish Activity
Route::get('/api/forecast/fish-activity', [ForecastController::class, 'getFishActivity'])->name('forecast.fish-activity');

// API Tide Data
Route::get('/api/tide-data', [ForecastController::class, 'getTideDataAjax'])->name('api.tide-data');

// API Wave Data
Route::get('/api/waves/data', [ForecastController::class, 'getData'])->name('api.waves.data');
Route::get('/api/waves/weekly', [ForecastController::class, 'getWeeklyForecast'])->name('api.waves.weekly');

// API Activity Forecast (Demo Data)
Route::get('/api/activity-forecast', function(Request $request) {
    $lat = $request->get('lat', -0.947136);
    $lng = $request->get('lng', 100.417419);
    $day = $request->get('day', 0);
    
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

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (belum login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });
    
    // Protected routes (sudah login sebagai admin)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Notifications Management
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('store');
            Route::delete('/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
        });
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
            Route::get('/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::post('/{id}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('ban');
            Route::post('/{id}/unban', [App\Http\Controllers\Admin\UserController::class, 'unban'])->name('unban');
            Route::post('/{id}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('reset-password');
            Route::delete('/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        });

        // Catches Management
        Route::prefix('catches')->name('catches.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CatchController::class, 'index'])->name('index');
            Route::get('/statistics', [App\Http\Controllers\Admin\CatchController::class, 'statistics'])->name('statistics');
            Route::get('/export', [App\Http\Controllers\Admin\CatchController::class, 'export'])->name('export');
            Route::get('/{id}', [App\Http\Controllers\Admin\CatchController::class, 'show'])->name('show');
            Route::delete('/{id}', [App\Http\Controllers\Admin\CatchController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [App\Http\Controllers\Admin\CatchController::class, 'bulkDelete'])->name('bulk-delete');
        });

        // Settings Management
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::put('/profile', [App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])->name('update-profile');
            Route::put('/password', [App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('update-password');
            Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'updateSettings'])->name('update-settings');
            Route::post('/clear-cache', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('clear-cache');
            Route::post('/optimize', [App\Http\Controllers\Admin\SettingsController::class, 'optimize'])->name('optimize');
            Route::post('/toggle-maintenance', [App\Http\Controllers\Admin\SettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
            Route::post('/backup-database', [App\Http\Controllers\Admin\SettingsController::class, 'backupDatabase'])->name('backup-database');
            Route::get('/system-info', [App\Http\Controllers\Admin\SettingsController::class, 'systemInfo'])->name('system-info');
        });

        // ✅ ADMIN FEEDBACK ROUTES - USING CONTROLLER (CLEAN & BEST PRACTICE)
        Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/', [AdminFeedbackController::class, 'index'])->name('index');
        Route::post('/{id}/reply', [AdminFeedbackController::class, 'reply'])->name('reply');
        Route::post('/{id}/read', [AdminFeedbackController::class, 'markAsRead'])->name('read');
        Route::delete('/{id}', [AdminFeedbackController::class, 'destroy'])->name('destroy');
    });
    });
}); 