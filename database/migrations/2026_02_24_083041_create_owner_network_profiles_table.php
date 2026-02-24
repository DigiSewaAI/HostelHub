<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('owner_network_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->json('auto_snapshot');        // cached snapshot of hostel data
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique('hostel_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('owner_network_profiles');
    }
};
