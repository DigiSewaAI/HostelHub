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

    // भूमिका सम्बन्धका लागि App\Models\Role प्रयोग गर्ने
    public function role(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    // भूमिका नाम पहुँच गर्ने एक्सेसर थप्ने
    public function getRoleAttribute(): ?string
    {
        return $this->roleRelation->name ?? null;
    }

    // सम्बन्धित भूमिका पहुँच गर्ने (नयाँ नाम)
    public function roleRelation(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    // भूमिका जाँचका लागि सही विधिहरू
    public function hasRole(string|int $role): bool
    {
        if (is_numeric($role)) {
            return $this->role_id === (int)$role;
        }

        // भूमिका नामले जाँच गर्दा सम्बन्ध लोड गर्ने
        if (!$this->relationLoaded('roleRelation')) {
            $this->load('roleRelation');
        }

        return $this->roleRelation && $this->roleRelation->name === $role;
    }

    // नयाँ विधि: भूमिका नाम फर्काउने
    public function getRoleName(): ?string
    {
        if (!$this->relationLoaded('roleRelation')) {
            $this->load('roleRelation');
        }

        return $this->roleRelation->name ?? null;
    }

    // अधिकार जाँचका विधिहरू (नयाँ संस्करण)
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isHostelManager(): bool
    {
        return $this->hasRole('hostel_manager');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    // अन्य विधिहरू जस्ताको तस्तै राख्ने...
    // hasAnyRole, scopeAdmins, आदि...
}
