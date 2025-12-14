<?php
// app/Services/ModernImageOptimizer.php - COMPLETE FILE

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ModernImageOptimizer
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function optimizeForModernTheme($imagePath, $options = [])
    {
        $defaults = [
            'sizes' => [300, 600, 900],
            'quality' => 75,
            'format' => 'webp',
            'modern_effect' => true,
            'sharpness' => 1.1,
            'vibrance' => 1.05
        ];

        $options = array_merge($defaults, $options);

        try {
            if (!Storage::disk('public')->exists($imagePath)) {
                return $this->getModernDefaultImage();
            }

            $fullPath = Storage::disk('public')->path($imagePath);
            $image = $this->manager->read($fullPath);

            // Apply modern effects
            if ($options['modern_effect']) {
                // Enhance sharpness
                $image->sharp($options['sharpness']);

                // Increase vibrance slightly
                $image->brightness(5);
                $image->contrast(5);
            }

            $optimized = [
                'original' => Storage::url($imagePath),
                'sizes' => [],
                'webp' => [],
                'srcset' => '',
                'webp_srcset' => '',
                'modern_metadata' => [
                    'enhanced' => $options['modern_effect'],
                    'format' => $options['format'],
                    'theme' => 'modern'
                ]
            ];

            foreach ($options['sizes'] as $size) {
                $resized = clone $image;
                $resized->scale(width: $size);

                // Apply modern gradient border
                $this->addModernBorder($resized);

                // JPEG version
                $jpegFilename = $this->generateModernFilename($imagePath, $size, 'jpg');
                $jpegPath = "modern_optimized/{$jpegFilename}";
                $resized->toJpeg($options['quality'])->save(Storage::disk('public')->path($jpegPath));

                // WebP version
                $webpFilename = $this->generateModernFilename($imagePath, $size, 'webp');
                $webpPath = "modern_optimized/{$webpFilename}";
                $resized->toWebp($options['quality'])->save(Storage::disk('public')->path($webpPath));

                $optimized['sizes'][$size] = [
                    'width' => $size,
                    'jpeg' => Storage::url($jpegPath),
                    'webp' => Storage::url($webpPath)
                ];

                if ($optimized['srcset']) {
                    $optimized['srcset'] .= ', ';
                    $optimized['webp_srcset'] .= ', ';
                }

                $optimized['srcset'] .= Storage::url($jpegPath) . " {$size}w";
                $optimized['webp_srcset'] .= Storage::url($webpPath) . " {$size}w";
            }

            return $optimized;
        } catch (\Exception $e) {
            \Log::error('Modern image optimization failed: ' . $e->getMessage());
            return $this->getModernDefaultImage();
        }
    }

    protected function addModernBorder($image)
    {
        // Add subtle gradient border for modern look
        $width = $image->width();
        $height = $image->height();

        // Create gradient overlay (subtle)
        $image->rectangle(0, 0, $width - 1, $height - 1, function ($draw) {
            $draw->background('rgba(59, 130, 246, 0.05)');
            $draw->border(2, 'rgba(59, 130, 246, 0.2)');
        });
    }

    protected function generateModernFilename($originalPath, $size, $extension)
    {
        $filename = pathinfo($originalPath, PATHINFO_FILENAME);
        $hash = substr(md5($originalPath . $size . 'modern'), 0, 8);

        return "modern_{$filename}_{$size}w_{$hash}.{$extension}";
    }

    protected function getModernDefaultImage()
    {
        return [
            'original' => asset('images/modern-default.jpg'),
            'srcset' => asset('images/modern-default.jpg') . ' 300w',
            'webp_srcset' => asset('images/modern-default.webp') . ' 300w',
            'modern_metadata' => [
                'enhanced' => true,
                'fallback' => true
            ]
        ];
    }

    public function createModernPlaceholder($width, $height, $type = 'image')
    {
        $colors = [
            'primary' => ['#3b82f6', '#1d4ed8'],
            'gradient' => ['#3b82f6', '#7c3aed']
        ];

        $gradient = $colors['gradient'];

        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">
            <!-- Modern Background -->
            <defs>
                <linearGradient id="modernGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="' . $gradient[0] . '" stop-opacity="0.05"/>
                    <stop offset="100%" stop-color="' . $gradient[1] . '" stop-opacity="0.02"/>
                </linearGradient>
                <pattern id="grid' . $width . '" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="' . $gradient[0] . '" stroke-width="0.5" opacity="0.1"/>
                </pattern>
            </defs>
            
            <rect width="100%" height="100%" fill="url(#modernGrad)"/>
            <rect width="100%" height="100%" fill="url(#grid' . $width . ')"/>
            
            <!-- Modern Card -->
            <rect x="20" y="20" width="' . ($width - 40) . '" height="' . ($height - 40) . '" 
                  rx="12" fill="white" stroke="' . $gradient[0] . '" stroke-width="2" opacity="0.9"/>
            
            <!-- Loading Animation -->
            <rect x="' . ($width / 2 - 40) . '" y="' . ($height / 2 - 40) . '" width="80" height="80" 
                  rx="12" fill="' . $gradient[0] . '" opacity="0.1">
                <animate attributeName="opacity" values="0.1;0.2;0.1" dur="2s" repeatCount="indefinite"/>
            </rect>
            
            <!-- Loading Icon -->
            <g transform="translate(' . ($width / 2) . ', ' . ($height / 2) . ')">
                <circle r="30" fill="' . $gradient[0] . '" opacity="0.2"/>
                <text text-anchor="middle" dy="0.3em" fill="' . $gradient[0] . '" 
                      font-family="Inter" font-size="14" font-weight="600">
                    ' . ($type === 'video' ? '▶' : '📷') . '
                </text>
            </g>
            
            <!-- Subtle Shadow -->
            <rect x="20" y="20" width="' . ($width - 40) . '" height="' . ($height - 40) . '" 
                  rx="12" fill="none" stroke="' . $gradient[1] . '" stroke-width="1" opacity="0.3">
                <animate attributeName="opacity" values="0.3;0.5;0.3" dur="3s" repeatCount="indefinite"/>
            </rect>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
