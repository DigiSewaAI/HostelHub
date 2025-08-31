<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            // image कलम हटाउनुहोस्
            $table->dropColumn('image');
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Rollback को लागि फेरी image कलम थप्नुहोस्
            $table->string('image')->nullable()->after('title');
        });
    }
};