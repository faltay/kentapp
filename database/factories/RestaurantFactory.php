<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();
        $slug = Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999);

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->sentence(12),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
