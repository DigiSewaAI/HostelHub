<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add dob column safely
            if (!Schema::hasColumn('students', 'dob')) {
                // Check if phone exists to use as reference
                if (Schema::hasColumn('students', 'phone')) {
                    $table->date('dob')->nullable()->after('phone');
                } else {
                    // Add without after clause if phone doesn't exist
                    $table->date('dob')->nullable();
                }
            }

            // Add gender column safely
            if (!Schema::hasColumn('students', 'gender')) {
                // Gender should come after dob
                $table->string('gender', 10)->nullable()->after('dob');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Safely remove columns if they exist
            if (Schema::hasColumn('students', 'dob')) {
                $table->dropColumn('dob');
            }
            if (Schema::hasColumn('students', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};
