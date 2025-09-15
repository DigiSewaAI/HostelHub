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
        // Step 1: Default organization ensure गर्ने
        if (!DB::table('organizations')->where('id', 1)->exists()) {
            DB::table('organizations')->insert([
                'id' => 1,
                'name' => 'Default Organization',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Step 2: NULL users fix गर्ने
        DB::table('users')->whereNull('organization_id')->update(['organization_id' => 1]);

        // Step 3: अब modify गर्ने
        Schema::table('users', function (Blueprint $table) {
            // पहिले foreign key छैन भने add गर्ने
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->foreignId('organization_id')
                    ->constrained()
                    ->onDelete('cascade');
            } else {
                // just modify existing column
                $table->unsignedBigInteger('organization_id')->nullable(false)->change();

                // fresh foreign key constraint add गर्ने
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // foreign key हटाउने
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('users');
            if ($doctrineTable->hasForeignKey('users_organization_id_foreign')) {
                $table->dropForeign('users_organization_id_foreign');
            }

            // column लाई nullable फर्काउने
            $table->unsignedBigInteger('organization_id')->nullable()->change();
        });
    }
};
