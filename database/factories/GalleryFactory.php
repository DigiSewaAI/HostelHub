<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GalleryFactory extends Factory
{
    public function definition(): array
    {
        // 'is_active' हटाइएको छ, status सिधै generate गरिएको छ
        $type = fake()->randomElement(['photo', 'video', 'youtube']);

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'image' => 'gallery/sample' . rand(1, 5) . '.jpg',
            'type' => $type,
            'is_featured' => fake()->boolean(30),
            'external_link' => $type === 'youtube'
                ? 'https://www.youtube.com/watch?v=' . Str::random(11)
                : null,
            'status' => fake()->randomElement(['active', 'inactive', 'draft']),
            'category' => fake()->randomElement(['rooms', 'facilities', 'common-area']),
        ];
    }
}
