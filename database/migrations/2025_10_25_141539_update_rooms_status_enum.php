<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Option 1: Change to VARCHAR to accept any status
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('status', 50)->default('उपलब्ध')->change();
        });

        // Option 2: Update ENUM to include new status (if you prefer ENUM)
        // DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('उपलब्ध', 'व्यस्त', 'मर्मत सम्भार', 'आंशिक उपलब्ध') DEFAULT 'उपलब्ध'");
    }

    public function down()
    {
        // Revert back to original ENUM if needed
        DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('available','occupied','maintenance') DEFAULT 'available'");
    }
};
