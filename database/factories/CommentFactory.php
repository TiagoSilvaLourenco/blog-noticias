<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'post_id' => \App\Models\Post::factory(),
            'user_name' => $this->faker->name,
            'comment' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['approved', 'pending', 'rejected']),
        ];
    }
}
