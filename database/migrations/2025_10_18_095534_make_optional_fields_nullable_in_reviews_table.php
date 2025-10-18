<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeOptionalFieldsNullableInReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Make fields nullable that are not essential for student reviews
            $table->string('name')->nullable()->change();
            $table->string('position')->nullable()->change();
            $table->text('content')->nullable()->change();
            $table->string('initials')->nullable()->change();
            $table->string('image')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('position')->nullable(false)->change();
            $table->text('content')->nullable(false)->change();
            $table->string('initials')->nullable(false)->change();
            $table->string('image')->nullable(false)->change();
        });
    }
}
