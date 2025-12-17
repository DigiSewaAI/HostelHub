<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // FIRST: Check if the user_id column actually exists
        if (!Schema::hasColumn('subscriptions', 'user_id')) {
            // If the column doesn't exist, we should create it.
            // You need to decide if this should be nullable from the start.
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->after('id');
            });
            return; // Exit early since we've created the column
        }

        // SECOND: If the column DOES exist, check for foreign key
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'subscriptions' 
            AND COLUMN_NAME = 'user_id' 
            AND CONSTRAINT_NAME <> 'PRIMARY'
        ");

        Schema::table('subscriptions', function (Blueprint $table) use ($foreignKeys) {
            // Drop foreign key only if it exists
            if (count($foreignKeys) > 0) {
                $table->dropForeign([$foreignKeys[0]->CONSTRAINT_NAME]);
            }

            // Make user_id nullable - safe because we checked it exists
            $table->foreignId('user_id')->nullable()->change();

            // Optionally, re-add the foreign key constraint if needed
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        // Only try to revert if the column exists
        if (Schema::hasColumn('subscriptions', 'user_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }
    }
};
