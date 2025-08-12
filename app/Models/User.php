<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'payment_verified' // student_id हटाइयो किनभने यो गलत रिलेसनसिप हो
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'payment_verified' => 'boolean',
    ];

    /**
     * Get the role associated with the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the student record associated with the user.
     * NOTE: Students table has user_id field, so User hasOne Student
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role_id === 1; // 1 = admin role_id (RoleSeeder मा परिभाषित)
    }

    /**
     * Check if user has hostel manager role
     */
    public function isHostelManager(): bool
    {
        return $this->role_id === 2; // 2 = hostel_manager role_id
    }

    /**
     * Check if user has student role
     */
    public function isStudent(): bool
    {
        return $this->role_id === 3; // 3 = student role_id (RoleSeeder मा परिभाषित)
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string|int $role): bool
    {
        if (is_numeric($role)) {
            return $this->role_id === (int)$role;
        }

        return $this->role && $this->role->name === $role;
    }

    /**
     * Accessor for role name
     */
    public function getRoleNameAttribute(): ?string
    {
        return $this->role->name ?? null;
    }

    /**
     * Determine if the user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        // Convert all roles to IDs if they are strings
        $roleIds = [];
        $roleNames = [];

        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $roleIds[] = (int)$role;
            } else {
                $roleNames[] = $role;
            }
        }

        if ($roleIds && in_array($this->role_id, $roleIds)) {
            return true;
        }

        if ($roleNames && $this->role && in_array($this->role->name, $roleNames)) {
            return true;
        }

        return false;
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role_id', 1);
    }

    /**
     * Scope for hostel manager users
     */
    public function scopeHostelManagers($query)
    {
        return $query->where('role_id', 2);
    }

    /**
     * Scope for student users
     */
    public function scopeStudents($query)
    {
        return $query->where('role_id', 3);
    }

    /**
     * Check if payment is verified
     */
    public function isPaymentVerified(): bool
    {
        return (bool)$this->payment_verified;
    }
}
