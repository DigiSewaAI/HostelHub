<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // First check if email column already exists
            if (!Schema::hasColumn('students', 'email')) {
                // Check if 'name' column exists to use as reference
                if (Schema::hasColumn('students', 'name')) {
                    // Add email after name if name exists
                    $table->string('email')->nullable()->after('name');
                } else {
                    // Otherwise, just add the email column without 'after' clause
                    $table->string('email')->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Only drop the column if it exists
            if (Schema::hasColumn('students', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
