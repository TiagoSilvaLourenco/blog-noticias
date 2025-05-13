<?php

namespace Database\Factories;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
   protected $model = Ad::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->company,
            'image' => $this->faker->imageUrl(300, 250, 'business', true),
            'html_code' => null,
            'type' => 'image',
            'link' => $this->faker->url,
            'position' => $this->faker->randomElement(['sidebar_top', 'sidebar_bottom', 'footer']),
            'post_id' => null, // ou associe depois manualmente
            'is_active' => true,
            'start_at' => now()->subDays(5),
            'end_at' => now()->addDays(30),
        ];
    }
}
