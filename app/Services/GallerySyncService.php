<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Gallery;
use App\Models\Hostel;
use Illuminate\Support\Facades\Log;

class GallerySyncService
{
    /**
     * Sync all rooms to gallery
     */
    public function syncAllRooms(): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'total' => 0
        ];

        $rooms = Room::with('hostel')->whereNotNull('image')->get();

        foreach ($rooms as $room) {
            try {
                $this->syncRoomToGallery($room);
                $results['success']++;
            } catch (\Exception $e) {
                Log::error("Failed to sync room {$room->id} to gallery: " . $e->getMessage());
                $results['failed']++;
            }
            $results['total']++;
        }

        return $results;
    }

    /**
     * Sync single room to gallery
     */
    public function syncRoomToGallery(Room $room): bool
    {
        if (!$room->image) {
            return false;
        }

        // Delete existing entries
        Gallery::where('room_id', $room->id)->delete();

        // Create gallery entry
        return (bool) Gallery::create([
            'title' => "Room {$room->room_number} - {$room->type}",
            'description' => $room->description ?? "{$room->type} room at {$room->hostel->name}",
            'category' => $room->gallery_category,
            'media_type' => 'photo',
            'file_path' => $room->image,
            'thumbnail' => $room->image,
            'is_featured' => false,
            'is_active' => true,
            'user_id' => $room->hostel->owner_id ?? 1,
            'hostel_id' => $room->hostel_id,
            'room_id' => $room->id,
            'hostel_name' => $room->hostel->name
        ]);
    }

    /**
     * Update hostel names in galleries
     */
    public function updateHostelNames(): int
    {
        $updated = 0;
        $hostels = Hostel::all();

        foreach ($hostels as $hostel) {
            $count = Gallery::where('hostel_id', $hostel->id)
                ->update(['hostel_name' => $hostel->name]);
            $updated += $count;
        }

        return $updated;
    }
}
