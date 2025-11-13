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

    /**
     * Validation rules for circular recipient
     */
    public static function validationRules($id = null): array
    {
        return [
            'circular_id' => 'required|exists:circulars,id',
            'user_id' => 'required|exists:users,id',
            'user_type' => 'required|in:student,manager,admin,user',
            'is_read' => 'boolean',
            'read_at' => 'nullable|date'
        ];
    }

    // Relationships
    public function circular()
    {
        return $this->belongsTo(Circular::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id')
            ->where('user_type', 'student')->withDefault();
    }

    /**
     * Scope for user-specific recipients
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for circular-specific recipients
     */
    public function scopeForCircular($query, $circularId)
    {
        return $query->where('circular_id', $circularId);
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
