<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollegeSeeder extends Seeder
{
    public function run()
    {
        $colleges = [
            [
                'name' => 'त्रिभुवन विश्वविद्यालय',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'काठमाण्डौ विश्वविद्यालय',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'पोखरा विश्वविद्यालय',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'महेन्द्र संस्कृत क्याम्पस',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'पुलचोक प्राविधिक क्याम्पस',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('colleges')->insert($colleges);
    }
}
