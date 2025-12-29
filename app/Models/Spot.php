<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'type',
        'latitude',
        'longitude',
        'rating',
        'is_verified',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_verified' => 'boolean',
    ];

    // Relasi Many-to-One dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
