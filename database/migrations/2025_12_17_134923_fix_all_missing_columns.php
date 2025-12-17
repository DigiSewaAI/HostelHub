<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Ensure organizations table has default organization
        if (!DB::table('organizations')->where('id', 1)->exists()) {
            DB::table('organizations')->insert([
                'id' => 1,
                'name' => 'Default Organization',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 2: Fix users table
        Schema::table('users', function (Blueprint $table) {
            // Add organization_id if missing
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable()->after('id');
            }
        });

        // Set default organization for existing users
        DB::table('users')->whereNull('organization_id')->update(['organization_id' => 1]);

        // Add foreign key if not exists
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            }
        });

        // Step 3: Fix students table columns
        Schema::table('students', function (Blueprint $table) {
            $columnsToAdd = [
                'name' => function () use ($table) {
                    $table->string('name')->nullable();
                },
                'phone' => function () use ($table) {
                    $table->string('phone')->nullable();
                },
                'email' => function () use ($table) {
                    $table->string('email')->nullable();
                },
                'dob' => function () use ($table) {
                    $table->date('dob')->nullable();
                },
                'gender' => function () use ($table) {
                    $table->string('gender', 10)->nullable();
                },
                'college' => function () use ($table) {
                    $table->string('college')->nullable();
                },
            ];

            foreach ($columnsToAdd as $column => $callback) {
                if (!Schema::hasColumn('students', $column)) {
                    $callback();
                }
            }
        });

        // Step 4: Mark old problematic migrations as DONE
        $problematicMigrations = [
            '2025_10_13_093538_add_email_to_students_table',
            '2025_10_13_114239_add_dob_gender_to_students_table',
            '2025_10_13_134809_add_college_to_students_table',
            '2025_10_13_162334_make_organization_id_nullable_in_users_table',
            '2025_10_13_141714_make_user_id_nullable_in_students_table',
        ];

        foreach ($problematicMigrations as $migration) {
            if (!DB::table('migrations')->where('migration', $migration)->exists()) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => 9999,
                ]);
            }
        }
    }

    public function down(): void
    {
        // We won't drop anything in down() to prevent data loss
        // If you need to rollback, create a separate migration
    }
};
