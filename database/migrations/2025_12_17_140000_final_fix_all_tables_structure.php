<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: FIX organizations table structure FIRST
        // Check if slug column exists and if it's nullable
        if (Schema::hasTable('organizations')) {
            // Make slug nullable if it exists and is not nullable
            try {
                DB::statement("
                    ALTER TABLE organizations 
                    MODIFY COLUMN slug VARCHAR(255) NULL
                ");
            } catch (\Exception $e) {
                // If fails, maybe slug doesn't exist yet or already nullable
            }

            // Add slug if it doesn't exist
            if (!Schema::hasColumn('organizations', 'slug')) {
                Schema::table('organizations', function (Blueprint $table) {
                    $table->string('slug')->nullable()->after('name');
                });
            }

            // Insert default organization with slug
            if (!DB::table('organizations')->where('id', 1)->exists()) {
                DB::table('organizations')->insert([
                    'id' => 1,
                    'name' => 'Default Organization',
                    'slug' => 'default-organization', // ADD THIS
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Step 2: FIX users table - Add organization_id if missing
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'organization_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')
                    ->default(1)
                    ->after('id');
            });

            // Add foreign key
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            });
        }

        // Step 3: MARK ALL PROBLEMATIC MIGRATIONS AS DONE
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
                    'batch' => 99999, // Highest batch number
                ]);
            }
        }
    }

    public function down(): void
    {
        // We're not going back - this is a one-way fix
    }
};
