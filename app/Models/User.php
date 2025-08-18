<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'payment_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'payment_verified' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function getRoleName(): string
    {
        return $this->role->name;
    }

    // FIXED: Unified role checking
    public function hasRole($roleName): bool
    {
        // Admin has all roles
        if ($this->isAdmin()) {
            return true;
        }

        return $this->role && $this->role->name === $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->getRoleName() === 'admin';
    }

    public function isHostelManager(): bool
    {
        return $this->getRoleName() === 'hostel_manager';
    }

    public function isStudent(): bool
    {
        return $this->getRoleName() === 'student';
    }
}
