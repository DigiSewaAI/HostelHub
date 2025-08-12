<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always run RoleSeeder FIRST (critical dependency)
        $this->call(RoleSeeder::class);

        // Create Admin User with proper role relationship
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hostelhub.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'phone' => '9800000000',
            'address' => 'काठमाडौं, नेपाल',
            'role_id' => $adminRole->id, // ✅ CORRECTED: Use role_id instead of role string
        ]);

        // Create Test User (student role)
        $studentRole = Role::where('name', 'student')->firstOrFail();

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'phone' => '9812345678',
            'address' => 'पोखरा, नेपाल',
            'role_id' => $studentRole->id, // ✅ CORRECTED
        ]);

        // Run other seeders AFTER user creation
        $this->call([
            CourseSeeder::class,
            HostelHubSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
