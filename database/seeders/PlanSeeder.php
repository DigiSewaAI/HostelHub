<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'starter',
                'name' => 'सुरुवाती',
                'price_month' => 2999, // Rs. 2,999
                'max_students' => 50,
                'max_hostels' => 1,
                'features' => json_encode([
                    'मूल विद्यार्थी व्यवस्थापन',
                    'कोठा आवंटन',
                    'भुक्तानी ट्र्याकिंग',
                    'भोजन व्यवस्थापन',
                    'मोबाइल एप्प'
                ]),
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'slug' => 'pro',
                'name' => 'प्रो',
                'price_month' => 4999, // Rs. 4,999
                'max_students' => 200,
                'max_hostels' => 1,
                'features' => json_encode([
                    'पूर्ण विद्यार्थी व्यवस्थापन',
                    'अग्रिम कोठा बुकिंग',
                    'भुक्तानी ट्र्याकिंग',
                    'भोजन व्यवस्थापन',
                    'मोबाइल एप्प'
                ]),
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'slug' => 'enterprise',
                'name' => 'एन्टरप्राइज',
                'price_month' => 8999, // Rs. 8,999
                'max_students' => 9999,
                'max_hostels' => 10,
                'features' => json_encode([
                    'पूर्ण विद्यार्थी व्यवस्थापन',
                    'बहु-होस्टल व्यवस्थापन',
                    'कस्टम भुक्तानी प्रणाली',
                    'विस्तृत विवरण र विश्लेषण',
                    '२४/७ समर्थन'
                ]),
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
