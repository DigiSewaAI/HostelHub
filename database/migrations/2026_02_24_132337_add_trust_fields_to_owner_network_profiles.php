<?php
// database/migrations/2025_02_25_000001_add_trust_fields_to_owner_network_profiles.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrustFieldsToOwnerNetworkProfiles extends Migration
{
    public function up()
    {
        Schema::table('owner_network_profiles', function (Blueprint $table) {
            $table->string('trust_level')->default('normal')->after('verified_at')->comment('normal, verified, trusted, suspended');
            $table->timestamp('suspended_at')->nullable()->after('trust_level');
        });
    }

    public function down()
    {
        Schema::table('owner_network_profiles', function (Blueprint $table) {
            $table->dropColumn(['trust_level', 'suspended_at']);
        });
    }
}
