<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
        // Removed 'location' and 'contact_email' as they don't exist in database
    ];

    protected $table = 'colleges';

    /**
     * Validation rules for college
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:colleges,name,' . $id
            // Removed location and contact_email validation as columns don't exist
        ];
    }

    /**
     * Get all students belonging to this college
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Scope for active colleges (not deleted)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope for searching colleges by name
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%");
        // Removed location search as column doesn't exist
    }
}
