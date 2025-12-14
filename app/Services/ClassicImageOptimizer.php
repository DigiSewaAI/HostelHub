<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ClassicImageOptimizer
{
    /**
     * Optimize image for classic theme with vintage effects
     */
    public function optimizeForClassicTheme($imagePath, $options = [])
    {
        $defaults = [
            'width' => 300,
            'height' => 300,
            'quality' => 80,
            'vintage_effect' => true,
            'sepia_tone' => 0.1,
            'warm_tone' => true
        ];

        $options = array_merge($defaults, $options);

        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return $this->getClassicDefaultImage();
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $image = $this->resizeImage($fullPath, $options['width'], $options['height']);

            if (!$image) {
                return $this->getClassicDefaultImage();
            }

            // Apply vintage effects using GD
            if ($options['vintage_effect']) {
                // Add warm tone
                if ($options['warm_tone']) {
                    imagefilter($image, IMG_FILTER_COLORIZE, 10, 5, -5);
                }
                
                // Add slight sepia
                if ($options['sepia_tone'] > 0) {
                    $sepiaValue = $options['sepia_tone'] * 100;
                    imagefilter($image, IMG_FILTER_COLORIZE, $sepiaValue, $sepiaValue/2, -$sepiaValue/4);
                }
                
                // Slight contrast enhancement
                imagefilter($image, IMG_FILTER_CONTRAST, -10);
            }

            // Generate unique filename
            $filename = pathinfo($imagePath, PATHINFO_FILENAME);
            $hash = substr(md5($imagePath . 'classic_vintage'), 0, 8);
            $optimizedPath = "classic_optimized/classic_{$filename}_{$hash}.jpg";

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

            // Create srcset for responsive images
            $srcset = Storage::url($optimizedPath) . " 1x, " . 
                     Storage::url($optimizedPath) . " 2x";

            return [
                'original' => Storage::url($imagePath),
                'optimized' => Storage::url($optimizedPath),
                'base64' => $base64Image,
                'srcset' => $srcset,
                'webp_srcset' => '', // Can be added if WebP conversion is implemented
                'width' => $options['width'],
                'height' => $options['height'],
                'classic_metadata' => [
                    'vintage_enhanced' => $options['vintage_effect'],
                    'warm_tone' => $options['warm_tone'],
                    'sepia_tone' => $options['sepia_tone'],
                    'theme' => 'classic_vintage'
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Classic theme image optimization failed: ' . $e->getMessage());
            return $this->getClassicDefaultImage();
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
     * Get default image for classic theme
     */
    protected function getClassicDefaultImage()
    {
        // Create a default classic-style image programmatically
        $width = 300;
        $height = 300;

        $image = imagecreatetruecolor($width, $height);

        // Classic background color
        $bgColor = imagecolorallocate($image, 248, 244, 233); // Light beige
        imagefill($image, 0, 0, $bgColor);

        // Add classic border
        $borderColor = imagecolorallocate($image, 139, 0, 0); // Deep red
        imagerectangle($image, 5, 5, $width-5, $height-5, $borderColor);
        imagerectangle($image, 7, 7, $width-7, $height-7, $borderColor);

        // Gold accent
        $goldColor = imagecolorallocate($image, 212, 175, 55);
        imagefilledrectangle($image, 10, 10, $width-10, $height-10, $goldColor);

        // Classic text
        $textColor = imagecolorallocate($image, 101, 67, 33); // Dark brown
        $text = "CLASSIC HOSTEL";
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
            'original' => asset('images/classic-default.jpg'),
            'optimized' => $base64Image,
            'base64' => $base64Image,
            'srcset' => asset('images/classic-default.jpg') . ' 1x, ' . asset('images/classic-default.jpg') . ' 2x',
            'width' => $width,
            'height' => $height,
            'classic_metadata' => [
                'vintage_enhanced' => true,
                'fallback' => true,
                'theme' => 'classic_vintage',
                'auto_generated' => true
            ]
        ];
    }

    /**
     * Create classic placeholder with vintage style
     */
    public function createClassicPlaceholder($width, $height, $type = 'image')
    {
        $colors = [
            'primary' => ['#8B4513', '#654321'],
            'secondary' => ['#D4AF37', '#B8860B'],
            'accent' => ['#8B0000', '#660000']
        ];

        $colorSet = $type === 'video' ? $colors['accent'] : $colors['primary'];

        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
            <!-- Vintage Background -->
            <rect width="100%" height="100%" fill="#F8F4E9"/>
            
            <!-- Pattern Overlay -->
            <defs>
                <pattern id="vintagePattern' . $width . '" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="20" cy="20" r="1" fill="' . $colorSet[0] . '" opacity="0.1"/>
                    <path d="M20,0 L20,40 M0,20 L40,20" fill="none" stroke="' . $colorSet[1] . '" stroke-width="0.5" opacity="0.05"/>
                </pattern>
            </defs>
            
            <rect width="100%" height="100%" fill="url(#vintagePattern' . $width . ')"/>
            
            <!-- Main Border -->
            <rect x="10" y="10" width="' . ($width - 20) . '" height="' . ($height - 20) . '" 
                  fill="#FFF8E1" stroke="' . $colorSet[0] . '" stroke-width="3" opacity="0.8"/>
            
            <!-- Inner Border -->
            <rect x="20" y="20" width="' . ($width - 40) . '" height="' . ($height - 40) . '" 
                  fill="none" stroke="' . $colorSet[1] . '" stroke-width="2" opacity="0.5"/>
            
            <!-- Ornamental Corners -->
            <path d="M10,10 L30,10 L10,30 Z" fill="' . $colorSet[0] . '" opacity="0.3"/>
            <path d="M' . ($width - 10) . ',10 L' . ($width - 30) . ',10 L' . ($width - 10) . ',30 Z" fill="' . $colorSet[0] . '" opacity="0.3"/>
            <path d="M10,' . ($height - 10) . ' L30,' . ($height - 10) . ' L10,' . ($height - 30) . ' Z" fill="' . $colorSet[0] . '" opacity="0.3"/>
            <path d="M' . ($width - 10) . ',' . ($height - 10) . ' L' . ($width - 30) . ',' . ($height - 10) . ' L' . ($width - 10) . ',' . ($height - 30) . ' Z" fill="' . $colorSet[0] . '" opacity="0.3"/>
            
            <!-- Center Content -->
            <g transform="translate(' . ($width / 2) . ', ' . ($height / 2) . ')">
                <circle r="40" fill="' . $colorSet[0] . '" opacity="0.1"/>
                <text text-anchor="middle" dy="0.3em" fill="' . $colorSet[0] . '" 
                      font-family="Georgia" font-size="16" font-weight="bold">
                    ' . ($type === 'video' ? '▶' : '🏛️') . '
                </text>
                <text text-anchor="middle" dy="2.5em" fill="' . $colorSet[1] . '" 
                      font-family="Georgia" font-size="12" opacity="0.8">
                    ' . ($type === 'video' ? 'Classic Video' : 'Classic Image') . '
                </text>
            </g>
            
            <!-- Subtle Shadow -->
            <rect x="10" y="10" width="' . ($width - 20) . '" height="' . ($height - 20) . '" 
                  fill="none" stroke="' . $colorSet[0] . '" stroke-width="1" opacity="0.2">
                <animate attributeName="opacity" values="0.2;0.4;0.2" dur="3s" repeatCount="indefinite"/>
            </rect>
        </svg>';

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

            return ['width' => 300, 'height' => 300];
        } catch (\Exception $e) {
            return ['width' => 300, 'height' => 300];
        }
    }
}