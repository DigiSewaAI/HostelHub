<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCircularRecipientsTable extends Migration
{
    public function up()
    {
        Schema::create('circular_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circular_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('user_type', ['student', 'hostel_manager']);
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['circular_id', 'user_id']);

            // Indexes for performance
            $table->index(['user_id', 'is_read']);
            $table->index(['circular_id', 'is_read']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('circular_recipients');
    }
}
