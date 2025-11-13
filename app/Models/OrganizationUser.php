<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table = 'organization_user';

    protected $fillable = [
        'organization_id',
        'user_id',
        'role'
    ];

    protected $casts = [
        'role' => 'string'
    ];

    /**
     * Validation rules for OrganizationUser model
     */
    public static function validationRules($id = null): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:owner,admin,manager,viewer'
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for organization members
     */
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope for user's organization memberships
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific roles
     */
    public function scopeWithRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Check if member is owner
     */
    public function getIsOwnerAttribute(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if member is admin
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if member is manager
     */
    public function getIsManagerAttribute(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if member is viewer
     */
    public function getIsViewerAttribute(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Get role in Nepali
     */
    public function getRoleNepaliAttribute(): string
    {
        $roles = [
            'owner' => 'मालिक',
            'admin' => 'प्रशासक',
            'manager' => 'प्रबन्धक',
            'viewer' => 'दर्शक'
        ];

        return $roles[$this->role] ?? $this->role;
    }

    /**
     * Check if user can modify this organization user
     */
    public function canBeModifiedBy($user): bool
    {
        // Only organization owners can modify members
        return $this->organization->isOwnedBy($user);
    }

    /**
     * Check if role can be changed
     */
    public function getCanRoleBeChangedAttribute(): bool
    {
        // Cannot change owner role and cannot change your own role
        return !$this->is_owner;
    }
}
