<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meal_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->string('meal_type'); // breakfast, lunch, dinner
            $table->string('day_of_week'); // sunday, monday...
            $table->json('items'); // ["Dal", "Bhat", "Tarkari"]
            $table->string('image')->nullable(); // meal photo
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('meal_menus', function (Blueprint $table) {
            $table->index(['hostel_id', 'meal_type', 'day_of_week']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('meal_menus');
    }
};
