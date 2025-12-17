<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployDatabase extends Command
{
    protected $signature = 'deploy:database {--seed : Run seeders with basic data}';
    protected $description = 'Run database migrations and optionally seed basic data';

    public function handle()
    {
        $this->info('Running database migrations...');
        Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
        $this->info('✓ Migrations completed.');

        if ($this->option('seed')) {
            $this->info('Seeding basic data...');
            Artisan::call('db:seed', [
                '--class' => 'DatabaseSeeder',
                '--force' => true,
                '--no-interaction' => true
            ]);
            $this->info('✓ Basic data seeded.');
        }

        $this->info('✓ Database deployment completed successfully!');
        
        if ($this->option('seed')) {
            $this->line("\n🔑 Default Login Credentials:");
            $this->line("   Admin: parasharregmi@gmail.com / Himalayan@1980");
            $this->line("   Manager: regmiashish629@gmail.com / Himalayan@1980");
            $this->line("   Student: shresthaxok@gmail.com / Himalayan@1980");
            $this->line("   Test Student: student@hostelhub.com / password123");
        }
    }
}