<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FishCatch extends Model
{
    protected $table = 'fish_catches';

    protected $fillable = [
        'user_id',
        'fish_type',
        'weight',
        'length',
        'quantity',
        'location',
        'location_name',
        'latitude',
        'longitude',
        'photo',
        'caught_at',
        'catch_time',
        'fishing_method',
        'notes',
        'weather',
        'water_temp',
    ];

    protected $casts = [
        'caught_at' => 'datetime',
        // ✅ PERBAIKAN: Hapus cast untuk catch_time karena tipe TIME
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'water_temp' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ PERBAIKAN: Perbaiki typo Weight -> weight
    public function getFormattedWeightAttribute(): string
    {
        return number_format($this->weight, 2) . ' kg'; // Huruf w kecil
    }

    public function getTotalWeightAttribute(): float
    {
        return $this->weight * ($this->quantity ?? 1);
    }
}