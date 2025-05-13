<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(5)->create();
        Tag::factory(10)->create();
        User::factory(5)->create();

        Post::factory(20)->create()->each(function ($post) {
            $tags = \App\Models\Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $post->tags()->attach($tags);
        });

        Comment::factory(40)->create();
        Ad::factory(5)->create();
    }
}
