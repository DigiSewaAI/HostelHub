<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
            $table->integer('featured_order')->default(0)->after('is_featured');
            $table->decimal('commission_rate', 5, 2)->default(10.00)->after('featured_order');
            $table->decimal('extra_commission', 8, 2)->default(0.00)->after('commission_rate');
        });
    }

    public function down()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'featured_order', 'commission_rate', 'extra_commission']);
        });
    }
};
