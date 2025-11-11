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
        Schema::table('contacts', function (Blueprint $table) {
            // Add hostel_id and room_id columns
            $table->unsignedBigInteger('hostel_id')->nullable()->after('message');
            $table->unsignedBigInteger('room_id')->nullable()->after('hostel_id');

            // Add foreign key constraints
            $table->foreign('hostel_id')
                ->references('id')
                ->on('hostels')
                ->onDelete('set null');

            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('set null');

            // Add index for better performance
            $table->index(['hostel_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['hostel_id']);
            $table->dropForeign(['room_id']);

            // Drop indexes
            $table->dropIndex(['hostel_id', 'room_id']);

            // Drop columns
            $table->dropColumn(['hostel_id', 'room_id']);
        });
    }
};
