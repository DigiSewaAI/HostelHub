<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::create([
            'name' => 'Starter',
            'price_month' => 299900, // Rs. 2,999 in paisa
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
        ]);

        Plan::create([
            'name' => 'Pro',
            'price_month' => 499900, // Rs. 4,999 in paisa
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
        ]);

        Plan::create([
            'name' => 'Enterprise',
            'price_month' => 899900, // Rs. 8,999 in paisa
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
        ]);
    }
}
