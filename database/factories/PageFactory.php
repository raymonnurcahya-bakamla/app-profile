<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'user_id' => User::factory(),
            'title' => ucwords($title),
            'slug' => str($title)->slug(),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>',
            'image' => null,
            'status' => 'published',
            'sort_order' => fake()->numberBetween(1, 10),
            'show_in_menu' => true,
        ];
    }
}
