<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Check if the column 'org_id' exists before trying to rename it
        if (Schema::hasColumn('subscriptions', 'org_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->renameColumn('org_id', 'organization_id');
            });
        }
        // If 'org_id' doesn't exist, but 'organization_id' already does, do nothing.
    }

    public function down(): void
    {
        // Check if the column 'organization_id' exists before trying to rename it back
        if (Schema::hasColumn('subscriptions', 'organization_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->renameColumn('organization_id', 'org_id');
            });
        }
        // If 'organization_id' doesn't exist, but 'org_id' already does, do nothing.
    }
};
