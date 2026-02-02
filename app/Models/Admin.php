<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',  // Laravel 10+
    ];

     public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
        // Helper method
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
}