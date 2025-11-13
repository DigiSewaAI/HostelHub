<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('document_type');
            $table->string('title');
            $table->string('document_number')->nullable();
            $table->string('original_name');
            $table->string('stored_path');
            $table->integer('file_size');
            $table->string('mime_type');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamps();

            $table->index(['student_id', 'document_type']);
            $table->index(['hostel_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
