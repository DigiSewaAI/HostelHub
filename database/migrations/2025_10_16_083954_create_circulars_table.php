<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCircularsTable extends Migration
{
    public function up()
    {
        Schema::create('circulars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('hostel_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('priority', ['urgent', 'normal', 'info'])->default('normal');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('audience_type', [
                'all_students',
                'all_managers',
                'all_users',
                'organization_students',
                'organization_managers',
                'organization_users',
                'specific_hostel',
                'specific_students'
            ]);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('target_audience')->nullable(); // For specific students/hostels
            $table->timestamps();

            // Indexes for performance
            $table->index(['organization_id', 'status']);
            $table->index(['created_by', 'status']);
            $table->index(['published_at', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('circulars');
    }
}
