<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run custom seeders
        $this->call([
            CourseSeeder::class,
            HostelHubSeeder::class,
            ReviewSeeder::class,
        ]);

        // Create Admin User
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@hostelhub.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'phone' => '9800000000',
            'address' => 'काठमाडौं, नेपाल',
        ]);

        // Create Test User
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'phone' => '9812345678',
            'address' => 'पोखरा, नेपाल',
        ]);
    }
}
