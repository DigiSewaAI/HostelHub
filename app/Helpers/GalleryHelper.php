<?php

namespace App\Helpers;

use App\Models\Hostel;

class GalleryHelper
{
    /**
     * Get gallery mode based on published hostels count
     */
    public static function getGalleryMode(): string
    {
        $totalPublished = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->count();

        return $totalPublished <= 10 ? 'simple' : 'dynamic';
    }

    /**
     * Get gallery mode description
     */
    public static function getGalleryModeDescription(): string
    {
        $mode = self::getGalleryMode();
        $totalPublished = Hostel::where('is_published', true)
            ->where('status', 'active')
            ->count();

        if ($mode === 'simple') {
            return "सरल मोड ({$totalPublished} प्रकाशित होस्टल) - केवल वास्तविक छविहरू";
        } else {
            return "डाइनामिक मोड ({$totalPublished} प्रकाशित होस्टल) - वास्तविक + फलब्याक छविहरू";
        }
    }
}