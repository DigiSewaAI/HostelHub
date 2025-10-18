<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInReviewsTable extends Migration
{
    public function up()
    {
        // Option A: Change to string (flexible)
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change();
        });

        // OR Option B: Update enum values
        // DB::statement("ALTER TABLE reviews MODIFY status ENUM('pending', 'approved', 'rejected', 'active', 'inactive') DEFAULT 'pending'");
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->change();
        });
    }
}
