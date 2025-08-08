<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    // ✅ यो लाइन थप्नुहोस् (custom table name)
    protected $table = 'reviews';

    protected $fillable = [
        'name',
        'position',
        'content',
        'initials',
        'image',
        'type',
        'status',
        'rating'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
