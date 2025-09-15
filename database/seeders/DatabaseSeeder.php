<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Organization;
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

        // Truncate tables safely with proper order to respect foreign key constraints
        $tables = [
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
            'permissions',
            'roles',
            'bookings',
            'payments',
            'students',
            'rooms',
            'room_types',
            'hostels',
            'organizations',
            'organization_user',
            'users',
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

        // Create organizations
        $organizations = [
            Organization::firstOrCreate(
                ['name' => 'Default Organization'],
                [
                    'slug' => 'default-organization',
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
                ]
            ),
            Organization::firstOrCreate(
                ['name' => 'Hostel A'],
                [
                    'slug' => 'hostel-a',
                    'is_ready' => true,
                    'settings' => [
                        'city' => 'Kathmandu',
                        'address' => 'Kathmandu, Nepal',
                        'contact_phone' => '+977-1-1111111',
                        'monthly_fee' => 6000,
                        'deposit' => 12000,
                        'meal_plan' => true,
                        'meal_price' => 2500,
                    ]
                ]
            ),
            Organization::firstOrCreate(
                ['name' => 'Hostel B'],
                [
                    'slug' => 'hostel-b',
                    'is_ready' => true,
                    'settings' => [
                        'city' => 'Pokhara',
                        'address' => 'Pokhara, Nepal',
                        'contact_phone' => '+977-1-2222222',
                        'monthly_fee' => 5500,
                        'deposit' => 11000,
                        'meal_plan' => true,
                        'meal_price' => 2300,
                    ]
                ]
            )
        ];

        // Create users WITHOUT organization_id
        $admin = User::firstOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9761762036',
                'address' => 'काठमाडौं, नेपाल',
                'payment_verified' => true,
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
            ]
        );
        $student->assignRole('student');

        // Create organization-user relationships using sync to avoid duplicates
        $admin->organizations()->sync([
            $organizations[0]->id => ['role' => 'admin'],
            $organizations[1]->id => ['role' => 'admin'],
            $organizations[2]->id => ['role' => 'admin']
        ]);

        $manager->organizations()->sync([
            $organizations[0]->id => ['role' => 'manager'],
            $organizations[1]->id => ['role' => 'manager']
        ]);

        $student->organizations()->sync([
            $organizations[0]->id => ['role' => 'student']
        ]);

        // Run other seeders
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
