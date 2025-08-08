<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToCollegesTable extends Migration
{
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->softDeletes(); // यो लाइन थप्नुहोस् (deleted_at कलम बनाउँछ)
        });
    }

    public function down()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropSoftDeletes(); // deleted_at कलम हटाउँछ
        });
    }
}
