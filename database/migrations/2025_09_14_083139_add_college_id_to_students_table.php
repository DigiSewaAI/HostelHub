<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'college_id')) {
                $table->unsignedBigInteger('college_id')->nullable()->after('user_id');
                $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null');
            }

            if (!Schema::hasColumn('students', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->after('college_id');
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'college_id')) {
                $table->dropForeign(['college_id']);
                $table->dropColumn('college_id');
            }

            if (Schema::hasColumn('students', 'course_id')) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }
        });
    }
};
