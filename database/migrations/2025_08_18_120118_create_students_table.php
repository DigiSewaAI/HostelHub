<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('org_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('college_id')->nullable()->constrained('colleges')->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('set null');

            $table->string('name', 100);
            $table->string('phone', 15);
            $table->string('email', 100)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('dob')->nullable();
            $table->text('address')->nullable();

            $table->string('guardian_name', 100);
            $table->string('guardian_phone', 15);
            $table->string('guardian_relation', 50);
            $table->text('guardian_address')->nullable();

            $table->string('academic_year', 10)->nullable();
            $table->string('semester', 20)->nullable();
            $table->date('admission_date');
            $table->enum('status', ['Active', 'Left', 'Suspended', 'Blocked'])->default('Active');

            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
