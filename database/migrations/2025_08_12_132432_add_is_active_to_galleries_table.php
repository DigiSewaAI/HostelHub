<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // सिधै is_active कलम थप्नुहोस् (सबै प्रकारका टेबल संरचनासँग संगत)
            if (!Schema::hasColumn('galleries', 'is_active')) {
                // description कलम पछि थप्ने (यदि छ भने)
                if (Schema::hasColumn('galleries', 'description')) {
                    $table->boolean('is_active')->default(true)->after('description');
                }
                // अन्यथा अन्त्यमा थप्ने
                else {
                    $table->boolean('is_active')->default(true);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
}
