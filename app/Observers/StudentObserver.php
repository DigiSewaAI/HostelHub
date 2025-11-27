<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\Room;
use Illuminate\Support\Facades\Log;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        Log::info('StudentObserver: Student created', [
            'student_id' => $student->id,
            'room_id' => $student->room_id,
            'hostel_id' => $student->hostel_id
        ]);

        if ($student->room_id) {
            $this->updateRoomCounters($student->room_id);
        }
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        Log::info('StudentObserver: Student updated', [
            'student_id' => $student->id,
            'room_id' => $student->room_id,
            'original_room_id' => $student->getOriginal('room_id')
        ]);

        // If room_id changed, update both old and new room
        if ($student->isDirty('room_id')) {
            $originalRoomId = $student->getOriginal('room_id');
            $newRoomId = $student->room_id;

            if ($originalRoomId) {
                $this->updateRoomCounters($originalRoomId);
            }
            if ($newRoomId) {
                $this->updateRoomCounters($newRoomId);
            }
        } elseif ($student->isDirty('status')) {
            // If status changed, update the room counters
            if ($student->room_id) {
                $this->updateRoomCounters($student->room_id);
            }
        }
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        Log::info('StudentObserver: Student deleted', [
            'student_id' => $student->id,
            'room_id' => $student->room_id
        ]);

        if ($student->room_id) {
            $this->updateRoomCounters($student->room_id);
        }
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        if ($student->room_id) {
            $this->updateRoomCounters($student->room_id);
        }
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        if ($student->room_id) {
            $this->updateRoomCounters($student->room_id);
        }
    }

    /**
     * Update room counters based on active students
     */
    private function updateRoomCounters(int $roomId): void
    {
        try {
            $room = Room::find($roomId);
            if (!$room) {
                Log::warning('StudentObserver: Room not found', ['room_id' => $roomId]);
                return;
            }

            // Count active students in this room
            $activeStudentsCount = Student::where('room_id', $roomId)
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

            // Update room quietly to avoid recursion
            $room->updateQuietly([
                'current_occupancy' => $activeStudentsCount,
                'available_beds' => $availableBeds,
                'status' => $status
            ]);

            Log::info('StudentObserver: Room counters updated', [
                'room_id' => $roomId,
                'room_number' => $room->room_number,
                'active_students' => $activeStudentsCount,
                'capacity' => $room->capacity,
                'available_beds' => $availableBeds,
                'new_status' => $status
            ]);

        } catch (\Exception $e) {
            Log::error('StudentObserver: Failed to update room counters', [
                'room_id' => $roomId,
                'error' => $e->getMessage()
            ]);
        }
    }
}