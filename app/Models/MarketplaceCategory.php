<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_np',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * सम्बन्ध: यो कोटि अन्तर्गतका लिस्टिङहरू
     */
    public function listings()
    {
        return $this->hasMany(MarketplaceListing::class, 'category_id');
    }

    /**
     * सक्रिय कोटिहरू मात्र ल्याउन scope
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * नेपाली नाम प्राथमिकतामा राखेर नाम लिने
     */
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'np' ? $this->name_np : $this->name_en;
    }
}
