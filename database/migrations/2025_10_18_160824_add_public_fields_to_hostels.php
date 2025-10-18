<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hostels', function (Blueprint $table) {
            if (!Schema::hasColumn('hostels', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('description');
            }
            if (!Schema::hasColumn('hostels', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
            if (!Schema::hasColumn('hostels', 'theme_color')) {
                $table->string('theme_color', 7)->default('#3b82f6')->after('published_at');
            }
            if (!Schema::hasColumn('hostels', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('theme_color');
            }
            if (!Schema::hasColumn('hostels', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });

        // Add index for better performance
        Schema::table('hostels', function (Blueprint $table) {
            $table->index(['is_published', 'published_at']);
            $table->index('slug');
        });
    }

    public function down()
    {
        // Safe down migration - don't drop columns to avoid data loss
    }
};
