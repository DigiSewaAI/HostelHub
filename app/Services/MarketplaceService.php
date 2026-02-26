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
    public function createListing($ownerId, array $data): MarketplaceListing
    {
        // slug पहिल्यै नभएको भए बनाउने
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        }

        $data['owner_id'] = $ownerId;
        $data['tenant_id'] = session('tenant_id', 35); // डिफल्ट 35

        // यदि quantity नभए 1 सेट गर्ने
        if (!isset($data['quantity']) || $data['quantity'] < 1) {
            $data['quantity'] = 1;
        }

        return MarketplaceListing::create($data);
    }

    /**
     * सूची अपडेट गर्ने (owner वा admin)
     */
    public function updateListing(MarketplaceListing $listing, array $data): bool
    {
        // यदि title परिवर्तन भएमा slug पनि अपडेट गर्ने
        if (isset($data['title']) && $listing->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        }

        if (isset($data['quantity']) && $data['quantity'] < 1) {
            $data['quantity'] = 1;
        }

        return $listing->update($data);
    }

    /**
     * मिडिया अपलोड ह्यान्डल गर्ने (धेरै फाइल)
     */
    public function handleMediaUpload(MarketplaceListing $listing, array $files): void
    {
        foreach ($files as $index => $file) {
            $path = $file->store('marketplace/' . $listing->id, 'public');

            MarketplaceListingMedia::create([
                'listing_id' => $listing->id,
                'file_path'  => $path,
                'order'      => $index,
            ]);
        }
    }

    /**
     * एकल मिडिया मेटाउने
     */
    public function deleteMedia(MarketplaceListingMedia $media): bool
    {
        // storage बाट फाइल मेटाउने
        \Storage::disk('public')->delete($media->file_path);
        return $media->delete();
    }

    /**
     * सूचीको सबै मिडिया मेटाउने (जब सूची मेटिन्छ)
     */
    public function deleteAllMedia(MarketplaceListing $listing): void
    {
        foreach ($listing->media as $media) {
            $this->deleteMedia($media);
        }
    }

    /**
     * सूची स्वीकृत गर्ने (admin द्वारा)
     */
    public function approveListing(MarketplaceListing $listing, int $moderatorId): MarketplaceListing
    {
        $listing->update([
            'status'       => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
            'approved_by'  => $moderatorId,
            'approved_at'  => now(),
            'rejected_reason' => null,
        ]);

        return $listing;
    }

    /**
     * सूची अस्वीकृत गर्ने (admin द्वारा)
     */
    public function rejectListing(MarketplaceListing $listing, int $moderatorId, string $reason): MarketplaceListing
    {
        $listing->update([
            'status'          => 'rejected',
            'moderated_by'    => $moderatorId,
            'moderated_at'    => now(),
            'approved_by'     => null,
            'approved_at'     => null,
            'rejected_reason' => $reason,
        ]);

        return $listing;
    }

    /**
     * हेराइको संख्या बढाउने
     */
    public function incrementViews(MarketplaceListing $listing): void
    {
        $listing->increment('views');
    }
}
