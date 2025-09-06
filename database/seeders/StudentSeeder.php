<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\College;
use App\Models\Course;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Student;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Check for student role
        $studentRole = Role::where('name', 'student')->first();

        if (!$studentRole) {
            $this->command->error('Student role not found. Please run permission seeder first.');
            return;
        }

        // Get or create student users
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->get();

        if ($students->count() < 2) {
            $studentUsers = [
                [
                    'name' => 'राम श्रेष्ठ',
                    'email' => 'ram@example.com',
                    'password' => bcrypt('password'),
                    'phone' => '9841000001',
                    'address' => 'काठमाडौं, नेपाल',
                    'payment_verified' => true,
                ],
                [
                    'name' => 'सीता महर्जन',
                    'email' => 'sita@example.com',
                    'password' => bcrypt('password'),
                    'phone' => '9841000002',
                    'address' => 'ललितपुर, नेपाल',
                    'payment_verified' => true,
                ]
            ];

            foreach ($studentUsers as $userData) {
                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    $userData
                );
                $user->assignRole('student');
            }

            $students = User::whereHas('roles', fn($q) => $q->where('name', 'student'))->get();
        }

        // Get required relations
        $college = College::first();
        $course = Course::first();
        $hostel = Hostel::first();
        $room = Room::first();

        if (!$college || !$course || !$hostel || !$room) {
            $this->command->error('Missing required relations. Please seed colleges, courses, hostels, and rooms first.');
            return;
        }

        $studentData = [
            [
                'user_id' => $students[0]->id,
                'college_id' => $college->id,
                'course_id' => $course->id,
                'hostel_id' => $hostel->id,
                'room_id' => $room->id,
                'student_id' => 'ST001',
                'guardian_name' => 'हरि श्रेष्ठ',
                'guardian_contact' => '9841000011',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $students[1]->id,
                'college_id' => $college->id,
                'course_id' => $course->id,
                'hostel_id' => $hostel->id,
                'room_id' => $room->id + 1, // Use next room
                'student_id' => 'ST002',
                'guardian_name' => 'गीता महर्जन',
                'guardian_contact' => '9841000012',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($studentData as $data) {
            Student::firstOrCreate(
                ['student_id' => $data['student_id']],
                $data
            );
        }

        $this->command->info('StudentSeeder successfully executed. Created/Updated ' . count($studentData) . ' students.');
    }
}
