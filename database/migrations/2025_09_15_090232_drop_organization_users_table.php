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
        Schema::dropIfExists('organization_users');
    }

    public function down()
    {
        // Optional: undo garna chai table feri create garna sakinchha
        Schema::create('organization_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('role')->default('member');
            $table->timestamps();
        });
    }
};
