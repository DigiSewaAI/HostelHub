<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Hostel;
use App\Models\HostelImage;

class RefreshGalleryCache extends Command
{
    protected $signature = 'gallery:refresh';
    protected $description = 'Refresh the homepage dynamic gallery cache';

    public function handle()
    {
        $this->info('Refreshing dynamic gallery cache...');

        // Get current hour key
        $currentHour = now()->format('Y-m-d_H');

        // Call the method from PublicController to regenerate cache
        $controller = new \App\Http\Controllers\Frontend\PublicController(new \App\Services\ImageService());
        $galleryItems = $controller->getDynamicGalleryItems();

        $this->info('✅ Dynamic gallery cache refreshed successfully!');
        $this->info("Cache key: dynamic_gallery_items_{$currentHour}");
        $this->info('Total images cached: ' . $galleryItems->count());

        // Statistics
        $featuredCount = $galleryItems->where('is_featured_hostel', true)->count();
        $regularCount = $galleryItems->where('is_featured_hostel', false)->count();

        $this->info("Featured hostel images: {$featuredCount}");
        $this->info("Regular hostel images: {$regularCount}");

        return 0;
    }
}
