<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    /**
     * बूट ट्रेट: ग्लोबल स्कोप र क्रियटिङ इभेन्ट थप्ने
     */
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            $builder->where(static::getTenantColumn(), static::getTenantId());
        });

        static::creating(function ($model) {
            if (!$model->{static::getTenantColumn()}) {
                $model->{static::getTenantColumn()} = static::getTenantId();
            }
        });
    }

    /**
     * सम्बन्ध: संगठन (Tenant)
     * यदि तपाईंसँग Tenant मोडेल छ भने मात्र प्रयोग गर्नुहोस्।
     */
    public function organization()
    {
        return $this->belongsTo(Tenant::class, static::getTenantColumn());
    }

    /**
     * टेन्ट स्तम्भको नाम फर्काउने (डिफल्ट: organization_id)
     * आवश्यक परे मोडेलमा ओभरराइड गर्न सकिन्छ।
     */
    protected static function getTenantColumn(): string
    {
        return defined('static::TENANT_COLUMN') ? static::TENANT_COLUMN : 'tenant_id';
    }

    /**
     * टेन्ट ID फर्काउने (डिफल्ट: 35)
     * आवश्यक परे मोडेलमा ओभरराइड गर्न सकिन्छ।
     */
    protected static function getTenantId(): int
    {
        // पहिले सेसनबाट हेर्ने (यदि छ भने)
        if (auth()->check() && session()->has('organization_id')) {
            return session('organization_id');
        }

        // नभए config बाट लिने (डिफल्ट 35)
        return config('app.organization_id', 35);
    }
}
