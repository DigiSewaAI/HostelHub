<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            if (Schema::hasColumn('hostels', 'org_id')) {
                $table->renameColumn('org_id', 'organization_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            if (Schema::hasColumn('hostels', 'organization_id')) {
                $table->renameColumn('organization_id', 'org_id');
            }
        });
    }
};
