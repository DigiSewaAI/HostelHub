<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use App\Models\Gallery;

class SyncRoomImagesToGallery extends Command
{
    protected $signature = 'gallery:sync-room-images';
    protected $description = 'Sync all existing room images to gallery system';

    public function handle()
    {
        $this->info('Starting room images sync to gallery...');

        $roomsWithImages = Room::whereNotNull('image')->get();
        $total = $roomsWithImages->count();

        $this->info("Found {$total} rooms with images to sync.");

        $bar = $this->output->createProgressBar($total);

        foreach ($roomsWithImages as $room) {
            // Delete existing gallery entries for this room
            Gallery::where('room_id', $room->id)->delete();

            // Create new gallery entry
            if ($room->image) {
                $room->syncImageToGallery();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nSync completed successfully!");

        $galleryCount = Gallery::whereNotNull('room_id')->count();
        $this->info("Total room images in gallery: {$galleryCount}");
    }
}
