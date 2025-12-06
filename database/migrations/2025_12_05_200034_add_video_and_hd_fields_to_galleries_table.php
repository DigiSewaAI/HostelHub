<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Video fields
            $table->string('video_duration')->nullable()->after('external_link');
            $table->string('video_resolution')->nullable()->after('video_duration');
            $table->boolean('is_360_video')->default(false)->after('video_resolution');
            $table->string('video_thumbnail')->nullable()->after('is_360_video');

            // HD image field
            $table->string('hd_file_path')->nullable()->after('file_path');

            // Indexes for better performance
            $table->index(['media_type', 'is_active'], 'galleries_media_type_active_index');
            $table->index(['category', 'is_active'], 'galleries_category_active_index');
            $table->index(['hostel_id', 'is_active'], 'galleries_hostel_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn([
                'video_duration',
                'video_resolution',
                'is_360_video',
                'video_thumbnail',
                'hd_file_path'
            ]);

            // Drop indexes
            $table->dropIndex('galleries_media_type_active_index');
            $table->dropIndex('galleries_category_active_index');
            $table->dropIndex('galleries_hostel_active_index');
        });
    }
};
