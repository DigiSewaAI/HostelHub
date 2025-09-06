<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Hostel;

class HostelSeeder extends Seeder
{
    public function run()
    {
        // Get the first user as owner
        $owner = User::first();

        // If no user exists, create one first
        if (!$owner) {
            $owner = User::create([
                'name' => 'Hostel Owner',
                'email' => 'owner@example.com',
                'password' => bcrypt('password'),
                'role_id' => 2, // Assuming 2 is for hostel manager
                'phone' => '9761762036',
                'address' => 'Kathmandu, Nepal',
                'payment_verified' => true,
            ]);

            // Assign role if using Spatie Permission
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $owner->assignRole('hostel_manager');
            }
        }

        Hostel::create([
            'name' => 'पहिलो होस्टेल',
            'slug' => Str::slug('पहिलो होस्टेल'),
            'address' => 'काठमाडौं, नेपाल',
            'city' => 'Kathmandu',
            'contact_person' => 'Parashar Regmi',
            'contact_phone' => '9761762036',
            'contact_email' => 'parasharregmi@gmail.com',
            'description' => 'यो एउटा उत्कृष्ट होस्टेल हो',
            'total_rooms' => 10,
            'available_rooms' => 5,
            'facilities' => 'WiFi, Parking, Food',
            'manager_id' => $owner->id,
            'org_id' => 1, // If using organizations
            'owner_id' => $owner->id,
            'status' => 'active'
        ]);

        Hostel::create([
            'name' => 'दोस्रो होस्टेल',
            'slug' => Str::slug('दोस्रो होस्टेल'),
            'address' => 'पोखरा, नेपाल',
            'city' => 'Pokhara',
            'contact_person' => 'Hostel Manager',
            'contact_phone' => '9800000000',
            'contact_email' => 'manager@example.com',
            'description' => 'यो अर्को राम्रो होस्टेल हो',
            'total_rooms' => 8,
            'available_rooms' => 3,
            'facilities' => 'WiFi, Parking, Laundry',
            'manager_id' => $owner->id,
            'org_id' => 1, // If using organizations
            'owner_id' => $owner->id,
            'status' => 'active'
        ]);
    }
}
