<?php

namespace Tests\Unit\Models;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;
    // ── isActive ──────────────────────────────────────────────────────────────

    public function test_is_active_returns_true_for_active_status_with_future_end(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_ACTIVE;
        $sub->ends_at = Carbon::now()->addMonth();

        $this->assertTrue($sub->isActive());
    }

    public function test_is_active_returns_true_for_active_status_with_no_end_date(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_ACTIVE;
        $sub->ends_at = null;

        $this->assertTrue($sub->isActive());
    }

    public function test_is_active_returns_true_for_cancelled_status_with_future_end(): void
    {
        // Cancelled but period not yet over — still grants access
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_CANCELLED;
        $sub->ends_at = Carbon::now()->addDays(10);

        $this->assertTrue($sub->isActive());
    }

    public function test_is_active_returns_false_when_ends_at_is_past(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_ACTIVE;
        $sub->ends_at = Carbon::now()->subDay();

        $this->assertFalse($sub->isActive());
    }

    public function test_is_active_returns_false_for_expired_status(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_EXPIRED;
        $sub->ends_at = Carbon::now()->addMonth(); // even with future date

        $this->assertFalse($sub->isActive());
    }

    public function test_is_active_returns_false_for_past_due_status(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_PAST_DUE;
        $sub->ends_at = null;

        $this->assertFalse($sub->isActive());
    }

    // ── isExpired ─────────────────────────────────────────────────────────────

    public function test_is_expired_is_inverse_of_is_active(): void
    {
        $active = new Subscription();
        $active->status = Subscription::STATUS_ACTIVE;
        $active->ends_at = null;
        $this->assertFalse($active->isExpired());

        $expired = new Subscription();
        $expired->status = Subscription::STATUS_EXPIRED;
        $expired->ends_at = null;
        $this->assertTrue($expired->isExpired());
    }

    // ── isCancelled ───────────────────────────────────────────────────────────

    public function test_is_cancelled_returns_true_for_cancelled_status(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_CANCELLED;

        $this->assertTrue($sub->isCancelled());
    }

    public function test_is_cancelled_returns_false_for_active_status(): void
    {
        $sub = new Subscription();
        $sub->status = Subscription::STATUS_ACTIVE;

        $this->assertFalse($sub->isCancelled());
    }

    // ── daysLeft ──────────────────────────────────────────────────────────────

    public function test_days_left_returns_max_int_when_no_end_date(): void
    {
        $sub = new Subscription();
        $sub->ends_at = null;

        $this->assertEquals(PHP_INT_MAX, $sub->daysLeft());
    }

    public function test_days_left_returns_correct_days(): void
    {
        $sub = new Subscription();
        $sub->ends_at = Carbon::now()->addDays(30);

        // diffInDays can be 29 or 30 depending on microseconds — allow ±1
        $this->assertEqualsWithDelta(30, $sub->daysLeft(), 1);
    }

    public function test_days_left_returns_zero_when_expired(): void
    {
        $sub = new Subscription();
        $sub->ends_at = Carbon::now()->subDays(5);

        $this->assertEquals(0, $sub->daysLeft());
    }
}
