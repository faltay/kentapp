<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->city() . ' Branch';

        return [
            'restaurant_id' => Restaurant::factory(),
            'name'          => ['en' => $name, 'tr' => $name],
            'slug'          => Str::slug($name),
            'is_active'     => true,
            'is_main'       => true,
            'sort_order'    => 0,
        ];
    }
}
