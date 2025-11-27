<?php
// app/Console/Commands/SyncRoomsOccupancy.php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncRoomsOccupancy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hostel:sync-rooms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync room occupancy, available beds, and status based on active students';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting room occupancy sync...');
        Log::info('Starting room occupancy sync command');

        $totalRooms = Room::count();
        $processed = 0;
        $updated = 0;
        $errors = 0;

        // Process rooms in chunks to avoid memory issues
        Room::chunk(100, function ($rooms) use (&$processed, &$updated, &$errors) {
            foreach ($rooms as $room) {
                try {
                    $this->syncRoom($room) ? $updated++ : $processed++;
                    $processed++;
                } catch (\Exception $e) {
                    $errors++;
                    $this->error("Error syncing room {$room->id}: " . $e->getMessage());
                    Log::error("Error syncing room {$room->id}: " . $e->getMessage());
                }
            }
        });

        $this->info("Room occupancy sync completed!");
        $this->info("Processed: {$processed} rooms");
        $this->info("Updated: {$updated} rooms");
        $this->info("Errors: {$errors} rooms");

        Log::info('Room occupancy sync completed', [
            'processed' => $processed,
            'updated' => $updated,
            'errors' => $errors
        ]);

        return Command::SUCCESS;
    }

    /**
     * Sync individual room occupancy
     */
    private function syncRoom(Room $room): bool
    {
        // Count active students in this room
        $activeStudentsCount = Student::where('room_id', $room->id)
            ->whereIn('status', ['active', 'approved'])
            ->count();

        // Calculate available beds
        $availableBeds = max(0, $room->capacity - $activeStudentsCount);

        // Determine status based on occupancy
        if ($activeStudentsCount == 0) {
            $status = 'available';
        } elseif ($activeStudentsCount == $room->capacity) {
            $status = 'occupied';
        } else {
            $status = 'partially_available';
        }

        // Check if update is needed
        if (
            $room->current_occupancy == $activeStudentsCount &&
            $room->available_beds == $availableBeds &&
            $room->status == $status
        ) {
            return false; // No changes needed
        }

        // Update room quietly
        $room->updateQuietly([
            'current_occupancy' => $activeStudentsCount,
            'available_beds' => $availableBeds,
            'status' => $status
        ]);

        $this->info("Room {$room->room_number}: {$activeStudentsCount}/{$room->capacity} students, {$availableBeds} beds available, status: {$status}");

        return true;
    }
}
