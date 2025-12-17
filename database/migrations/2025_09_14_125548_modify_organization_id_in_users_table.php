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
        // if (!DB::table('organizations')->where('id', 1)->exists()) {
        //     DB::table('organizations')->insert([
        //         'id' => 1,
        //         'name' => 'Default Organization',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Step 2: अहिले नै column छ कि छैन भनेर जाँच गर्ने
        if (!Schema::hasColumn('users', 'organization_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('organization_id')
                    ->default(1)  // default value set गर्ने
                    ->constrained()
                    ->onDelete('cascade');
            });
        } else {
            // Step 3: NULL users लाई default organization मा set गर्ने
            DB::table('users')->whereNull('organization_id')->update(['organization_id' => 1]);

            // Step 4: existing column लाई modify गर्ने
            Schema::table('users', function (Blueprint $table) {
                // foreign key हटाउनुहोस् (यदि छ भने)
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails('users');
                $foreignKeys = $doctrineTable->getForeignKeys();

                foreach ($foreignKeys as $constraint) {
                    if (in_array('organization_id', $constraint->getColumns())) {
                        $table->dropForeign([$constraint->getName()]);
                        break;
                    }
                }

                // column modify गर्ने
                $table->unsignedBigInteger('organization_id')
                    ->nullable(false)
                    ->default(1)
                    ->change();

                // नयाँ foreign key add गर्ने
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
            });
        }
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
            $foreignKeys = $doctrineTable->getForeignKeys();

            foreach ($foreignKeys as $constraint) {
                if (in_array('organization_id', $constraint->getColumns())) {
                    $table->dropForeign([$constraint->getName()]);
                    break;
                }
            }

            // column लाई nullable फर्काउने
            $table->unsignedBigInteger('organization_id')
                ->nullable()
                ->default(null)
                ->change();
        });

        // Optional: Default organization हटाउने यदि तपाईं चाहनुहुन्छ भने
        // DB::table('organizations')->where('id', 1)->delete();
    }
};
