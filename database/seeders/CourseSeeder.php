<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Bachelor in Computer Science',
                'duration' => '4 Years',
                'description' => 'Full-stack development, AI, and cloud computing'
            ],
            [
                'name' => 'Bachelor in Business Administration',
                'duration' => '3 Years',
                'description' => 'Finance, Marketing, and Entrepreneurship'
            ],
            // आफ्नो अनुसार थप कोर्सहरू यहाँ राख्नुहोस्
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
