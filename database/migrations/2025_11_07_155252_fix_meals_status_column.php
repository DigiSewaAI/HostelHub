<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, let's check the current structure and fix it
        Schema::table('meals', function (Blueprint $table) {
            // Check if status column exists and modify it
            if (Schema::hasColumn('meals', 'status')) {
                // Change the column to match our application needs
                $table->string('status', 20)->default('pending')->change();
            } else {
                // Add the column if it doesn't exist
                $table->string('status', 20)->default('pending');
            }
        });

        // If there are existing records, update them to valid statuses
        DB::table('meals')->whereNotIn('status', ['pending', 'served', 'missed'])->update(['status' => 'pending']);
    }

    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            // Revert if needed
            $table->string('status', 20)->default('pending')->change();
        });
    }
};
