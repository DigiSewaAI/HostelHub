<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'name' => 'बिज्ञान तह',
                'duration' => '४ वर्ष',
                'description' => 'स्नातक तहको बिज्ञान विषय',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'प्राविधिक शिक्षा',
                'duration' => '३ वर्ष',
                'description' => 'इन्जिनियरिङ्गको डिप्लोमा',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'व्यवस्थापन',
                'duration' => '४ वर्ष',
                'description' => 'बिजनेस एड्मिनिस्ट्रेसन',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'कम्प्युटर विज्ञान',
                'duration' => '४ वर्ष',
                'description' => 'बिज्ञान स्नातक कम्प्युटर विज्ञान',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'अर्थशास्त्र',
                'duration' => '३ वर्ष',
                'description' => 'कला स्नातक अर्थशास्त्र',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('courses')->insert($courses);
    }
}
