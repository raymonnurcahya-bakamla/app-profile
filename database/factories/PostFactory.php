<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => str($title)->slug(),
            'summary' => fake()->paragraph(),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(4)).'</p>',
            'image' => null,
            'status' => 'published',
            'published_at' => now(),
            'view_count' => fake()->numberBetween(10, 1000),
            'is_featured' => fake()->boolean(20),
        ];
    }
}
