<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hostel;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $hostels = Hostel::all();

        foreach ($hostels as $hostel) {
            Room::create([
                'hostel_id' => $hostel->id,
                'room_number' => '101',
                'type' => 'single',
                'price' => 5000,
                'capacity' => 1,
                'status' => 'available',
                'org_id' => 1, // यो कलम तपाइँको टेबलमा छ
                'description' => 'एकल कोठा' // यो कलम पनि तपाइँको टेबलमा छ
            ]);

            Room::create([
                'hostel_id' => $hostel->id,
                'room_number' => '102',
                'type' => 'double',
                'price' => 8000,
                'capacity' => 2,
                'status' => 'available',
                'org_id' => 1, // यो कलम तपाइँको टेबलमा छ
                'description' => 'दोहोरो कोठा' // यो कलम पनि तपाइँको टेबलमा छ
            ]);
        }
    }
}
