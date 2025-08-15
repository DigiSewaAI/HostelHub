<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'hostels',
            'rooms',
            'room_types',
            'students',
            'invoices',
            'gallery'
        ];

        foreach ($tables as $tableName) {
            // table छ कि छैन? अनि column छैन भने मात्र add
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'org_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('org_id')->nullable()->after('id');
                    $table->index('org_id');
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'hostels',
            'rooms',
            'room_types',
            'students',
            'invoices',
            'gallery'
        ];

        foreach ($tables as $tableName) {
            // table छ र column पनि छ भने मात्र हटाउने
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'org_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    // default index name drop गर्न array syntax सजिलो हुन्छ
                    $table->dropIndex(['org_id']);
                    $table->dropColumn('org_id');
                });
            }
        }
    }
};
