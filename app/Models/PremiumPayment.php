<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PremiumPayment extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'status',
        'qris_url',
        'expires_at',
        'paid_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast() && $this->status === 'pending';
    }
}