<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // users को लागि मात्र फरेन कि थप्नुहोस्
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
        });

        // students को लागि फरेन कि हटाउनुहोस् (यो अर्को फाइलमा छ)
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
    }
};
