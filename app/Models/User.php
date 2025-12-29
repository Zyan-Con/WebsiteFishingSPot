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
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi One-to-One dengan UserProfile
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
}

// Model UserProfile untuk relasi One-to-One
namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'bio',
        'favorite_spot',
        'fishing_experience',
    ];

        protected $hidden = [
        'password',
        'remember_token',
    ];

        protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // Relasi One-to-One dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}