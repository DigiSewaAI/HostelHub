<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function getUrl($path, $default = null)
    {
        // Use the default from config if not provided
        $default = $default ?? config('services.images.default_gallery_thumbnail', 'images/default-image.jpg');

        // If image path exists and file exists in storage
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        // Otherwise return the default image URL
        return asset($default);
    }
}
