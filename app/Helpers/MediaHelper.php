<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    /**
     * Generate a consistent media URL for Railway deployment
     */
    public static function getMediaUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        // Remove any storage prefixes if present
        $path = str_replace('storage/', '', $path);
        $path = str_replace('public/', '', $path);

        // For Railway deployment - use direct URL structure
        // Assuming you've set MEDIA_URL in your .env
        if (config('app.media_url')) {
            return rtrim(config('app.media_url'), '/') . '/' . ltrim($path, '/');
        }

        // Fallback to storage URL for local development
        return Storage::disk('public')->url($path);
    }

    /**
     * Generate thumbnail URL
     */
    public static function getThumbnailUrl($path, $thumbnailPath = null)
    {
        if ($thumbnailPath) {
            return self::getMediaUrl($thumbnailPath);
        }

        return self::getMediaUrl($path);
    }

    /**
     * Check if file exists (Railway-specific)
     */
    public static function mediaExists($path)
    {
        if (empty($path)) {
            return false;
        }

        // On Railway, check if file exists in the storage
        $path = str_replace('storage/', '', $path);
        $path = str_replace('public/', '', $path);

        return Storage::disk('public')->exists($path);
    }
}