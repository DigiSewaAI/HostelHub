<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, Billable;

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
     * Get all hostels owned by the user.
     */
    public function hostels(): HasMany
    {
        return $this->hasMany(Hostel::class, 'owner_id');
    }

    /**
     * Get the role name of the user.
     */
    public function getRoleName(): string
    {
        return $this->role?->name ?? 'N/A';
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a hostel manager.
     */
    public function isHostelManager(): bool
    {
        return $this->hasRole('hostel_manager');
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }
}
