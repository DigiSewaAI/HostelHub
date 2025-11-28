<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixRoomOccupancyCalculation extends Migration
{
    public function up()
    {
        // Add index for better performance
        Schema::table('rooms', function (Blueprint $table) {
            $table->index(['hostel_id', 'status']);
            $table->index(['type', 'status']);
        });

        // Create a command to sync all room occupancy data
        Artisan::call('db:seed', [
            '--class' => 'RoomOccupancySyncSeeder',
            '--force' => true
        ]);
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex(['hostel_id', 'status']);
            $table->dropIndex(['type', 'status']);
        });
    }
}
