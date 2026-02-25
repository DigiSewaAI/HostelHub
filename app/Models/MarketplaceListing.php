<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class MarketplaceListing extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'owner_id',
        'title',
        'slug',
        'description',
        'type',
        'price',
        'location',
        'status',
        'moderated_at',
        'moderated_by',
        'moderation_notes',
        'views',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'moderated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function media()
    {
        return $this->hasMany(MarketplaceListingMedia::class, 'listing_id');
    }

    /**
     * Get the admin who approved this listing.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include approved listings.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include pending listings.
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending');
    }
}
