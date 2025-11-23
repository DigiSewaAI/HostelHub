<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // ✅ FIXED: student_id को सट्टा guest_email पछि email column थप्ने
            if (!Schema::hasColumn('bookings', 'email')) {
                $table->string('email')->nullable()->after('guest_email');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
