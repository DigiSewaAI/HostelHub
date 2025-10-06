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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('hostel_id')->after('id')->constrained()->onDelete('cascade');
            $table->text('owner_reply')->nullable()->after('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('owner_reply');
            $table->index(['hostel_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['hostel_id']);
            $table->dropColumn(['hostel_id', 'owner_reply', 'status']);
        });
    }
};
