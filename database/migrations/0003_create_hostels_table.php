<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('address');
            $table->string('city');
            $table->string('contact_person');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->text('description')->nullable();
            $table->integer('total_rooms')->default(0);
            $table->integer('available_rooms')->default(0);
            $table->json('facilities')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hostels');
    }
};
