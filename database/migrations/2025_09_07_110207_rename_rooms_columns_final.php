<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Foreign key checks लाई temporary disable गर्ने
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Rooms table का कलमहरूलाई नेपालीबाट अंग्रेजीमा रename गर्ने
        if (Schema::hasTable('rooms')) {
            if (Schema::hasColumn('rooms', 'कोठा_नम्बर')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('कोठा_नम्बर', 'room_number');
                });
            }

            if (Schema::hasColumn('rooms', 'मूल्य')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('मूल्य', 'price');
                });
            }

            if (Schema::hasColumn('rooms', 'क्षमता')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('क्षमता', 'capacity');
                });
            }

            if (Schema::hasColumn('rooms', 'प्रकार')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('प्रकार', 'type');
                });
            }

            if (Schema::hasColumn('rooms', 'स्थिति')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('स्थिति', 'status');
                });
            }

            if (Schema::hasColumn('rooms', 'तस्वीर')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('तस्वीर', 'image');
                });
            }

            if (Schema::hasColumn('rooms', 'विवरण')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->renameColumn('विवरण', 'description');
                });
            }
        }

        // Hostels table मा owner_id कलम थप्ने (यदि छैन भने)
        if (Schema::hasTable('hostels') && !Schema::hasColumn('hostels', 'owner_id')) {
            Schema::table('hostels', function (Blueprint $table) {
                $table->foreignId('owner_id')->nullable()->after('id')->constrained('users');
            });
        }

        // Foreign key checks लाई फेरि enable गर्ने
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // यो migration लाई reverse गर्न गार्हो छ, त्यसैले down method लाई खाली छोड्नुहोस्
        // यदि rollback गर्नु पर्यो भने, database लाई backup बाट restore गर्नुहोस्
    }
};
