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
        Schema::table('organization_users', function (Blueprint $table) {
            $table->string('role')->default('member'); // default role set
        });
    }

    public function down()
    {
        Schema::table('organization_users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
