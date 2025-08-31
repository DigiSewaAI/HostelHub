<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
