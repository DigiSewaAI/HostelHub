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

        // Step 2: Add organization_id column if it doesn't exist
        if (!Schema::hasColumn('users', 'organization_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')
                    ->nullable()
                    ->after('id');
            });

            // Set default value for existing users
            DB::table('users')->update(['organization_id' => 1]);
        }

        // Step 3: Add foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            // Check if foreign key already exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'users' 
                AND COLUMN_NAME = 'organization_id' 
                AND CONSTRAINT_NAME <> 'PRIMARY'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");

            if (empty($foreignKeys)) {
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            }
        });

        // Step 4: Make the column NOT NULL
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable(false)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove foreign key first
            try {
                $table->dropForeign(['organization_id']);
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }

            // Remove column
            if (Schema::hasColumn('users', 'organization_id')) {
                $table->dropColumn('organization_id');
            }
        });
    }
};
