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
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('id');
            }

            // file_path थप्ने (यदि पहिले नै छैन भने)
            if (!Schema::hasColumn('galleries', 'file_path')) {
                $table->string('file_path')->nullable()->after('image');
            }

            // thumbnail थप्ने (यदि पहिले नै छैन भने)
            if (!Schema::hasColumn('galleries', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('file_path');
            }
        });

        // is_active को डाटा माइग्रेट गर्ने
        if (Schema::hasColumn('galleries', 'is_active')) {
            // स्ट्रिङ मानहरू boolean मा रूपान्तरण
            DB::statement("UPDATE galleries SET is_active = 1 WHERE is_active = 'active' OR is_active = '1'");
            DB::statement("UPDATE galleries SET is_active = 0 WHERE is_active = 'inactive' OR is_active = 'draft' OR is_active = '0' OR is_active IS NULL");

            // boolean मा परिवर्तन
            Schema::table('galleries', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->change();
            });
        } else {
            Schema::table('galleries', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // is_active लाई फेरि string मा परिवर्तन गर्ने
        if (Schema::hasColumn('galleries', 'is_active')) {
            DB::statement("UPDATE galleries SET is_active = 'active' WHERE is_active = 1");
            DB::statement("UPDATE galleries SET is_active = 'inactive' WHERE is_active = 0");

            Schema::table('galleries', function (Blueprint $table) {
                $table->string('is_active')->default('active')->change();
            });
        }

        Schema::table('galleries', function (Blueprint $table) {
            // thumbnail हटाउने
            if (Schema::hasColumn('galleries', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }

            // file_path हटाउने
            if (Schema::hasColumn('galleries', 'file_path')) {
                $table->dropColumn('file_path');
            }

            // user_id हटाउने
            if (Schema::hasColumn('galleries', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
