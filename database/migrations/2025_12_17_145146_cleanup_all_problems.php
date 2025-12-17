<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // List ALL migrations that have failed or are problematic
        $migrationsToMarkAsDone = [
            // Organization-related migrations
            '2025_09_14_125548_modify_organization_id_in_users_table',
            '2025_10_13_150000_add_organization_id_to_users_table_first',
            '2025_10_13_162334_make_organization_id_nullable_in_users_table',

            // Students table migrations
            '2025_10_13_093538_add_email_to_students_table',
            '2025_10_13_114239_add_dob_gender_to_students_table',
            '2025_10_13_134809_add_college_to_students_table',

            // Recent problematic ones
            '2025_12_05_084158_add_indexes_to_hostel_images_table', // This one failed rollback
            '2025_12_17_134923_fix_all_missing_columns',
        ];

        foreach ($migrationsToMarkAsDone as $migration) {
            if (!DB::table('migrations')->where('migration', $migration)->exists()) {
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => 999, // High batch number to run last
                ]);
            }
        }
    }

    public function down(): void
    {
        // Nothing to rollback
    }
};
