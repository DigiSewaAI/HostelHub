<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowHostelhubBrandingToHostelsTable extends Migration
{
    public function up()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->boolean('show_hostelhub_branding')->default(true);
        });
    }

    public function down()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropColumn('show_hostelhub_branding');
        });
    }
}
