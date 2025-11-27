<?php

namespace Tests\Feature;

use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $hostel;
    protected $room;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hostel = Hostel::factory()->create([
            'name' => 'Test Hostel',
            'slug' => 'test-hostel',
            'status' => 'active',
            'is_published' => true
        ]);

        $this->room = Room::factory()->create([
            'hostel_id' => $this->hostel->id,
            'room_number' => '101',
            'capacity' => 2,
            'current_occupancy' => 0,
            'available_beds' => 2,
            'status' => 'available',
            'price' => 5000
        ]);
    }

    /** @test */
    public function gallery_booking_loads_with_correct_variables()
    {
        $response = $this->get("/book/{$this->hostel->slug}?room_id={$this->room->id}");

        $response->assertStatus(200);
        $response->assertViewHas('hostel');
        $response->assertViewHas('availableRooms');
        $response->assertViewHas('selectedRoom');
        $response->assertViewHas('checkIn');
        $response->assertViewHas('checkOut');
        $response->assertViewHas('datesLocked');

        // Check that selected room matches requested room
        $viewData = $response->getOriginalContent()->getData();
        $this->assertEquals($this->room->id, $viewData['selectedRoom']->id);
    }

    /** @test */
    public function gallery_booking_redirects_when_room_not_available()
    {
        // Make room unavailable
        $this->room->update([
            'current_occupancy' => 2,
            'available_beds' => 0,
            'status' => 'occupied'
        ]);

        $response = $this->get("/book/{$this->hostel->slug}?room_id={$this->room->id}");

        $response->assertRedirect(route('hostel.book.all.rooms', ['slug' => $this->hostel->slug]));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function gallery_booking_redirects_when_no_room_id()
    {
        $response = $this->get("/book/{$this->hostel->slug}");

        $response->assertRedirect(route('hostel.book.all.rooms', ['slug' => $this->hostel->slug]));
    }
}
