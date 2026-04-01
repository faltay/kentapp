<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    public function definition(): array
    {
        static $counter = 1;

        return [
            'name' => 'Table ' . $counter++,
            'capacity' => fake()->randomElement([2, 2, 4, 4, 4, 6, 8]),
            'is_active' => fake()->boolean(90),
            'sort_order' => fake()->numberBetween(1, 50),
        ];
    }

    public function active(): static
    {
        return $this->state(['is_active' => true]);
    }
}
