<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'hostel_manager')->first();
        $studentRole = Role::where('name', 'student')->first();

        $organizations = Organization::all();

        $admin = User::firstOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'phone' => '9761762036',
                'address' => 'काठमाडौं, नेपाल',
                'payment_verified' => true,
                'organization_id' => $organizations->first()->id
            ]
        );
        $admin->assignRole($adminRole);
        $admin->organizations()->attach($organizations->pluck('id'), ['role' => 'admin']);

        $manager = User::firstOrCreate(
            ['email' => 'regmiashish629@gmail.com'],
            [
                'name' => 'Ashish Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'phone' => '9761762036',
                'address' => 'पोखरा, नेपाल',
                'payment_verified' => true,
                'organization_id' => $organizations->first()->id
            ]
        );
        $manager->assignRole($managerRole);
        $manager->organizations()->attach($organizations->pluck('id'), ['role' => 'manager']);

        $student = User::firstOrCreate(
            ['email' => 'shresthaxok@gmail.com'],
            [
                'name' => 'Ashok Shrestha',
                'password' => Hash::make('password123'),
                'phone' => '9851134338',
                'address' => 'पोखरा, नेपाल',
                'payment_verified' => false,
                'organization_id' => $organizations->first()->id
            ]
        );
        $student->assignRole($studentRole);
        $student->organizations()->attach($organizations->first()->id, ['role' => 'student']);
    }
}
