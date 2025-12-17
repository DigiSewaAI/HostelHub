<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostel_images', function (Blueprint $table) {
            // First, check if is_active column exists
            if (Schema::hasColumn('hostel_images', 'is_active')) {
                // Only add the index if the column exists
                $table->index(['hostel_id', 'is_active'], 'hostel_images_hostel_id_is_active_index');
            }

            // Check if other columns exist before adding indexes
            if (Schema::hasColumn('hostel_images', 'is_primary')) {
                $table->index('is_primary', 'hostel_images_is_primary_index');
            }

            if (Schema::hasColumn('hostel_images', 'created_at')) {
                $table->index('created_at', 'hostel_images_created_at_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hostel_images', function (Blueprint $table) {
            // Safely remove indexes if they exist
            $indexes = [
                'hostel_images_hostel_id_is_active_index',
                'hostel_images_is_primary_index',
                'hostel_images_created_at_index'
            ];

            foreach ($indexes as $index) {
                try {
                    $table->dropIndex($index);
                } catch (\Exception $e) {
                    // Index doesn't exist, ignore
                }
            }
        });
    }
};
