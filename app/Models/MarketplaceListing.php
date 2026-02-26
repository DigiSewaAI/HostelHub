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
        // नयाँ थपियो: step 1 बाट आएका fields
        'visibility',
        'category_id',
        'condition',
        'quantity',
        'price_type',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'moderated_at' => 'datetime',
        'approved_at' => 'datetime',
        // नयाँ थपियो
        'quantity' => 'integer',
        'visibility' => 'string',
        'condition' => 'string',
        'price_type' => 'string',
    ];

    /**
     * सम्बन्ध: मालिक (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * सम्बन्ध: मिडिया (तस्वीरहरू)
     */
    public function media()
    {
        return $this->hasMany(MarketplaceListingMedia::class, 'listing_id');
    }

    /**
     * सम्बन्ध: एडमिन जसले स्वीकृत गर्यो
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ========== नयाँ थपिएका सम्बन्धहरू ==========

    /**
     * सम्बन्ध: कोटि (Category)
     */
    public function category()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }

    /**
     * सम्बन्ध: होस्टल (यदि लिस्टिङ कुनै होस्टलसँग सम्बन्धित छ भने)
     * NOTE: यदि तपाईंको listing मा hostel_id छ भने मात्र प्रयोग गर्नुहोस्।
     * यदि hostel_id छैन भने यो सम्बन्ध हटाउनुहोस्।
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    // ========== स्कोपहरू (Scopes) ==========

    /**
     * Scope: स्वीकृत लिस्टिङ मात्र
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: पेन्डिङ लिस्टिङ मात्र
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * सार्वजनिक रूपमा देखिने लिस्टिङहरू (status approved, visibility public वा both)
     */
    public function scopePublic($query)
    {
        return $query->approved()
            ->whereIn('visibility', ['public', 'both']);
    }

    /**
     * नेटवर्क भित्र मात्र देखिने लिस्टिङहरू (status approved, visibility private वा both)
     */
    public function scopePrivate($query)
    {
        return $query->approved()
            ->whereIn('visibility', ['private', 'both']);
    }

    /**
     * नेटवर्कका लागि देखिने लिस्टिङ (private + both)
     * scopePrivate() को जस्तै, तर नाम फरक।
     */
    public function scopeVisibleInNetwork($query)
    {
        return $query->approved()
            ->whereIn('visibility', ['private', 'both']);
    }

    // ========== हेल्पर मेथडहरू ==========

    /**
     * के यो लिस्टिङ सार्वजनिक रूपमा देखिन्छ?
     */
    public function isPublic(): bool
    {
        return $this->status === 'approved' && in_array($this->visibility, ['public', 'both']);
    }

    /**
     * के यो लिस्टिङ नेटवर्क भित्र मात्र देखिन्छ?
     */
    public function isPrivate(): bool
    {
        return $this->status === 'approved' && in_array($this->visibility, ['private', 'both']);
    }

    /**
     * के यो लिस्टिङ मोलमोलाइ हुने (negotiable) हो?
     */
    public function isPriceNegotiable(): bool
    {
        return $this->price_type === 'negotiable';
    }

    /**
     * के यो नयाँ (new) हो वा प्रयोग गरिएको (used)?
     */
    public function getConditionLabel(): string
    {
        return $this->condition === 'new' ? 'नयाँ' : 'प्रयोग गरिएको';
    }
}
