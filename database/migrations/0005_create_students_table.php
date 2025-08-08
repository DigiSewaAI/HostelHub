<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->date('dob');
            $table->string('gender');
            $table->string('guardian_name');
            $table->string('guardian_phone');
            $table->string('guardian_relation');
            $table->string('guardian_address');

            // Foreign key references
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('year');
            $table->string('semester');
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            $table->date('admission_date');
            $table->string('status')->default('active');

            // Optional text fields if you still want direct names
            $table->string('college')->nullable();
            $table->string('course')->nullable();

            // Created_at & Updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
