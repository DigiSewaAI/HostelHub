<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class MealMenu extends Model
{
    protected $fillable = [
        'hostel_id',
        'meal_type',
        'day_of_week',
        'items',
        'image',
        'description',
        'is_active'
    ];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean'
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Scope to get only active meal menus
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by day of week
     */
    public function scopeForDay(Builder $query, string $day): Builder
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Scope to filter by meal type
     */
    public function scopeForMealType(Builder $query, string $mealType): Builder
    {
        return $query->where('meal_type', $mealType);
    }

    /**
     * Get breakfast items
     */
    public function getBreakfastAttribute(): string
    {
        return $this->items['breakfast'] ?? 'उपलब्ध छैन';
    }

    /**
     * Get lunch items
     */
    public function getLunchAttribute(): string
    {
        return $this->items['lunch'] ?? 'उपलब्ध छैन';
    }

    /**
     * Get dinner items
     */
    public function getDinnerAttribute(): string
    {
        return $this->items['dinner'] ?? 'उपलब्ध छैन';
    }
}
