<?php

namespace App\Services;

use App\Models\OwnerNetworkProfile;

class DirectoryService
{
    /**
     * मालिकहरू खोज्ने
     */
    public function searchOwners($filters = [])
    {
        $query = OwnerNetworkProfile::with('user')
            ->where('tenant_id', session('tenant_id'));

        if (!empty($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        if (!empty($filters['services'])) {
            $query->whereJsonContains('services', $filters['services']);
        }

        if (!empty($filters['pricing_category'])) {
            $query->where('pricing_category', $filters['pricing_category']);
        }

        if (!empty($filters['min_size'])) {
            $query->where('hostel_size', '>=', $filters['min_size']);
        }

        if (!empty($filters['max_size'])) {
            $query->where('hostel_size', '<=', $filters['max_size']);
        }

        if (!empty($filters['verified_only'])) {
            $query->where('is_verified', true);
        }

        return $query->paginate(20);
    }
}
