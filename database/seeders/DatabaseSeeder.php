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

        // Disable foreign key checks safely
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables safely with proper order
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
            'organization_user',
            'organizations',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Schema::enableForeignKeyConstraints();

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

        // ✅ **FIXED: Create organizations with proper slug**
        $organizations = [
            [
                'name' => 'Default Organization',
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
            ],
            [
                'name' => 'Hostel A',
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
            ],
            [
                'name' => 'Hostel B',
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
        ];

        $organizationModels = [];
        foreach ($organizations as $orgData) {
            $org = Organization::firstOrCreate(
                ['slug' => $orgData['slug']], // Use slug as unique identifier
                $orgData
            );
            $organizationModels[] = $org;
        }

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
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9851134338',
                'address' => 'पोखरा, नेपाल',
                'payment_verified' => false,
            ]
        );
        $student->assignRole('student');

        // Create additional test users with SIMPLE password for testing only
        User::firstOrCreate(
            ['email' => 'student@hostelhub.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '9800000000',
                'address' => 'Kathmandu, Nepal',
                'payment_verified' => false,
            ]
        )->assignRole('student');

        // Create organization-user relationships
        $admin->organizations()->sync([
            $organizationModels[0]->id => ['role' => 'admin'],
            $organizationModels[1]->id => ['role' => 'admin'],
            $organizationModels[2]->id => ['role' => 'admin']
        ]);

        $manager->organizations()->sync([
            $organizationModels[0]->id => ['role' => 'manager'],
            $organizationModels[1]->id => ['role' => 'manager']
        ]);

        $student->organizations()->sync([
            $organizationModels[0]->id => ['role' => 'student']
        ]);

        // ✅ **FIXED: Check if seeders exist before calling**
        if ($this->shouldSeedWithData()) {
            $seeders = [
                'CollegeSeeder',
                'CourseSeeder',
                'HostelSeeder',
                'RoomSeeder',
                'StudentSeeder',
            ];

            foreach ($seeders as $seeder) {
                $seederClass = "Database\\Seeders\\{$seeder}";
                if (class_exists($seederClass)) {
                    $this->call($seederClass);
                }
            }
        }

        // Clear permission cache again
        if (class_exists(\Spatie\Permission\PermissionRegistrar::class)) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }

        // Output login information
        echo "\n\n🎉 DEFAULT LOGIN CREDENTIALS:\n";
        echo "==============================\n";
        echo "👑 Admin: parasharregmi@gmail.com / Himalayan@1980\n";
        echo "👨‍💼 Manager: regmiashish629@gmail.com / Himalayan@1980\n";
        echo "🎓 Student: shresthaxok@gmail.com / Himalayan@1980\n";
        echo "🧪 Test Student: student@hostelhub.com / password123\n";
        echo "==============================\n\n";
    }

    /**
     * Determine if we should seed with sample data
     * - Always seed in local/development
     * - Only seed in production if explicitly allowed
     */
    private function shouldSeedWithData(): bool
    {
        // In local/development, always seed
        if (app()->environment('local', 'development', 'testing')) {
            return true;
        }

        // In production, only seed if explicitly allowed via env
        return env('SEED_WITH_SAMPLE_DATA', false) === true;
    }
}
