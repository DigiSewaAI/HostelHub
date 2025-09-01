<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;  // ✅ Spatie Permission Trait

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;  // ✅ HasRoles trait थपियो

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'payment_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'payment_verified' => 'boolean',
    ];

    /**
     * Get the role that this user belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the student profile associated with the user.
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the role name of the user.
     */
    public function getRoleName(): string
    {
        return $this->role?->name ?? 'N/A';
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($roleName): bool
    {
        // Admin has all roles
        if ($this->isAdmin()) {
            return true;
        }

        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->getRoleName() === 'admin';
    }

    /**
     * Check if the user is a hostel manager.
     */
    public function isHostelManager(): bool
    {
        return $this->getRoleName() === 'hostel_manager';
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->getRoleName() === 'student';
    }
}
