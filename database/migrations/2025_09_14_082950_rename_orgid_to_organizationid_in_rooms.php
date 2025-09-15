<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'org_id')) {
                // org_id भए rename गर
                $table->renameColumn('org_id', 'organization_id');
            } else if (!Schema::hasColumn('rooms', 'organization_id')) {
                // दुवै छैन भने नयाँ create गर
                $table->unsignedBigInteger('organization_id')->nullable()->after('hostel_id');
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->renameColumn('organization_id', 'org_id');
            }
        });
    }
};
