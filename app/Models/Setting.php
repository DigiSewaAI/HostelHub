<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'options',
        'description',
        'is_system'
    ];

    protected $casts = [
        'options' => 'array',
        'is_system' => 'boolean'
    ];

    /**
     * Validation rules for Setting model
     */
    public static function validationRules($id = null): array
    {
        return [
            'key' => 'required|string|max:255|unique:settings,key,' . $id,
            'value' => 'required|string|max:1000',
            'group' => 'required|string|max:100',
            'type' => 'required|in:string,integer,boolean,array,json',
            'options' => 'nullable|array',
            'description' => 'nullable|string|max:500',
            'is_system' => 'boolean'
        ];
    }

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value): bool
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            return $setting->update(['value' => $value]);
        }

        return static::create([
            'key' => $key,
            'value' => $value,
            'group' => 'general',
            'type' => 'string',
            'is_system' => false
        ]) !== null;
    }

    /**
     * Scope for system settings
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope for user settings
     */
    public function scopeUser($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Scope for group
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get setting groups
     */
    public static function getGroups(): array
    {
        return static::distinct()->pluck('group')->toArray();
    }

    /**
     * Check if setting is system protected
     */
    public function getIsProtectedAttribute(): bool
    {
        return $this->is_system;
    }

    /**
     * Check if user can modify this setting
     */
    public function canBeModifiedBy($user): bool
    {
        // Only allow modification of non-system settings for regular users
        if (!$this->is_system) {
            return true;
        }

        // System settings can only be modified by admins
        return $user->isAdmin();
    }

    /**
     * Get setting value with proper type casting
     */
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'boolean' => (bool) $this->value,
            'array', 'json' => json_decode($this->value, true) ?? [],
            default => $this->value
        };
    }
}
