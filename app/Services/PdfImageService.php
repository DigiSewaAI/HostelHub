<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfImageService
{
    /**
     * Get hostel logo as base64 for PDF generation
     * SIMPLIFIED VERSION - No external dependencies
     */
    public function getHostelLogoForPdf($hostelId)
    {
        try {
            $hostel = \App\Models\Hostel::find($hostelId);

            if (!$hostel || !$hostel->logo_path) {
                return $this->generateDefaultLogo($hostel ? $hostel->name : 'Hostel');
            }

            // Get file path
            $storagePath = storage_path('app/public/' . $hostel->logo_path);

            // If storage path doesn't exist, try public path
            if (!file_exists($storagePath)) {
                $publicPath = public_path('storage/' . $hostel->logo_path);
                if (file_exists($publicPath)) {
                    $storagePath = $publicPath;
                } else {
                    return $this->generateDefaultLogo($hostel->name);
                }
            }

            // Get file info
            $mimeType = mime_content_type($storagePath);

            // Only allow specific image types
            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/svg+xml' => 'svg',
                'image/webp' => 'webp'
            ];

            if (!array_key_exists($mimeType, $allowedMimes)) {
                return $this->generateDefaultLogo($hostel->name);
            }

            // Read file and convert to base64
            $imageData = file_get_contents($storagePath);

            if (!$imageData) {
                return $this->generateDefaultLogo($hostel->name);
            }

            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            Log::error('PDF Logo Service Error: ' . $e->getMessage());
            return $this->generateDefaultLogo('Hostel');
        }
    }

    /**
     * Generate a default logo using pure PHP GD (no Intervention)
     */
    private function generateDefaultLogo($hostelName)
    {
        try {
            $initial = strtoupper(substr(trim($hostelName), 0, 1));
            if (empty($initial)) $initial = 'H';

            $size = 100;

            // Check if GD is available
            if (!function_exists('imagecreatetruecolor')) {
                return null;
            }

            $image = imagecreatetruecolor($size, $size);

            if (!$image) {
                return null;
            }

            // Background colors
            $colors = [
                [59, 130, 246],   // Blue
                [16, 185, 129],   // Green
                [245, 158, 11],   // Orange
                [139, 92, 246],   // Purple
                [239, 68, 68],    // Red
            ];

            $colorIndex = crc32($hostelName) % count($colors);
            $color = $colors[$colorIndex];

            $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
            $textColor = imagecolorallocate($image, 255, 255, 255);

            imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);

            // Add text using GD built-in font
            $font = 5; // GD built-in font (1-5)
            $textWidth = imagefontwidth($font) * strlen($initial);
            $textHeight = imagefontheight($font);
            $x = ($size - $textWidth) / 2;
            $y = ($size - $textHeight) / 2;

            imagestring($image, $font, $x, $y, $initial, $textColor);

            // Add border
            $borderColor = imagecolorallocate($image, 255, 255, 255);
            imagerectangle($image, 0, 0, $size - 1, $size - 1, $borderColor);

            // Output as PNG base64
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);

            return 'data:image/png;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            Log::error('Default Logo Generation Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simple check if image exists and get base64
     */
    public function getSimpleLogoForPdf($hostel)
    {
        try {
            if (!$hostel || !$hostel->logo_path) {
                return null;
            }

            // Try multiple paths
            $paths = [
                storage_path('app/public/' . $hostel->logo_path),
                public_path('storage/' . $hostel->logo_path),
                base_path('public/storage/' . $hostel->logo_path),
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $mimeType = mime_content_type($path);
                    $imageData = file_get_contents($path);

                    if ($imageData) {
                        return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
