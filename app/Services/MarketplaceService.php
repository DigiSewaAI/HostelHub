<?php

namespace App\Services;

use App\Models\MarketplaceListing;
use App\Models\MarketplaceListingMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarketplaceService
{
    /**
     * सूची सिर्जना गर्ने
     */
    public function createListing($ownerId, $data)
    {
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        $data['owner_id'] = $ownerId;
        $data['tenant_id'] = session('tenant_id');

        return MarketplaceListing::create($data);
    }

    /**
     * मिडिया अपलोड गर्ने
     */
    public function handleMediaUpload($listing, $files)
    {
        foreach ($files as $index => $file) {
            $path = $file->store('marketplace/' . $listing->id, 'public');

            MarketplaceListingMedia::create([
                'listing_id' => $listing->id,
                'file_path' => $path,
                'order' => $index,
            ]);
        }
    }

    /**
     * सूची स्वीकृत गर्ने
     */
    public function approveListing($listingId, $moderatorId)
    {
        $listing = MarketplaceListing::findOrFail($listingId);
        $listing->update([
            'status' => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);

        return $listing;
    }

    /**
     * हेराइको संख्या बढाउने
     */
    public function incrementViews($listing)
    {
        $listing->increment('views');
    }
}
