<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('hostel_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('pending')->change(); // pending, approved, rejected, cancelled
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Add missing columns if they don't exist
            if (!Schema::hasColumn('bookings', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['hostel_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['hostel_id', 'approved_by', 'approved_at', 'rejection_reason']);
        });
    }
};
