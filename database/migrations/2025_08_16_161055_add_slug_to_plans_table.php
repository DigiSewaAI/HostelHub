<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Plan; // Added missing model import

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // कलम अझै अवस्थित छ कि जाँच गर्ने
        if (!Schema::hasColumn('plans', 'slug')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->string('slug')->after('id');
            });
        }

        // योजनाहरूको स्लग अपडेट गर्ने
        $plans = [
            ['name' => 'Starter', 'slug' => 'starter'],
            ['name' => 'Pro', 'slug' => 'pro'],
            ['name' => 'Enterprise', 'slug' => 'enterprise'],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')
                ->where('name', $plan['name'])
                ->update(['slug' => $plan['slug']]);
        }

        // युनिक कन्स्ट्रेन्ट थप्ने (केवल यदि पहिले थपिएको छैन भने)
        Schema::table('plans', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('slug'); // Added proper column removal
        });
    }
};
