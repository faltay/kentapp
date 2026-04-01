<?php

namespace Tests\Unit\Models;

use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionPlanTest extends TestCase
{
    use RefreshDatabase;
    // ── isFree ────────────────────────────────────────────────────────────────

    public function test_is_free_returns_true_when_prices_empty(): void
    {
        $plan = new SubscriptionPlan();
        $plan->prices = [];

        $this->assertTrue($plan->isFree());
    }

    public function test_is_free_returns_true_when_prices_null(): void
    {
        $plan = new SubscriptionPlan();
        $plan->prices = null;

        $this->assertTrue($plan->isFree());
    }

    public function test_is_free_returns_false_when_price_is_nonzero(): void
    {
        $plan = new SubscriptionPlan();
        $plan->prices = ['USD' => ['monthly' => 29.99]];

        $this->assertFalse($plan->isFree());
    }

    // ── getPrice ────────────────────────────────────────────────────────────

    public function test_get_price_returns_correct_amount(): void
    {
        $plan = new SubscriptionPlan();
        $plan->prices = [
            'USD' => ['monthly' => 9.99, 'yearly' => 99.99],
            'TRY' => ['monthly' => 250.00, 'yearly' => 2500.00],
        ];

        $this->assertEquals(9.99, $plan->getPrice('USD', 'monthly'));
        $this->assertEquals(99.99, $plan->getPrice('USD', 'yearly'));
        $this->assertEquals(250.00, $plan->getPrice('TRY', 'monthly'));
        $this->assertNull($plan->getPrice('EUR', 'monthly'));
    }

    // ── getAvailableCurrencies ──────────────────────────────────────────────

    public function test_get_available_currencies_returns_keys(): void
    {
        $plan = new SubscriptionPlan();
        $plan->prices = [
            'USD' => ['monthly' => 9.99],
            'TRY' => ['monthly' => 250.00],
        ];

        $this->assertEquals(['USD', 'TRY'], $plan->getAvailableCurrencies());
    }

    // ── getDisplayPrice ─────────────────────────────────────────────────────

    public function test_get_display_price_formats_correctly(): void
    {
        $plan = new SubscriptionPlan();
        $plan->prices = ['USD' => ['monthly' => 9.99]];

        $this->assertEquals('$9.99', $plan->getDisplayPrice('USD'));
        $this->assertEquals('-', $plan->getDisplayPrice('EUR'));
    }

    // ── unlimited helpers ─────────────────────────────────────────────────────

    public function test_has_unlimited_menu_items_returns_true_when_minus_one(): void
    {
        $plan = new SubscriptionPlan();
        $plan->max_menu_items = -1;

        $this->assertTrue($plan->hasUnlimitedMenuItems());
    }

    public function test_has_unlimited_menu_items_returns_false_for_finite_limit(): void
    {
        $plan = new SubscriptionPlan();
        $plan->max_menu_items = 100;

        $this->assertFalse($plan->hasUnlimitedMenuItems());
    }

    public function test_has_unlimited_branches_returns_true_when_minus_one(): void
    {
        $plan = new SubscriptionPlan();
        $plan->max_branches = -1;

        $this->assertTrue($plan->hasUnlimitedBranches());
    }

    public function test_has_unlimited_tables_returns_true_when_minus_one(): void
    {
        $plan = new SubscriptionPlan();
        $plan->max_tables = -1;

        $this->assertTrue($plan->hasUnlimitedTables());
    }

    // ── getLimit ──────────────────────────────────────────────────────────────

    public function test_get_limit_returns_correct_values(): void
    {
        $plan = new SubscriptionPlan();
        $plan->max_restaurants = 1;
        $plan->max_branches = 3;
        $plan->max_menu_items = 50;
        $plan->max_tables = 10;

        $this->assertEquals(1, $plan->getLimit('restaurant'));
        $this->assertEquals(3, $plan->getLimit('branch'));
        $this->assertEquals(50, $plan->getLimit('menu_item'));
        $this->assertEquals(10, $plan->getLimit('table'));
        $this->assertEquals(0, $plan->getLimit('unknown_feature'));
    }

    // ── localized_name ────────────────────────────────────────────────────────

    public function test_localized_name_returns_correct_locale(): void
    {
        $plan = new SubscriptionPlan();
        $plan->name = ['en' => 'Pro Plan', 'tr' => 'Pro Plan TR'];

        app()->setLocale('tr');
        $this->assertEquals('Pro Plan TR', $plan->localized_name);

        app()->setLocale('en');
        $this->assertEquals('Pro Plan', $plan->localized_name);
    }

    public function test_localized_name_falls_back_to_english(): void
    {
        $plan = new SubscriptionPlan();
        $plan->name = ['en' => 'Standard'];

        app()->setLocale('tr');
        $this->assertEquals('Standard', $plan->localized_name);
    }
}
