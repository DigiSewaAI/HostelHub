<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update plans table with proper max_hostels limits
        DB::table('plans')->where('slug', 'starter')->update([
            'max_hostels' => 1,
            'features' => json_encode([
                'बेसिक विद्यार्थी व्यवस्थापन',
                'कोठा आवंटन',
                'भुक्तानी ट्र्याकिंग',
                'भोजन व्यवस्थापन',
                'मोबाइल एप्प',
                'बेसिक अग्रिम कोठा बुकिंग (manual approval)'
            ])
        ]);

        DB::table('plans')->where('slug', 'pro')->update([
            'max_hostels' => 1,
            'features' => json_encode([
                'पूर्ण विद्यार्थी व्यवस्थापन',
                'अग्रिम कोठा बुकिंग (auto-confirm)',
                'भुक्तानी ट्र्याकिंग',
                'भोजन व्यवस्थापन',
                'मोबाइल एप्प',
                'विस्तृत विवरण र विश्लेषण'
            ])
        ]);

        DB::table('plans')->where('slug', 'enterprise')->update([
            'max_hostels' => 5,
            'features' => json_encode([
                'पूर्ण विद्यार्थी व्यवस्थापन',
                'बहु-होस्टल व्यवस्थापन (५ होस्टल सम्म)',
                'अग्रिम कोठा बुकिंग (auto-confirm)',
                'कस्टम भुक्तानी प्रणाली',
                'विस्तृत विवरण र विश्लेषण',
                '२४/७ समर्थन'
            ])
        ]);
    }

    public function down(): void
    {
        // Revert back to original values
        DB::table('plans')->where('slug', 'starter')->update([
            'max_hostels' => 0,
            'features' => json_encode([])
        ]);

        DB::table('plans')->where('slug', 'pro')->update([
            'max_hostels' => 0,
            'features' => json_encode([])
        ]);

        DB::table('plans')->where('slug', 'enterprise')->update([
            'max_hostels' => 0,
            'features' => json_encode([])
        ]);
    }
};
