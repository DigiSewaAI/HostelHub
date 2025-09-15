<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // पहिले existing Cashier-related columns हरू drop गर्ने
            if (Schema::hasColumn('subscriptions', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('subscriptions', 'stripe_id')) {
                $table->dropColumn('stripe_id');
            }
            if (Schema::hasColumn('subscriptions', 'stripe_status')) {
                $table->dropColumn('stripe_status');
            }
            if (Schema::hasColumn('subscriptions', 'stripe_price')) {
                $table->dropColumn('stripe_price');
            }
            if (Schema::hasColumn('subscriptions', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('subscriptions', 'ends_at')) {
                $table->dropColumn('ends_at');
            }

            // नयाँ columns थप्ने
            if (!Schema::hasColumn('subscriptions', 'organization_id')) {
                $table->foreignId('organization_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('subscriptions', 'plan_id')) {
                $table->foreignId('plan_id')->after('organization_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('subscriptions', 'renews_at')) {
                $table->datetime('renews_at')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // नयाँ columns हटाउने
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['organization_id', 'plan_id', 'status', 'renews_at', 'notes']);

            // मूल columns फिर्ता थप्ने
            $table->string('type')->nullable();
            $table->string('stripe_id')->nullable();
            $table->string('stripe_status')->nullable();
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('ends_at')->nullable();
        });
    }
};
