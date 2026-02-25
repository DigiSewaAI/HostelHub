<?php
// database/migrations/2025_02_25_000003_add_moderation_to_marketplace_listings.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModerationToMarketplaceListings extends Migration
{
    public function up()
    {
        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'rejected_reason']);
        });
    }
}
