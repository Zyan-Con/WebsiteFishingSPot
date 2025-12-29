<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wave extends Model
{
    use HasFactory;

    protected $fillable = [
        'forecast_date',
        'forecast_time',
        'wave_height',
        'wave_period',
        'wave_direction',
        'location',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'wave_height' => 'decimal:2',
    ];

    // Helper untuk mendapatkan arah mata angin
    public function getDirectionNameAttribute()
    {
        $direction = $this->wave_direction;
        
        if ($direction >= 337.5 || $direction < 22.5) return 'N';
        if ($direction >= 22.5 && $direction < 67.5) return 'NE';
        if ($direction >= 67.5 && $direction < 112.5) return 'E';
        if ($direction >= 112.5 && $direction < 157.5) return 'SE';
        if ($direction >= 157.5 && $direction < 202.5) return 'S';
        if ($direction >= 202.5 && $direction < 247.5) return 'SW';
        if ($direction >= 247.5 && $direction < 292.5) return 'W';
        if ($direction >= 292.5 && $direction < 337.5) return 'NW';
        
        return 'N/A';
    }
}