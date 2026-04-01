<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => Subscription::STATUS_ACTIVE,
            'billing_cycle' => Subscription::CYCLE_MONTHLY,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'amount_paid' => 0,
            'currency' => 'USD',
        ];
    }

    public function active(): static
    {
        return $this->state([
            'status' => Subscription::STATUS_ACTIVE,
            'ends_at' => now()->addMonth(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => Subscription::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'ends_at' => now()->addDays(15),
        ]);
    }

    public function expired(): static
    {
        return $this->state([
            'status' => Subscription::STATUS_EXPIRED,
            'ends_at' => now()->subDay(),
        ]);
    }
}
