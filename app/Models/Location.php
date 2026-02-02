<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'description',
        'photo',
        'latitude',
        'longitude',
        'end_latitude',
        'end_longitude',
        'rating',
        'last_visited_at',
        'depth',
        'difficulty',
        'fish_types',
        'hooks_count',
        'total_catch',
        'success_rate',
        'bait_type',
        'set_time',
        'haul_time',
        'rawai_distance',
        'distance_km',
        'duration_minutes',
        'avg_speed',
        'lure_type',
    ];

    protected $casts = [
        'fish_types' => 'array',
        'last_visited_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'end_latitude' => 'decimal:7',
        'end_longitude' => 'decimal:7',
        'depth' => 'decimal:2',
        'success_rate' => 'decimal:2',
        'rawai_distance' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'avg_speed' => 'decimal:2',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Auto-calculate success rate for rawai
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($location) {
            if ($location->type === 'rawai' && $location->hooks_count && $location->total_catch) {
                $location->success_rate = ($location->total_catch / $location->hooks_count) * 100;
            }
        });
    }
}