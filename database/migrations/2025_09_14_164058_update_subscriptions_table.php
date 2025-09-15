<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the foreign key exists before trying to drop it
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'subscriptions' 
            AND COLUMN_NAME = 'user_id' 
            AND CONSTRAINT_NAME <> 'PRIMARY'
        ");

        Schema::table('subscriptions', function (Blueprint $table) use ($foreignKeys) {
            // Drop foreign key only if it exists
            if (count($foreignKeys) > 0) {
                $table->dropForeign([$foreignKeys[0]->CONSTRAINT_NAME]);
            }

            // Make user_id nullable
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Revert back to not nullable
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
