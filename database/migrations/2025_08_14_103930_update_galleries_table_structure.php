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
        Schema::table('galleries', function (Blueprint $table) {
            // user_id थप्ने (यदि पहिले नै छैन भने)
            if (!Schema::hasColumn('galleries', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            }

            // thumbnail थप्ने (यदि पहिले नै छैन भने)
            if (!Schema::hasColumn('galleries', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('file_path');
            }

            // is_active को डाटा माइग्रेट गर्ने
            if (Schema::hasColumn('galleries', 'is_active')) {
                // सबै सम्भावित स्ट्रिङ मानहरूलाई boolean मा रूपान्तरण गर्ने
                DB::statement("UPDATE galleries SET is_active = '1' WHERE is_active = 'active'");
                DB::statement("UPDATE galleries SET is_active = '0' WHERE is_active = 'inactive' OR is_active = 'draft' OR is_active IS NULL");

                // डाटा प्रकार परिवर्तन गर्ने
                $table->boolean('is_active')->default(true)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // is_active को डाटा प्रकार फिर्ता ल्याउने
            if (Schema::hasColumn('galleries', 'is_active')) {
                // boolean बाट स्ट्रिङ मा रूपान्तरण गर्ने
                DB::statement("UPDATE galleries SET is_active = 'active' WHERE is_active = 1");
                DB::statement("UPDATE galleries SET is_active = 'inactive' WHERE is_active = 0");

                $table->string('is_active')->default('active')->change();
            }

            // thumbnail हटाउने (यदि पहिले नै छ भने)
            if (Schema::hasColumn('galleries', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }

            // user_id हटाउने (यदि पहिले नै छ भने)
            if (Schema::hasColumn('galleries', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
