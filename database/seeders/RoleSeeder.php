<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use firstOrCreate to prevent duplicate entries
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'hostel_manager']);
        Role::firstOrCreate(['name' => 'student']);
    }
}
