<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
   protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->sentence;
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph,
            'content' => $this->faker->paragraphs(3, true),
            'cover' => $this->faker->imageUrl(800, 600, 'nature', true),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'published_at' => now(),
            'is_featured' => $this->faker->boolean(30),
            'comments_enabled' => true,
            'comments_count' => 0,
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}
