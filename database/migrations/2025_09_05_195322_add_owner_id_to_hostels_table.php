<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('hostels') && !Schema::hasColumn('hostels', 'owner_id')) {
            Schema::table('hostels', function (Blueprint $table) {
                // Add owner_id column
                $table->foreignId('owner_id')
                    ->after('id')
                    ->constrained('users')
                    ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('hostels') && Schema::hasColumn('hostels', 'owner_id')) {
            Schema::table('hostels', function (Blueprint $table) {
                // Drop foreign key first
                $table->dropForeign(['owner_id']);
                // Then drop the column
                $table->dropColumn('owner_id');
            });
        }
    }
};
