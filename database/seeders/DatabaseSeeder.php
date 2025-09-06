<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear permission cache
        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::disableForeignKeyConstraints();

        // Truncate tables safely
        $tables = [
            'model_has_roles',
            'role_has_permissions',
            'model_has_permissions',
            'roles',
            'permissions',
            'hostels', // Add hostels table to truncate
            'students', // Add students table to truncate
            'colleges', // Add colleges table to truncate
            'courses', // Add courses table to truncate
            'rooms' // Add rooms table to truncate
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create permissions
        $models = ['payments', 'students', 'hostels', 'rooms', 'galleries', 'contacts', 'reviews', 'meals', 'organizations'];
        $actions = ['access', 'create', 'edit', 'delete', 'export', 'report'];

        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissionName = $model . '_' . $action;

                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $hostelManagerRole = Role::firstOrCreate(['name' => 'hostel_manager', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // Assign permissions to roles
        $adminRole->syncPermissions(Permission::all());

        $hostelManagerPermissions = [];
        foreach (['payments', 'students', 'hostels', 'rooms', 'galleries', 'contacts', 'reviews', 'meals'] as $model) {
            foreach (['access', 'create', 'edit', 'delete', 'export', 'report'] as $action) {
                $hostelManagerPermissions[] = $model . '_' . $action;
            }
        }
        $hostelManagerRole->syncPermissions($hostelManagerPermissions);

        $studentPermissions = [];
        foreach (['payments', 'hostels', 'galleries'] as $model) {
            $studentPermissions[] = $model . '_access';
        }
        $studentRole->syncPermissions($studentPermissions);

        // Create users
        $admin = User::firstOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9761762036',
                'address' => 'काठमाडौं, नेपाल',
                'payment_verified' => true,
                'role_id' => 1 // Add role_id
            ]
        );
        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'regmiashish629@gmail.com'],
            [
                'name' => 'Ashish Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9761762036',
                'address' => 'पोखरा, नेपाल',
                'payment_verified' => true,
                'role_id' => 2 // Add role_id
            ]
        );
        $manager->assignRole('hostel_manager');

        $student = User::firstOrCreate(
            ['email' => 'shresthaxok@gmail.com'],
            [
                'name' => 'Ashok Shrestha',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '9851134338',
                'address' => 'पोखरा, नेपाल',
                'payment_verified' => false,
                'role_id' => 3 // Add role_id
            ]
        );
        $student->assignRole('student');

        // Run seeders
        $this->call([
            CollegeSeeder::class,
            CourseSeeder::class,
            HostelSeeder::class,
            RoomSeeder::class,
            StudentSeeder::class,
        ]);

        // Clear permission cache again
        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }
    }
}
