<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Illuminate\Support\Facades\Log;

class RoomOccupancySyncSeeder extends Seeder
{
    public function run()
    {
        Log::info('Starting room occupancy sync for all hostels...');

        $rooms = Room::all();
        $updatedCount = 0;

        foreach ($rooms as $room) {
            try {
                $activeStudentsCount = $room->students()
                    ->whereIn('status', ['active', 'approved'])
                    ->count();

                $availableBeds = max(0, $room->capacity - $activeStudentsCount);

                // Determine correct status
                if ($room->status !== 'maintenance') {
                    if ($activeStudentsCount == 0) {
                        $status = 'available';
                    } elseif ($activeStudentsCount >= $room->capacity) {
                        $status = 'occupied';
                    } else {
                        $status = 'partially_available';
                    }
                } else {
                    $status = 'maintenance';
                }

                $room->update([
                    'current_occupancy' => $activeStudentsCount,
                    'available_beds' => $availableBeds,
                    'status' => $status
                ]);

                $updatedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to sync room {$room->id}: " . $e->getMessage());
            }
        }

        Log::info("Room occupancy sync completed. Updated: {$updatedCount} rooms");
    }
}
