<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('marketplace_listings', function (Blueprint $table) {
            // 1. visibility (पहिले देखि छ भने नथप्ने)
            if (!Schema::hasColumn('marketplace_listings', 'visibility')) {
                $table->enum('visibility', ['private', 'public', 'both'])
                    ->default('private')
                    ->after('status');
            }

            // 2. category_id (पहिले देखि छ भने नथप्ने)
            if (!Schema::hasColumn('marketplace_listings', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('title');
            }

            // 3. condition (नभएको भए मात्र थप्ने)
            if (!Schema::hasColumn('marketplace_listings', 'condition')) {
                $table->enum('condition', ['new', 'used'])->nullable()->after('description');
            }

            // 4. quantity (नभएको भए मात्र थप्ने)
            if (!Schema::hasColumn('marketplace_listings', 'quantity')) {
                $table->integer('quantity')->default(1)->after('condition');
            }

            // 5. price_type (नभएको भए मात्र थप्ने)
            if (!Schema::hasColumn('marketplace_listings', 'price_type')) {
                $table->enum('price_type', ['fixed', 'negotiable'])->default('fixed')->after('price');
            }
        });

        // अब category_id मा foreign key छ कि छैन जाँच गरौं र नभएको भए मात्र थपौं
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'marketplace_listings'
              AND COLUMN_NAME = 'category_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (empty($foreignKeys) && Schema::hasColumn('marketplace_listings', 'category_id')) {
            Schema::table('marketplace_listings', function (Blueprint $table) {
                $table->foreign('category_id')
                    ->references('id')
                    ->on('marketplace_categories')
                    ->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::table('marketplace_listings', function (Blueprint $table) {
            // down मा foreign key पहिले drop गर्ने
            if (Schema::hasColumn('marketplace_listings', 'category_id')) {
                try {
                    $table->dropForeign(['category_id']);
                } catch (\Exception $e) {
                    // foreign key नभए केही गर्नु पर्दैन
                }
            }

            // अब columns ड्रप गर्ने (यदि छन् भने मात्र)
            $columns = ['visibility', 'category_id', 'condition', 'quantity', 'price_type'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('marketplace_listings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
