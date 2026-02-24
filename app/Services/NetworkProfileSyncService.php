<?php

namespace App\Services;

use App\Models\Hostel;
use App\Models\OwnerNetworkProfile;
use Illuminate\Support\Facades\Log;

class NetworkProfileSyncService
{
    /**
     * Sync network profile for a given hostel.
     * Creates or updates based on hostel eligibility.
     */
    public function syncForHostel(Hostel $hostel): ?OwnerNetworkProfile
    {
        // If hostel is not eligible, delete any existing profile and return null
        if (!$hostel->isEligibleForNetwork()) {
            if ($hostel->networkProfile) {
                $hostel->networkProfile->delete();
                Log::info("Deleted network profile for ineligible hostel", ['hostel_id' => $hostel->id]);
            }
            return null;
        }

        // Build snapshot data from hostel (and related models if needed)
        $snapshot = [
            'name'            => $hostel->name,
            'phone'           => $hostel->contact_phone,
            'email'           => $hostel->contact_email,
            'city'            => $hostel->city,
            'address'         => $hostel->address,
            'description'     => $hostel->description,
            'total_rooms'     => $hostel->total_rooms,
            'available_rooms' => $hostel->available_rooms,
            'facilities'      => $hostel->facilities,
            'logo_path'       => $hostel->logo_path,
            'occupancy_rate'  => $hostel->occupancy_rate, // using existing accessor
            'min_price'       => $hostel->min_price,
            'max_price'       => $hostel->max_price,
        ];

        // Update or create profile
        $profile = OwnerNetworkProfile::updateOrCreate(
            ['hostel_id' => $hostel->id],
            [
                'auto_snapshot' => $snapshot,
                'verified_at'   => $hostel->networkProfile?->verified_at, // preserve if already verified
            ]
        );

        Log::info("Synced network profile for hostel", ['hostel_id' => $hostel->id]);

        return $profile;
    }
}
