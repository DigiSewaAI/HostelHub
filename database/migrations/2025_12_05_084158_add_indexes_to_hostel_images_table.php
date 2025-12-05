<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hostels table maa indexes
        Schema::table('hostels', function (Blueprint $table) {
            // Featured hostel query ko lagi
            $table->index(['is_featured', 'is_published', 'status']);

            // City-based search ko lagi  
            $table->index(['city', 'is_published']);
        });

        // Hostel_images table maa indexes
        Schema::table('hostel_images', function (Blueprint $table) {
            // Primary image khojda fast
            $table->index(['hostel_id', 'is_primary']);

            // Active images khojda fast
            $table->index(['hostel_id', 'is_active']);

            // Featured images khojda fast
            $table->index(['is_active', 'is_featured']);
        });
    }

    public function down()
    {
        // Hostels table indexes drop
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropIndex(['is_featured', 'is_published', 'status']);
            $table->dropIndex(['city', 'is_published']);
        });

        // Hostel_images table indexes drop
        Schema::table('hostel_images', function (Blueprint $table) {
            $table->dropIndex(['hostel_id', 'is_primary']);
            $table->dropIndex(['hostel_id', 'is_active']);
            $table->dropIndex(['is_active', 'is_featured']);
        });
    }
};
