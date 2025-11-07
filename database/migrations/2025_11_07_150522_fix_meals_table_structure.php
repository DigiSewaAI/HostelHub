<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            // Check if old 'menu' column exists and remove it
            if (Schema::hasColumn('meals', 'menu')) {
                $table->dropColumn('menu');
            }

            // Add missing columns if they don't exist
            if (!Schema::hasColumn('meals', 'student_id')) {
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
            }

            if (!Schema::hasColumn('meals', 'hostel_id')) {
                $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            }

            if (!Schema::hasColumn('meals', 'meal_type')) {
                $table->enum('meal_type', ['breakfast', 'lunch', 'dinner'])->default('breakfast');
            }

            if (!Schema::hasColumn('meals', 'meal_date')) {
                $table->date('meal_date');
            }

            if (!Schema::hasColumn('meals', 'status')) {
                $table->enum('status', ['pending', 'served', 'missed'])->default('pending');
            }

            if (!Schema::hasColumn('meals', 'remarks')) {
                $table->text('remarks')->nullable();
            }

            // Add indexes for better performance
            $table->index(['hostel_id', 'meal_date']);
            $table->index(['student_id', 'meal_date']);
        });
    }

    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            // Reverse the changes if needed
            $table->dropForeign(['student_id']);
            $table->dropForeign(['hostel_id']);

            $table->dropColumn(['student_id', 'hostel_id', 'meal_type', 'meal_date', 'status', 'remarks']);

            // Re-add the old menu column if rolling back
            $table->string('menu')->nullable();
        });
    }
};
