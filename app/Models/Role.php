<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // रोलहरूको सम्बन्ध
    const ADMIN = 'admin';
    const HOSTEL_MANAGER = 'hostel_manager';
    const STUDENT = 'student';

    /**
     * Validation rules for role
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $id
        ];
    }

    /**
     * Scope for specific role
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Scope for admin role
     */
    public function scopeAdmin($query)
    {
        return $query->where('name', self::ADMIN);
    }

    /**
     * Scope for hostel manager role
     */
    public function scopeHostelManager($query)
    {
        return $query->where('name', self::HOSTEL_MANAGER);
    }

    /**
     * Scope for student role
     */
    public function scopeStudent($query)
    {
        return $query->where('name', self::STUDENT);
    }

    /**
     * Get users for this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class)->withDefault();
    }

    /**
     * Check if role is admin
     */
    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    /**
     * Check if role is hostel manager
     */
    public function isHostelManager(): bool
    {
        return $this->name === self::HOSTEL_MANAGER;
    }

    /**
     * Check if role is student
     */
    public function isStudent(): bool
    {
        return $this->name === self::STUDENT;
    }

    /**
     * Get role name in Nepali
     */
    public function getNepaliName(): string
    {
        $names = [
            self::ADMIN => 'प्रशासक',
            self::HOSTEL_MANAGER => 'होस्टेल प्रबन्धक',
            self::STUDENT => 'विद्यार्थी'
        ];

        return $names[$this->name] ?? $this->name;
    }
}
