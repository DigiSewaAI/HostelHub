<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->string('facebook_url')->nullable()->after('theme_color');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('tiktok_url')->nullable()->after('twitter_url');
            $table->string('whatsapp_number')->nullable()->after('tiktok_url');
            $table->string('youtube_url')->nullable()->after('whatsapp_number');
            $table->string('linkedin_url')->nullable()->after('youtube_url');
        });
    }

    public function down()
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'tiktok_url',
                'whatsapp_number',
                'youtube_url',
                'linkedin_url'
            ]);
        });
    }
};
