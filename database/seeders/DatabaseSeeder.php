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
        // भूमिका सिडर एकपटक मात्र चलाउने
        $this->call(RoleSeeder::class);

        // एडमिन यूजर (Parashar Regmi)
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        User::firstOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9761762036',
                'address' => 'काठमाडौं, नेपाल',
                'role_id' => $adminRole->id,
                'payment_verified' => true
            ]
        );

        // होस्टेल म्यानेजर यूजर (Ashish Regmi)
        $managerRole = Role::where('name', 'hostel_manager')->firstOrFail();
        User::firstOrCreate(
            ['email' => 'regmiashish629@gmail.com'],
            [
                'name' => 'Ashish Regmi',
                'password' => Hash::make('Himalayan@1980'),
                'email_verified_at' => now(),
                'phone' => '9761762036',
                'address' => 'पोखरा, नेपाल',
                'role_id' => $managerRole->id,
                'payment_verified' => true
            ]
        );

        // टेस्ट यूजर (छात्र)
        $studentRole = Role::where('name', 'student')->firstOrFail();
        User::firstOrCreate(
            ['email' => 'shresthaxok@gmail.com'],
            [
                'name' => 'Ashok Shrestha',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '9851134338',
                'address' => 'पोखरा, नेपाल',
                'role_id' => $studentRole->id,
                'payment_verified' => false
            ]
        );

        // अन्य सिडरहरू (UserSeeder हटाइएको)
        $this->call([
            CourseSeeder::class,
            HostelHubSeeder::class,
            ReviewSeeder::class,
            GallerySeeder::class,
        ]);
    }
}
