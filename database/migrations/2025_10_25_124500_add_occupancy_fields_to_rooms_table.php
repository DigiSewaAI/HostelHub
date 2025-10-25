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
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('current_occupancy')->default(0)->after('capacity');
            $table->integer('available_beds')->default(0)->after('current_occupancy');
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['current_occupancy', 'available_beds']);
        });
    }
};
