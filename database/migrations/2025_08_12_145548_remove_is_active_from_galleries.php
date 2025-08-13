<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // migration file
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });
    }
};
