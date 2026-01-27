<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // यदि floor column छैन भने थप्नुहोस्
            if (!Schema::hasColumn('rooms', 'floor')) {
                $table->string('floor')->nullable()->after('gallery_category');
            }

            // Note: image_url column थप्नुपर्दैन किनकि यो accessor हो
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Optionally drop the columns if needed
            $table->dropColumn(['floor']);
        });
    }
};
