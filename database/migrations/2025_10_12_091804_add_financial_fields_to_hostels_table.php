<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->decimal('monthly_rent', 10, 2)->default(0)->after('available_rooms');
            $table->decimal('security_deposit', 10, 2)->default(0)->after('monthly_rent');
            $table->string('image')->nullable()->after('security_deposit');
        });
    }

    public function down(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropColumn(['monthly_rent', 'security_deposit', 'image']);
        });
    }
};
