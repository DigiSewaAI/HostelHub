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
        Schema::table('hostels', function (Blueprint $table) {
            $table->string('city')->nullable()->change();
            $table->string('contact_person')->nullable()->change();
            $table->string('contact_phone')->nullable()->change();
            $table->string('contact_email')->nullable()->change();
            $table->integer('total_rooms')->nullable()->change();
            $table->integer('available_rooms')->nullable()->change();
            $table->text('facilities')->nullable()->change();
            $table->foreignId('manager_id')->nullable()->change();
            $table->foreignId('org_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('hostels', function (Blueprint $table) {
            // Rollback logic
        });
    }
};
