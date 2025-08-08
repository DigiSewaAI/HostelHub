<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Course;
use App\Models\Hostel;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class HostelHubSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Colleges
        $college1 = College::updateOrCreate(['name' => 'काठमाडौं विश्वविद्यालय']);
        $college2 = College::updateOrCreate(['name' => 'पुर्वाञ्चल विश्वविद्यालय']);
        $college3 = College::updateOrCreate(['name' => 'पोखरा विश्वविद्यालय']);

        // 2. Create Courses
        $courseBScIT = Course::updateOrCreate(
            ['name' => 'बी.एस्सी.आई.टि.'],
            ['duration' => '४ वर्ष']
        );
        $courseBE = Course::updateOrCreate(
            ['name' => 'बी.ई.'],
            ['duration' => '४ वर्ष']
        );
        $courseBA = Course::updateOrCreate(
            ['name' => 'बी.ए.'],
            ['duration' => '३ वर्ष']
        );

        // 3. Create Hostels
        $hostel1 = Hostel::updateOrCreate(
            ['slug' => 'kathmandu-hostel'],
            [
                'name' => 'काठमाडौं होस्टल',
                'slug' => 'kathmandu-hostel',
                'address' => 'कमलपोखरी, काठमाडौं',
                'city' => 'काठमाडौं',
                'contact_person' => 'राम श्रेष्ठ',
                'contact_phone' => '9801234567',
                'contact_email' => 'kathmandu@hostelhub.com',
                'total_rooms' => 50,
                'available_rooms' => 30,
                'status' => 'active'
            ]
        );

        $hostel2 = Hostel::updateOrCreate(
            ['slug' => 'pokhara-hostel'],
            [
                'name' => 'पोखरा होस्टल',
                'slug' => 'pokhara-hostel',
                'address' => 'लेकसाइड, पोखरा',
                'city' => 'पोखरा',
                'contact_person' => 'श्याम अधिकारी',
                'contact_phone' => '9807654321',
                'contact_email' => 'pokhara@hostelhub.com',
                'total_rooms' => 30,
                'available_rooms' => 20,
                'status' => 'active'
            ]
        );

        // 4. Create Rooms
        $this->createRoom($hostel1, '101', 'single', 1, 15000, 'available');
        $this->createRoom($hostel1, '102', 'single', 1, 15000, 'available');
        $this->createRoom($hostel1, '201', 'double', 2, 25000, 'occupied');
        $this->createRoom($hostel2, '101', 'single', 1, 12000, 'available');
        $this->createRoom($hostel2, '201', 'double', 2, 20000, 'available');

        // 5. Create Students (WITH CORRECT EMAIL CHECK)
        $this->createStudent(
            'सुनीता पौडेल',
            'sunita@example.com',
            '9812345678',
            'पोखरा',
            '2001-04-15',
            'Female',
            'हरि पौडेल',
            '9843123456',
            'Father',
            'पोखरा',
            $courseBScIT->id,
            'First',
            'First',
            $college1->id,
            $hostel1->id,
            $hostel1->rooms()->where('room_number', '101')->first()->id ?? null
        );

        $this->createStudent(
            'राम श्रेष्ठ',
            'ram@example.com',
            '9801234567',
            'काठमाडौं',
            '2000-07-22',
            'Male',
            'श्याम श्रेष्ठ',
            '9841234567',
            'Father',
            'काठमाडौं',
            $courseBE->id,
            'Second',
            'Third',
            $college2->id,
            $hostel1->id,
            $hostel1->rooms()->where('room_number', '201')->first()->id ?? null
        );

        $this->createStudent(
            'सीता अधिकारी',
            'sita@example.com',
            '9845678901',
            'ललितपुर',
            '2002-01-10',
            'Female',
            'गोपाल अधिकारी',
            '9801234567',
            'Father',
            'ललितपुर',
            $courseBA->id,
            'First',
            'Second',
            $college3->id,
            $hostel2->id,
            $hostel2->rooms()->where('room_number', '101')->first()->id ?? null
        );
    }

    private function createRoom($hostel, $roomNumber, $type, $capacity, $price, $status)
    {
        Room::updateOrCreate(
            [
                'hostel_id' => $hostel->id,
                'room_number' => $roomNumber
            ],
            [
                'type' => $type,
                'capacity' => $capacity,
                'price' => $price,
                'status' => $status
            ]
        );
    }

    private function createStudent(
        $name,
        $email,
        $phone,
        $address,
        $dob,
        $gender,
        $guardianName,
        $guardianPhone,
        $guardianRelation,
        $guardianAddress,
        $courseId,
        $year,
        $semester,
        $collegeId,
        $hostelId,
        $roomId
    ) {
        // ✅ CORRECT CHECK: User table मा email पहिले नै छ कि जाँच गर्नुहोस्
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password123'),
            'role' => 'student',
            'phone' => $phone,
            'address' => $address,
            'email_verified_at' => now()
        ]);

        Student::create([
            'user_id' => $user->id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'dob' => $dob,
            'gender' => $gender,
            'guardian_name' => $guardianName,
            'guardian_phone' => $guardianPhone,
            'guardian_relation' => $guardianRelation,
            'guardian_address' => $guardianAddress,
            'course_id' => $courseId,
            'year' => $year,
            'semester' => $semester,
            'college_id' => $collegeId,
            'hostel_id' => $hostelId,
            'room_id' => $roomId,
            'admission_date' => now()->subMonths(rand(1, 12)),
            'status' => 'active',
            'college' => College::find($collegeId)->name,
            'course' => Course::find($courseId)->name
        ]);
    }
}
