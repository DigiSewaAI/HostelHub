<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // पोलिमोर्फिक सम्बन्धका लागि कलमहरू
            $table->string('reviewable_type')->nullable()->after('type');
            $table->unsignedBigInteger('reviewable_id')->nullable()->after('reviewable_type');
            // प्लेटफर्म समीक्षाको लागि इमेल (वैकल्पिक)
            $table->string('email')->nullable()->after('comment');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['reviewable_type', 'reviewable_id', 'email']);
        });
    }
};
