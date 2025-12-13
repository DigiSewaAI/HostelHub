<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

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
            $width,
            $height,
            $width,
            $height,
            $color
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

    /**
     * Optimize image for Dark Theme with cyberpunk effects using GD
     */
    public function optimizeForDarkTheme($imagePath, $options = [])
    {
        $defaults = [
            'width' => 300,
            'height' => 300,
            'quality' => 80,
            'neon_effect' => true,
            'contrast' => -20,
            'brightness' => 20
        ];

        $options = array_merge($defaults, $options);

        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return $this->getDarkThemeDefaultImage();
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $image = $this->resizeImage($fullPath, $options['width'], $options['height']);

            if (!$image) {
                return $this->getDarkThemeDefaultImage();
            }

            // Apply cyberpunk effects using GD
            if ($options['neon_effect']) {
                // Apply contrast and brightness using GD filters
                imagefilter($image, IMG_FILTER_CONTRAST, $options['contrast']);
                imagefilter($image, IMG_FILTER_BRIGHTNESS, $options['brightness']);

                // Add cyan tint (cyberpunk style) - colorize with cyan (0, 50, 50)
                imagefilter($image, IMG_FILTER_COLORIZE, 0, 50, 50, 0);
            }

            // Generate unique filename
            $filename = pathinfo($imagePath, PATHINFO_FILENAME);
            $hash = substr(md5($imagePath . 'dark_cyber'), 0, 8);
            $optimizedPath = "dark_optimized/dark_{$filename}_{$hash}.jpg";

            // Ensure directory exists
            $directory = dirname(Storage::disk('public')->path($optimizedPath));
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save optimized image
            $fullOptimizedPath = Storage::disk('public')->path($optimizedPath);
            imagejpeg($image, $fullOptimizedPath, $options['quality']);

            // Also create base64 version for immediate display
            ob_start();
            imagejpeg($image, null, $options['quality']);
            $imageData = ob_get_clean();
            imagedestroy($image);

            $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);

            return [
                'original' => Storage::url($imagePath),
                'optimized' => Storage::url($optimizedPath),
                'base64' => $base64Image,
                'width' => $options['width'],
                'height' => $options['height'],
                'cyber_metadata' => [
                    'neon_enhanced' => $options['neon_effect'],
                    'theme' => 'cyberpunk_dark',
                    'contrast' => $options['contrast'],
                    'brightness' => $options['brightness']
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Dark theme image optimization failed: ' . $e->getMessage());
            return $this->getDarkThemeDefaultImage();
        }
    }

    /**
     * Generate filename for dark theme optimized images
     */
    protected function generateDarkThemeFilename($originalPath, $size, $extension)
    {
        $filename = pathinfo($originalPath, PATHINFO_FILENAME);
        $hash = substr(md5($originalPath . $size . 'dark_cyber'), 0, 8);

        return "dark_{$filename}_{$size}w_{$hash}.{$extension}";
    }

    /**
     * Get default image for dark theme
     */
    protected function getDarkThemeDefaultImage()
    {
        // Create a default cyberpunk-style image programmatically
        $width = 300;
        $height = 300;

        $image = imagecreatetruecolor($width, $height);

        // Dark background
        $darkBg = imagecolorallocate($image, 10, 10, 10);
        imagefill($image, 0, 0, $darkBg);

        // Cyber grid pattern
        $gridColor = imagecolorallocate($image, 0, 212, 255); // Neon cyan
        for ($i = 0; $i < $width; $i += 20) {
            imageline($image, $i, 0, $i, $height, $gridColor);
        }
        for ($i = 0; $i < $height; $i += 20) {
            imageline($image, 0, $i, $width, $i, $gridColor);
        }

        // Cyber text
        $textColor = imagecolorallocate($image, 255, 0, 255); // Neon pink
        $text = "HOSTELHUB";
        $font = 5; // Built-in GD font
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        imagestring($image, $font, $x, $y, $text, $textColor);

        // Output as base64
        ob_start();
        imagejpeg($image, null, 80);
        $imageData = ob_get_clean();
        imagedestroy($image);

        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);

        return [
            'original' => asset('images/cyber-default.jpg'),
            'optimized' => $base64Image,
            'base64' => $base64Image,
            'width' => $width,
            'height' => $height,
            'cyber_metadata' => [
                'neon_enhanced' => true,
                'fallback' => true,
                'theme' => 'cyberpunk_dark',
                'auto_generated' => true
            ]
        ];
    }

    /**
     * Create cyberpunk placeholder for dark theme
     */
    public function createCyberPlaceholder($width, $height, $type = 'image')
    {
        $colors = [
            'cyan' => ['#00D4FF', '#0088AA'],
            'pink' => ['#FF00FF', '#AA00AA'],
            'green' => ['#00FF88', '#00AA55']
        ];

        $colorSet = $type === 'video' ? $colors['pink'] : $colors['cyan'];

        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
            <rect width="100%" height="100%" fill="#0A0A0A"/>
            
            <defs>
                <pattern id="cybergrid" width="20" height="20" patternUnits="userSpaceOnUse">
                    <path d="M 20 0 L 0 0 0 20" fill="none" stroke="' . $colorSet[1] . '" stroke-width="0.5" opacity="0.3"/>
                </pattern>
            </defs>
            
            <rect width="100%" height="100%" fill="url(#cybergrid)"/>
            
            <rect x="10" y="10" width="' . ($width - 20) . '" height="' . ($height - 20) . '" 
                  fill="none" stroke="' . $colorSet[0] . '" stroke-width="2" opacity="0.7"/>
            
            <line x1="10" y1="10" x2="' . ($width - 10) . '" y2="' . ($height - 10) . '" 
                  stroke="' . $colorSet[0] . '" stroke-width="1" opacity="0.4"/>
            <line x1="' . ($width - 10) . '" y1="10" x2="10" y2="' . ($height - 10) . '" 
                  stroke="' . $colorSet[0] . '" stroke-width="1" opacity="0.4"/>
            
            <text x="50%" y="50%" text-anchor="middle" dy="0.3em" 
                  fill="' . $colorSet[0] . '" font-family="Arial" font-size="14" font-weight="bold">
                ' . ($type === 'video' ? '▶' : '⚡') . '
            </text>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
