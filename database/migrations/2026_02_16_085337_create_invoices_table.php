<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('hostel_id');
            $table->date('billing_month');                 // महिनाको पहिलो दिन, जस्तै 2026-03-01
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
            $table->index(['student_id', 'billing_month']); // द्रुत खोजीको लागि
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};