<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Ensure organizations table has default organization (handle slug safely)
        if (!DB::table('organizations')->where('id', 1)->exists()) {
            // Check if organizations table has slug column
            $hasSlug = false;
            try {
                $hasSlug = Schema::hasColumn('organizations', 'slug');
            } catch (\Exception $e) {
                // Table might not exist yet, that's okay
            }

            $orgData = [
                'id' => 1,
                'name' => 'Default Organization',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Only add slug if the column exists
            if ($hasSlug) {
                $orgData['slug'] = 'default-organization';
            }

            DB::table('organizations')->insert($orgData);
        }

        // Step 2: Add organization_id to users if it doesn't exist
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'organization_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')
                    ->default(1)
                    ->after('id');
            });

            // Step 3: Try to add foreign key (will fail gracefully if exists)
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('organization_id')
                        ->references('id')
                        ->on('organizations')
                        ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist, ignore
            }
        }
    }

    public function down(): void
    {
        // Only remove foreign key if column exists
        if (Schema::hasColumn('users', 'organization_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['organization_id']);
                });
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }
        }
    }
};
