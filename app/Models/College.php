<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'contact_email'
    ];

    protected $table = 'colleges';

    /**
     * Validation rules for college
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:colleges,name,' . $id,
            'location' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255|unique:colleges,contact_email,' . $id
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
     * Scope for searching colleges by name or location
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('location', 'like', "%{$searchTerm}%");
    }
}
