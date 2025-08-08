<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDurationNullableInCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('duration')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('duration')->change(); // NOT NULL फेरि बनाउँछ
        });
    }
}
