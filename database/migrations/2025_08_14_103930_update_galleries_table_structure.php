<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                $table->foreignId('user_id')->nullable()->after('id');
                DB::statement('ALTER TABLE galleries ADD CONSTRAINT galleries_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
            }

            // thumbnail थप्ने (यदि पहिले नै छैन भने)
            if (!Schema::hasColumn('galleries', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('file_path');
            }

            // is_active को डाटा माइग्रेट गर्ने
            if (Schema::hasColumn('galleries', 'is_active')) {
                // स्ट्रिङ मानहरू boolean मा रूपान्तरण
                DB::statement("UPDATE galleries SET is_active = 1 WHERE is_active = 'active' OR is_active = '1' OR is_active = 1");
                DB::statement("UPDATE galleries SET is_active = 0 WHERE is_active = 'inactive' OR is_active = 'draft' OR is_active = '0' OR is_active = 0 OR is_active IS NULL");

                // boolean मा परिवर्तन
                $table->boolean('is_active')->default(true)->change();
            } else {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'is_active')) {
                // 1) पुरानो boolean column drop गर्ने
                $table->dropColumn('is_active');
            }
        });

        Schema::table('galleries', function (Blueprint $table) {
            // 2) नयाँ string column create गर्ने
            $table->string('is_active')->default('active')->after('description');
        });

        // 3) Data update गर्ने
        DB::statement("UPDATE galleries SET is_active = 'active' WHERE is_active IS NULL OR is_active = '1' OR is_active = 1");
        DB::statement("UPDATE galleries SET is_active = 'inactive' WHERE is_active = '0' OR is_active = 0");

        Schema::table('galleries', function (Blueprint $table) {
            // thumbnail हटाउने
            if (Schema::hasColumn('galleries', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }

            // user_id हटाउने
            if (Schema::hasColumn('galleries', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
