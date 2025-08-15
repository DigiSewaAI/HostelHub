<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateGalleryCategories extends Migration
{
    public function up()
    {
        DB::table('galleries')->update([
            'category' => DB::raw("
                CASE 
                    WHEN category = 'facilities' THEN 'bathroom'
                    WHEN category = 'hostel' THEN 'common'
                    WHEN category = 'room' THEN '1 seater'
                    WHEN category = 'common-area' THEN 'common'
                    ELSE category
                END
            ")
        ]);
    }

    public function down()
    {
        DB::table('galleries')->update([
            'category' => DB::raw("
                CASE 
                    WHEN category = 'bathroom' THEN 'facilities'
                    WHEN category = 'common' THEN 'common-area'
                    WHEN category = '1 seater' THEN 'room'
                    ELSE category
                END
            ")
        ]);
    }
}
