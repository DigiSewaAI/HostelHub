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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::disableForeignKeyConstraints();

        // सबै permission सम्बन्धित तालिकाहरू खाली गर्ने
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();

        Schema::enableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Permission हरू सिर्जना गर्ने (model_action format मा)
        $models = ['payments', 'students', 'hostels', 'rooms', 'galleries', 'contacts', 'reviews', 'meals', 'organizations'];
        $actions = ['access', 'create', 'edit', 'delete', 'export', 'report'];

        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissionName = $model . '_' . $action; // model_action format
                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
        }

        // भूमिकाहरू सिर्जना गर्ने
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $hostelManagerRole = Role::create(['name' => 'hostel_manager', 'guard_name' => 'web']);
        $studentRole = Role::create(['name' => 'student', 'guard_name' => 'web']);

        // Permission हरू असाइन गर्ने
        $adminRole->syncPermissions(Permission::all());

        $hostelManagerPermissions = [
            'payments_access',
            'payments_create',
            'payments_edit',
            'payments_delete',
            'payments_export',
            'payments_report',
            'students_access',
            'hostels_access',
            'rooms_access',
            'galleries_access',
            'contacts_access',
            'reviews_access',
            'meals_access'
        ];
        $hostelManagerRole->syncPermissions($hostelManagerPermissions);

        $studentRole->syncPermissions([
            'payments_access',
            'hostels_access',
            'galleries_access'
        ]);

        // प्रयोगकर्ताहरू सिर्जना गर्ने
        $admin = User::firstOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9761762036',
                'address' => 'काठमाडौं, नेपाल',
                'payment_verified' => true
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
                'payment_verified' => true
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
                'payment_verified' => false
            ]
        );
        $student->assignRole('student');

        $this->call([
            // CourseSeeder::class,
            // HostelHubSeeder::class,
            // ReviewSeeder::class,
            // GallerySeeder::class,
        ]);
    }
}
