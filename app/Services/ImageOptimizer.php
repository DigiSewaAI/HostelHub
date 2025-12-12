<?php

namespace App\Services;

class ImageOptimizer
{
    /**
     * Optimize image for gallery display
     */
    public function optimizeForGallery($path, $width = 280, $height = 280)
    {
        try {
            // Simple implementation without intervention/image
            $fullPath = storage_path('app/public/' . $path);
            
            if (!file_exists($fullPath)) {
                $fullPath = public_path($path);
                
                if (!file_exists($fullPath)) {
                    return null;
                }
            }

            // Get image info
            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                return null;
            }

            // Create a simple resized version using GD library (built into PHP)
            $image = $this->resizeImage($fullPath, $width, $height);
            
            if ($image) {
                // Output as base64
                ob_start();
                imagejpeg($image, null, 80);
                $imageData = ob_get_clean();
                imagedestroy($image);
                
                return 'data:image/jpeg;base64,' . base64_encode($imageData);
            }

            return null;

        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Simple image resize using GD
     */
    private function resizeImage($path, $width, $height)
    {
        $imageInfo = getimagesize($path);
        if (!$imageInfo) {
            return null;
        }

        $mime = $imageInfo['mime'];
        
        switch ($mime) {
            case 'image/jpeg':
                $srcImage = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($path);
                break;
            case 'image/gif':
                $srcImage = imagecreatefromgif($path);
                break;
            default:
                return null;
        }

        if (!$srcImage) {
            return null;
        }

        $srcWidth = imagesx($srcImage);
        $srcHeight = imagesy($srcImage);

        // Create new image
        $dstImage = imagecreatetruecolor($width, $height);
        
        // Preserve transparency for PNG
        if ($mime === 'image/png') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 0, 0, 0, 127);
            imagefill($dstImage, 0, 0, $transparent);
        }

        // Resize
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
        imagedestroy($srcImage);

        return $dstImage;
    }

    /**
     * Create a colored placeholder
     */
    public function createPlaceholder($width = 280, $height = 280, $color = '#1E293B')
    {
        $svg = sprintf(
            '<svg width="%d" height="%d" viewBox="0 0 %d %d" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%%" height="100%%" fill="%s"/>
                <text x="50%%" y="50%%" text-anchor="middle" dy=".3em" fill="white" font-family="Arial, sans-serif" font-size="16">
                    Hostel Image
                </text>
            </svg>',
            $width, $height, $width, $height, $color
        );

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get image dimensions
     */
    public function getImageSize($path)
    {
        try {
            $fullPath = storage_path('app/public/' . $path);
            
            if (!file_exists($fullPath)) {
                $fullPath = public_path($path);
                
                if (!file_exists($fullPath)) {
                    return ['width' => 0, 'height' => 0];
                }
            }

            $imageInfo = getimagesize($fullPath);
            if ($imageInfo) {
                return [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1]
                ];
            }

            return ['width' => 280, 'height' => 280];

        } catch (\Exception $e) {
            return ['width' => 280, 'height' => 280];
        }
    }
}