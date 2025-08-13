<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // सिर्जना गर्नुहोस्: php artisan make:factory GalleryFactory --model=Gallery
        Gallery::factory()->count(10)->create();
    }
}
