<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'is_active',
        'subscribed_at',
        'unsubscribed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime'
    ];

    /**
     * Validation rules for Newsletter model
     */
    public static function validationRules($id = null): array
    {
        return [
            'email' => 'required|email|max:255|unique:newsletters,email,' . $id,
            'is_active' => 'boolean',
            'subscribed_at' => 'nullable|date',
            'unsubscribed_at' => 'nullable|date'
        ];
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive subscribers
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Subscribe an email
     */
    public static function subscribe($email): bool
    {
        return static::updateOrCreate(
            ['email' => $email],
            ['is_active' => true, 'subscribed_at' => now()]
        ) !== null;
    }

    /**
     * Unsubscribe an email
     */
    public function unsubscribe(): bool
    {
        return $this->update([
            'is_active' => false,
            'unsubscribed_at' => now()
        ]);
    }

    /**
     * Check if email is subscribed
     */
    public static function isSubscribed($email): bool
    {
        return static::where('email', $email)->where('is_active', true)->exists();
    }
}
