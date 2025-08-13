<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // एडमिन यूजर सिर्जना गर्ने
        $adminRole = Role::where('name', 'admin')->first();
        User::firstOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'role_id' => $adminRole->id,
                'phone' => '9761762036',
                'address' => 'काठमाडौं, नेपाल',
                'payment_verified' => true
            ]
        );

        // होस्टेल म्यानेजर यूजर सिर्जना गर्ने
        $managerRole = Role::where('name', 'hostel_manager')->first();
        User::firstOrCreate(
            ['email' => 'regmiashish629@gmail.com'],
            [
                'name' => 'Ashish Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'role_id' => $managerRole->id,
                'phone' => '9761762036',
                'address' => 'पोखरा, नेपाल',
                'payment_verified' => true
            ]
        );
    }
}
