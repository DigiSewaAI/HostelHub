<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('hostel_count')->default(0)->after('plan_id');
            $table->integer('extra_hostels')->default(0)->after('hostel_count');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['hostel_count', 'extra_hostels']);
        });
    }
};
