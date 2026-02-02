<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_banned',
        'banned_at', 
        'google_id',
        'avatar',
        'banner',
        'bio',
        'rating',
        'total_reviews',
        'theme',                    // ✅ TAMBAHKAN INI
        'email_notifications',      // ✅ TAMBAHKAN INI
        'weather_notifications',    // ✅ TAMBAHKAN INI
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'is_premium' => 'boolean',
            'password' => 'hashed',
            'rating' => 'decimal:2',
            'email_notifications' => 'boolean',      // ✅ TAMBAHKAN INI
            'weather_notifications' => 'boolean',    // ✅ TAMBAHKAN INI
        ];
    }

    // Relasi One-to-One dengan UserProfile
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    // Relasi dengan FishCatch
    public function fishCatches()
    {
        return $this->hasMany(FishCatch::class);
    }

    // Relasi dengan Feedback
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}































// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     use HasFactory, Notifiable;

    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'is_banned',       // ← TAMBAHKAN INI
    //     'banned_at', 
    //     'google_id',
    //     'avatar',
    //     'banner',  // ← TAMBAH
    //     'bio',
    //     'rating',  // ← TAMBAH
    //     'total_reviews',  // ← TAMBAH
    // // ];

    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    

    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'is_banned' => 'boolean',          // ← TAMBAHKAN INI
    //         'banned_at' => 'datetime',   
    //         'is_premium' => 'boolean',
    //         'password' => 'hashed',
    //         'rating' => 'decimal:2',  // ← TAMBAH
    //     ];
    // }

    // Relasi One-to-One dengan UserProfile
    // public function profile()
    // {
    //     return $this->hasOne(UserProfile::class);
    // }

    // User yang mengikuti saya (followers)
//     public function followers()
//     {
//         return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
//                     ->withTimestamps();
//     }

//     // User yang saya ikuti (following)
//     public function following()
//     {
//         return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
//                     ->withTimestamps();
//     }

//     // Check apakah user ini mengikuti user lain
//     public function isFollowing($userId)
//     {
//         return $this->following()->where('following_id', $userId)->exists();
//     }

//     // Check apakah user ini di-follow oleh user lain
//     public function isFollowedBy($userId)
//     {
//         return $this->followers()->where('follower_id', $userId)->exists();
//     }

//     // Follow user lain
//     public function follow($userId)
//     {
//         if (!$this->isFollowing($userId)) {
//             $this->following()->attach($userId);
//         }
//     }
// }

// Model UserProfile untuk relasi One-to-One
// namespace App\models;

// use Illuminate\Database\Eloquent\Model;

// class UserProfile extends Model
// {
//     protected $fillable = [
//         'user_id',
//         'phone',
//         'address',
//         'avatar',
//         'bio',
//         'favorite_spot',
//         'fishing_experience',
//     ];

//         protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//         protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }


    // Relasi One-to-One dengan User
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // app/Models/User.php
//     public function fishCatches()
//     {
//         return $this->hasMany(FishCatch::class);
//     }
//         public function feedbacks()
//     {
//         return $this->hasMany(Feedback::class);
//     }
// } 