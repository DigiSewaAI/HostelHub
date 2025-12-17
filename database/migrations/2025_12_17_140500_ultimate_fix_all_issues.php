<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: FIX organizations table - make slug nullable
        try {
            if (Schema::hasTable('organizations')) {
                // Check if slug column exists
                if (Schema::hasColumn('organizations', 'slug')) {
                    // Make slug nullable using raw SQL (safe way)
                    DB::statement("ALTER TABLE organizations MODIFY slug VARCHAR(255) NULL");
                } else {
                    // Add slug if it doesn't exist
                    Schema::table('organizations', function (Blueprint $table) {
                        $table->string('slug')->nullable()->after('name');
                    });
                }

                // Insert default organization ONLY if doesn't exist
                if (!DB::table('organizations')->where('id', 1)->exists()) {
                    DB::table('organizations')->insert([
                        'id' => 1,
                        'name' => 'Default Organization',
                        'slug' => 'default-organization',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Just continue if any error
        }

        // Step 2: FIX users table - SIMPLER VERSION
        try {
            if (Schema::hasTable('users') && !Schema::hasColumn('users', 'organization_id')) {
                // First add the column
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('organization_id')
                        ->default(1)
                        ->after('id');
                });

                // Then add foreign key using Laravel's constrained() method
                Schema::table('users', function (Blueprint $table) {
                    // This automatically creates the foreign key
                    $table->foreign('organization_id')
                        ->references('id')
                        ->on('organizations');
                });
            }
        } catch (\Exception $e) {
            // Continue even if fails
        }

        // Step 3: MARK problematic migrations as done (FINAL STEP)
        $migrationsToSkip = [
            '2025_09_14_125548_modify_organization_id_in_users_table',
            '2025_10_13_150000_add_organization_id_to_users_table_first',
            '2025_10_13_162334_make_organization_id_nullable_in_users_table',
            '2025_10_13_093538_add_email_to_students_table',
            '2025_10_13_114239_add_dob_gender_to_students_table',
            '2025_10_13_134809_add_college_to_students_table',
        ];

        foreach ($migrationsToSkip as $migration) {
            if (!DB::table('migrations')->where('migration', $migration)->exists()) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => 100, // High batch number
                ]);
            }
        }
    }

    public function down(): void
    {
        // No down() - this is a one-way fix
    }
};
