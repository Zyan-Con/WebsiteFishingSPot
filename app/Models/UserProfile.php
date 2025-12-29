<?php

namespace App\Models;

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

    // Relasi One-to-One
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
