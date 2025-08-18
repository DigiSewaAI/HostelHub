<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create roles table if it doesn't exist
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });

            // Insert default roles
            DB::table('roles')->insert([
                ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'hostel_manager', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'student', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. Add role_id column to users table with default value
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(3)->after('id'); // Default to student role
        });

        // 3. Map existing string roles to role_id
        $roles = DB::table('roles')->pluck('id', 'name')->toArray();

        if (isset($roles['admin'])) {
            DB::table('users')->where('role', 'admin')->update(['role_id' => $roles['admin']]);
        }
        if (isset($roles['student'])) {
            DB::table('users')->where('role', 'student')->update(['role_id' => $roles['student']]);
        }
        if (isset($roles['hostel_manager'])) {
            DB::table('users')->where('role', 'hostel_manager')->update(['role_id' => $roles['hostel_manager']]);
        }

        // 4. Make role_id required
        // Note: Requires doctrine/dbal package for column modification
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable(false)->default(3)->change();
        });

        // 5. Add foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });

        // 6. Drop the old role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Restore the old role column
            $table->string('role')->default('student')->after('id');

            // 2. Drop foreign key constraint first
            $table->dropForeign(['role_id']);
        });

        // 3. Map role_id back to role string
        $roles = DB::table('roles')->pluck('name', 'id')->toArray();

        foreach ($roles as $id => $name) {
            DB::table('users')->where('role_id', $id)->update(['role' => $name]);
        }

        Schema::table('users', function (Blueprint $table) {
            // 4. Drop role_id column
            $table->dropColumn('role_id');
        });

        // 5. Drop roles table
        Schema::dropIfExists('roles');
    }
};
