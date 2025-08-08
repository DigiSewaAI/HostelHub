<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->softDeletes(); // यो लाइन थप्नुहोस्
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropSoftDeletes(); // deleted_at हटाउँछ
        });
    }
}
