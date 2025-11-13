<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'duration',
        'description'
    ];

    protected $table = 'courses';

    /**
     * Validation rules for Course model
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:courses,name,' . $id,
            'duration' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get all students enrolled in this course
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Scope for active courses (not deleted)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope for courses accessible by user
     */
    public function scopeForUser($query, $userId)
    {
        // If user has specific course access logic, implement here
        return $query->active();
    }

    /**
     * Check if course can be safely deleted
     */
    public function getCanBeDeletedAttribute(): bool
    {
        return $this->students()->count() === 0;
    }
}
