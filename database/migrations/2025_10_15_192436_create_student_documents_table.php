<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->enum('document_type', ['id_card', 'academic', 'medical', 'financial', 'contract', 'other']);
            $table->string('original_name');
            $table->string('stored_path');
            $table->string('file_size');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index(['organization_id', 'student_id']);
            $table->index(['organization_id', 'document_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_documents');
    }
};
