<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('message');
            }
        });
    }

    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};
