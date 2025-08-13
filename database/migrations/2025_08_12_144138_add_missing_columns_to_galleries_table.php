<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // केवल ती कलमहरू थप्ने जुन टेबलमा छैनन्
            if (!Schema::hasColumn('galleries', 'type')) {
                $table->string('type')->nullable()->after('description');
            }

            if (!Schema::hasColumn('galleries', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('type');
            }

            if (!Schema::hasColumn('galleries', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }

            if (!Schema::hasColumn('galleries', 'external_link')) {
                $table->string('external_link')->nullable()->after('is_featured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_active', 'is_featured', 'external_link']);
        });
    }
};
