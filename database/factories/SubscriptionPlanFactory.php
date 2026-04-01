<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPlan>
 */
class SubscriptionPlanFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Starter', 'Basic', 'Pro', 'Enterprise', 'Premium']);
        $price = fake()->randomFloat(2, 0, 99);

        return [
            'name' => ['en' => $name, 'tr' => $name],
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'description' => ['en' => fake()->sentence(), 'tr' => fake()->sentence()],
            'prices' => $price > 0 ? ['USD' => ['monthly' => $price]] : [],
            'max_restaurants' => 1,
            'max_branches' => 3,
            'features' => ['en' => ['QR Code Generation'], 'tr' => ['QR Kod Oluşturma']],
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    public function free(): static
    {
        return $this->state([
            'slug' => 'free-' . fake()->unique()->numberBetween(100, 999),
            'prices' => [],
        ]);
    }

    public function unlimited(): static
    {
        return $this->state([
            'max_restaurants' => -1,
            'max_branches' => -1,
        ]);
    }
}
