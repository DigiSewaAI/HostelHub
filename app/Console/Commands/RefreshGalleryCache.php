<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Hostel;
use App\Models\HostelImage;

class RefreshGalleryCache extends Command
{
    protected $signature = 'gallery:refresh';
    protected $description = 'Refresh the homepage gallery cache';

    public function handle()
    {
        $this->info('Refreshing gallery cache...');

        // Clear all gallery cache keys
        Cache::forget('dynamic_gallery_items_' . now()->format('Y-m-d_H'));
        Cache::forget('dynamic_gallery_items_' . now()->subHour()->format('Y-m-d_H'));

        // Get current gallery items to warm cache
        $controller = new \App\Http\Controllers\Frontend\PublicController(new \App\Services\ImageService());
        $galleryItems = $controller->getDynamicGalleryItems();

        $this->info('Gallery cache refreshed successfully!');
        $this->info('Total images in cache: ' . $galleryItems->count());

        // Log statistics
        $featuredCount = $galleryItems->where('is_featured_hostel', true)->count();
        $regularCount = $galleryItems->where('is_featured_hostel', false)->count();

        $this->info("Featured hostel images: {$featuredCount}");
        $this->info("Regular hostel images: {$regularCount}");

        return 0;
    }
}
