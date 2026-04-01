<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id'     => Branch::factory(),
            'restaurant_id' => function (array $attrs) {
                return Branch::find($attrs['branch_id'])->restaurant_id;
            },
            'name'          => ['en' => 'Menu', 'tr' => 'Menü'],
            'is_active'     => true,
            'sort_order'    => 0,
        ];
    }
}
