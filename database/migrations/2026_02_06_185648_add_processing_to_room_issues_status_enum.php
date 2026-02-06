<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE room_issues MODIFY COLUMN status ENUM('pending', 'processing', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE room_issues MODIFY COLUMN status ENUM('pending', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }
};
