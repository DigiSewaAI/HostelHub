<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ टेबलको नाम reviews (Laravel convention अनुसार)
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->text('content');
            $table->string('initials')->nullable();
            $table->string('image')->nullable();
            $table->enum('type', ['testimonial', 'review', 'feedback'])->default('testimonial');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
