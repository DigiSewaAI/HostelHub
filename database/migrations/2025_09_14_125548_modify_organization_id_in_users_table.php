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
        // कुनै INSERT नगर्ने
        // कुनै UPDATE नगर्ने

        Schema::table('users', function (Blueprint $table) {
            // पहिले column छ कि छैन
            if (!Schema::hasColumn('users', 'organization_id')) {
                // नयाँ column बनाउने
                $table->foreignId('organization_id')
                    ->default(1)
                    ->constrained('organizations')
                    ->onDelete('cascade');
            } else {
                // column छ भने केहि नगर्ने
                // यो migration ले केहि नगरोस्
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // foreign key छ भने drop गर्ने try गर्ने
            $foreignKeyExists = false;
            try {
                $foreignKeyExists = DB::select("
                    SELECT 1 
                    FROM information_schema.table_constraints 
                    WHERE table_name = 'users' 
                    AND constraint_name = 'users_organization_id_foreign'
                    AND constraint_type = 'FOREIGN KEY'
                ");
            } catch (\Exception $e) {
                // ignore
            }

            if ($foreignKeyExists) {
                $table->dropForeign(['organization_id']);
            }

            // column नहटाउने, just nullable गर्ने
            $table->unsignedBigInteger('organization_id')->nullable()->change();
        });
    }
};
