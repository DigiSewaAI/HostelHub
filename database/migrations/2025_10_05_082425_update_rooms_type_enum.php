<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // पहिले: enum मा 'shared' थप्नुहोस् र 'dorm' लाई 'shared' मा परिवर्तन गर्नुहोस्
        DB::statement("ALTER TABLE rooms MODIFY type ENUM('single', 'double', 'dorm', 'shared')");
        
        // अब: सबै 'dorm' records लाई 'shared' मा परिवर्तन गर्नुहोस्
        DB::table('rooms')->where('type', 'dorm')->update(['type' => 'shared']);
        
        // अन्तमा: 'dorm' लाई enum बाट हटाउनुहोस्
        DB::statement("ALTER TABLE rooms MODIFY type ENUM('single', 'double', 'shared')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert गर्दा: फेरी 'dorm' थप्नुहोस्
        DB::statement("ALTER TABLE rooms MODIFY type ENUM('single', 'double', 'dorm', 'shared')");
        
        // सबै 'shared' लाई 'dorm' मा बदल्नुहोस्
        DB::table('rooms')->where('type', 'shared')->update(['type' => 'dorm']);
        
        // फेरी मूल form मा ल्याउनुहोस्
        DB::statement("ALTER TABLE rooms MODIFY type ENUM('single', 'double', 'dorm')");
    }
};