<?php
// database/migrations/2025_02_25_000002_add_moderation_to_broadcast_messages.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModerationToBroadcastMessages extends Migration
{
    public function up()
    {
        Schema::table('broadcast_messages', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('broadcast_messages', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'rejected_reason']);
        });
    }
}
