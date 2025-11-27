<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Room;
use App\Models\Hostel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentObserverTest extends TestCase
{
    use RefreshDatabase;

    protected $hostel;
    protected $room;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hostel = Hostel::factory()->create([
            'name' => 'Test Hostel',
            'status' => 'active',
            'is_published' => true
        ]);

        $this->room = Room::factory()->create([
            'hostel_id' => $this->hostel->id,
            'room_number' => '101',
            'capacity' => 2,
            'current_occupancy' => 0,
            'available_beds' => 2,
            'status' => 'available'
        ]);
    }

    /** @test */
    public function creating_student_updates_room_occupancy()
    {
        // Create a student in the room
        $student = Student::factory()->create([
            'room_id' => $this->room->id,
            'hostel_id' => $this->hostel->id,
            'status' => 'active'
        ]);

        // Refresh room from database
        $this->room->refresh();

        // Assert room counters are updated
        $this->assertEquals(1, $this->room->current_occupancy);
        $this->assertEquals(1, $this->room->available_beds);
        $this->assertEquals('partially_available', $this->room->status);
    }

    /** @test */
    public function updating_student_room_updates_both_rooms()
    {
        // Create another room
        $newRoom = Room::factory()->create([
            'hostel_id' => $this->hostel->id,
            'room_number' => '102',
            'capacity' => 2,
            'current_occupancy' => 0,
            'available_beds' => 2,
            'status' => 'available'
        ]);

        // Create student in first room
        $student = Student::factory()->create([
            'room_id' => $this->room->id,
            'hostel_id' => $this->hostel->id,
            'status' => 'active'
        ]);

        // Move student to new room
        $student->update(['room_id' => $newRoom->id]);

        // Refresh both rooms
        $this->room->refresh();
        $newRoom->refresh();

        // Assert old room is empty
        $this->assertEquals(0, $this->room->current_occupancy);
        $this->assertEquals(2, $this->room->available_beds);
        $this->assertEquals('available', $this->room->status);

        // Assert new room has student
        $this->assertEquals(1, $newRoom->current_occupancy);
        $this->assertEquals(1, $newRoom->available_beds);
        $this->assertEquals('partially_available', $newRoom->status);
    }

    /** @test */
    public function deleting_student_updates_room_occupancy()
    {
        // Create student in room
        $student = Student::factory()->create([
            'room_id' => $this->room->id,
            'hostel_id' => $this->hostel->id,
            'status' => 'active'
        ]);

        // Delete student
        $student->delete();

        // Refresh room
        $this->room->refresh();

        // Assert room is empty
        $this->assertEquals(0, $this->room->current_occupancy);
        $this->assertEquals(2, $this->room->available_beds);
        $this->assertEquals('available', $this->room->status);
    }
}
