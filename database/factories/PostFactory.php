<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => Str::ulid(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            // 'created_by' => Str::ulid(),
            // 'updated_by' => Str::ulid(),
            'status' => $this->faker->randomElement([\App\Enums\PostStatus::Draft, \App\Enums\PostStatus::Published]),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
