<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'admin_id',
        'subject',
        'message',
        'admin_reply',
        'replied_at',
        'is_read',
        'is_read_by_admin',
        'status',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_read_by_admin' => 'boolean',
        'replied_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}