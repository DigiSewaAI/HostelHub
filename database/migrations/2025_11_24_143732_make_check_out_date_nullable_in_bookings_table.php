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
        Schema::table('bookings', function (Blueprint $table) {
            // ✅ check_out_date lai nullable banau
            $table->date('check_out_date')->nullable()->change();

            // ✅ Emergency contact field pani add garau yedi chaina bhane
            if (!Schema::hasColumn('bookings', 'emergency_contact')) {
                $table->string('emergency_contact', 15)->nullable()->after('notes');
            }

            // ✅ Organization ID pani add garau yedi chaina bhane
            if (!Schema::hasColumn('bookings', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable()->after('hostel_id');
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // ✅ Revert back to not nullable
            $table->date('check_out_date')->nullable(false)->change();

            // ✅ Remove the added columns in rollback
            if (Schema::hasColumn('bookings', 'emergency_contact')) {
                $table->dropColumn('emergency_contact');
            }

            if (Schema::hasColumn('bookings', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            }
        });
    }
};
