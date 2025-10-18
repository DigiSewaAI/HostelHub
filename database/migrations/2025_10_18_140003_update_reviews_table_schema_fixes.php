<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReviewsTableSchemaFixes extends Migration
{
    public function up()
    {
        // Check if columns exist before modifying
        if (Schema::hasTable('reviews')) {

            // Fix status column
            if (Schema::hasColumn('reviews', 'status')) {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->string('status', 20)->default('pending')->change();
                });
            }

            // Make name nullable
            if (Schema::hasColumn('reviews', 'name')) {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->string('name')->nullable()->change();
                });
            }

            // Make position nullable  
            if (Schema::hasColumn('reviews', 'position')) {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->string('position')->nullable()->change();
                });
            }

            // Fix type column
            if (Schema::hasColumn('reviews', 'type')) {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->string('type', 20)->default('review')->change();
                });
            }

            // Fix rating column
            if (Schema::hasColumn('reviews', 'rating')) {
                Schema::table('reviews', function (Blueprint $table) {
                    $table->integer('rating')->default(0)->change();
                });
            }
        }
    }

    public function down()
    {
        // Revert changes if needed
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                // Revert to previous state if necessary
                $table->string('status', 20)->default('pending')->change();
                $table->string('name')->nullable()->change();
                $table->string('position')->nullable()->change();
            });
        }
    }
}
