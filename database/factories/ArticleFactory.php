<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ArticleStatus;
use App\Models\User;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'content' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->imageUrl(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(ArticleStatus::cases()),
        ];
    }
}
