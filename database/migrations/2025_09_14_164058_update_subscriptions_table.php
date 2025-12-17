<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // CASE 1: Column doesn't exist -> Create it
        if (!Schema::hasColumn('subscriptions', 'user_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()  // Automatically references 'users.id'
                    ->onDelete('cascade')
                    ->after('id');
            });
            return; // Stop here. Column is created as nullable. Perfect.
        }

        // CASE 2: Column exists -> Ensure it's nullable
        Schema::table('subscriptions', function (Blueprint $table) {
            // Simply change the column to be nullable.
            // The ->change() method will preserve any existing foreign key.
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Only act if the column exists
        if (Schema::hasColumn('subscriptions', 'user_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                // Make it NOT NULL again
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        }
    }
};
