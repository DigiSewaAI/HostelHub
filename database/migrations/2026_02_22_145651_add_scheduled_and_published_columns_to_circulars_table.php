<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('circulars', function (Blueprint $table) {
            // scheduled_at थप्ने (यदि छैन भने मात्र)
            if (!Schema::hasColumn('circulars', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('status');
            }
            // published_at थप्ने (यदि छैन भने मात्र)
            if (!Schema::hasColumn('circulars', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('scheduled_at');
            }
        });
    }

    public function down()
    {
        Schema::table('circulars', function (Blueprint $table) {
            $table->dropColumn(['scheduled_at', 'published_at']);
        });
    }
};
