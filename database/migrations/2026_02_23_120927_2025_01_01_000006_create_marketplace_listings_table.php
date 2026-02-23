<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['sale', 'lease', 'partnership', 'investment']);
            $table->decimal('price', 15, 2)->nullable(); // बिक्री/भाडाको लागि
            $table->string('location')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold', 'closed'])->default('pending');
            $table->timestamp('moderated_at')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users');
            $table->text('moderation_notes')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_listings');
    }
};
