<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'hostel_id',
        'room_id',
        'is_read',
        'status',
    ];

    /**
     * Get the hostel associated with the contact.
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the room associated with the contact.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope a query to only include unread contacts.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include today's contacts.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include read contacts.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to only include contacts with replied status.
     */
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    /**
     * Check if contact is unread.
     */
    public function isUnread()
    {
        return !$this->is_read;
    }

    /**
     * Check if contact is from today.
     */
    public function isToday()
    {
        return $this->created_at->isToday();
    }

    /**
     * Mark contact as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'status' => 'read'
        ]);
    }

    /**
     * Mark contact as unread.
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'status' => 'unread'
        ]);
    }

    /**
     * Mark contact as replied.
     */
    public function markAsReplied()
    {
        $this->update([
            'status' => 'replied'
        ]);
    }

    /**
     * Get the display status.
     */
    public function getDisplayStatusAttribute()
    {
        if ($this->status == 'replied') {
            return 'जवाफ दिइयो';
        }

        return $this->is_read ? 'पढियो' : 'नपढिएको';
    }

    /**
     * Get the display status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        if ($this->status == 'replied') {
            return 'bg-info';
        }

        return $this->is_read ? 'bg-success' : 'bg-warning text-dark';
    }

    /**
     * Get truncated message for display.
     */
    public function getTruncatedMessageAttribute()
    {
        return \Str::limit($this->message, 50);
    }

    /**
     * Get contact's location info.
     */
    public function getLocationInfoAttribute()
    {
        $info = [];

        if ($this->hostel) {
            $info[] = "होस्टल: {$this->hostel->name}";
        }

        if ($this->room) {
            $info[] = "कोठा: {$this->room->room_number}";
        }

        return $info ? implode(', ', $info) : '—';
    }
}
