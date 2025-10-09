<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hostel;

class UpdateHostelRoomCounts extends Command
{
    protected $signature = 'hostels:update-counts';
    protected $description = 'Update hostel room counts from relationships';

    public function handle()
    {
        $hostels = Hostel::withCount(['rooms', 'rooms as available_rooms_count' => function ($query) {
            $query->where('status', 'available');
        }])->get();

        $updatedCount = 0;

        foreach ($hostels as $hostel) {
            $oldTotal = $hostel->total_rooms;
            $oldAvailable = $hostel->available_rooms;

            $hostel->update([
                'total_rooms' => $hostel->rooms_count,
                'available_rooms' => $hostel->available_rooms_count
            ]);

            if ($oldTotal != $hostel->rooms_count || $oldAvailable != $hostel->available_rooms_count) {
                $this->info("Updated {$hostel->name}: {$oldTotal}->{$hostel->rooms_count} total, {$oldAvailable}->{$hostel->available_rooms_count} available");
                $updatedCount++;
            }
        }

        $this->info("Successfully updated {$updatedCount} hostels");
        return 0;
    }
}
