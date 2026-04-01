<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $enTitle = fake()->sentence(5, false);
        $enTitle = rtrim($enTitle, '.');

        return [
            'title' => ['en' => $enTitle, 'tr' => fake()->sentence(5, false)],
            'slug' => Str::slug($enTitle) . '-' . fake()->unique()->numberBetween(100, 999),
            'excerpt' => [
                'en' => fake()->paragraph(1),
                'tr' => fake()->paragraph(1),
            ],
            'content' => [
                'en' => '<p>' . implode('</p><p>', fake()->paragraphs(4)) . '</p>',
                'tr' => '<p>' . implode('</p><p>', fake()->paragraphs(4)) . '</p>',
            ],
            'is_published' => fake()->boolean(70),
            'published_at' => fake()->boolean(70) ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    public function published(): static
    {
        return $this->state([
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
