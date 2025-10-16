<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircularRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'circular_id',
        'user_id',
        'user_type',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    // Relationships
    public function circular()
    {
        return $this->belongsTo(Circular::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id')
            ->where('user_type', 'student');
    }

    // Scope for read/unread
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Mark as read
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }
}
