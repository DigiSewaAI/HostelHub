<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            // Remove duplicate columns and keep only the correct ones
            if (Schema::hasColumn('meals', 'date')) {
                $table->dropColumn('date');
            }

            if (Schema::hasColumn('meals', 'type')) {
                $table->dropColumn('type');
            }

            // Ensure meal_date exists and is correct
            if (!Schema::hasColumn('meals', 'meal_date')) {
                $table->date('meal_date')->after('hostel_id');
            }

            // Ensure meal_type exists and is correct  
            if (!Schema::hasColumn('meals', 'meal_type')) {
                $table->enum('meal_type', ['breakfast', 'lunch', 'dinner'])->default('breakfast')->after('meal_date');
            }

            // Ensure status is correct
            if (Schema::hasColumn('meals', 'status')) {
                $table->string('status', 20)->default('pending')->change();
            }
        });
    }

    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            // For rollback - re-add the old columns
            if (!Schema::hasColumn('meals', 'date')) {
                $table->date('date')->after('hostel_id');
            }

            if (!Schema::hasColumn('meals', 'type')) {
                $table->enum('type', ['breakfast', 'lunch', 'dinner'])->default('breakfast')->after('date');
            }
        });
    }
};
