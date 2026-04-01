<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    public function definition(): array
    {
        $enTitle = fake()->sentence(3, false);
        $enTitle = rtrim($enTitle, '.');

        return [
            'title' => ['en' => $enTitle, 'tr' => fake()->sentence(3, false)],
            'slug' => Str::slug($enTitle) . '-' . fake()->unique()->numberBetween(100, 999),
            'content' => [
                'en' => '<p>' . implode('</p><p>', fake()->paragraphs(3)) . '</p>',
                'tr' => '<p>' . implode('</p><p>', fake()->paragraphs(3)) . '</p>',
            ],
            'is_published' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
