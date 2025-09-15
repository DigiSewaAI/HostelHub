<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        Organization::create([
            'name' => 'Hostel A',
            'slug' => 'hostel-a',
            'is_ready' => true,
            'settings' => [
                'city' => 'Kathmandu',
                'address' => 'Kathmandu, Nepal',
                'contact_phone' => '+977-1-1234567',
                'monthly_fee' => 5000,
                'deposit' => 10000,
                'meal_plan' => true,
                'meal_price' => 2000,
            ]
        ]);

        Organization::create([
            'name' => 'Hostel B',
            'slug' => 'hostel-b',
            'is_ready' => true,
            'settings' => [
                'city' => 'Pokhara',
                'address' => 'Pokhara, Nepal',
                'contact_phone' => '+977-1-9876543',
                'monthly_fee' => 4500,
                'deposit' => 9000,
                'meal_plan' => true,
                'meal_price' => 1800,
            ]
        ]);
    }
}
