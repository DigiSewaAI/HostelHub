<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->index(['city', 'is_published', 'status']);
            $table->index(['is_published', 'status']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->index(['status', 'available_beds']);
            $table->index(['hostel_id', 'status', 'available_beds']);
        });
    }

    public function down()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropIndex(['city', 'is_published', 'status']);
            $table->dropIndex(['is_published', 'status']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex(['status', 'available_beds']);
            $table->dropIndex(['hostel_id', 'status', 'available_beds']);
        });
    }
};
