<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => rtrim(fake()->sentence(5), '.'),
            'body'  => implode("\n\n", fake()->paragraphs(3)),
            'image' => null,
        ];
    }
}
