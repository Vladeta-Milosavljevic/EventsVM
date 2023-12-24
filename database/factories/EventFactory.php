<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => rand(1,3),
            'category_id' => rand(1,4),
            'name' => fake()->sentence(2),
            'tags' => fake()->sentence(3),
            'description' => fake()->paragraph(5),
            'image' => fake()->imageUrl(1920, 1080, 'event'),
        ];
    }
}
