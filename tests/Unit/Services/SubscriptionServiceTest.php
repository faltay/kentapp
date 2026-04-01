<?php

namespace Tests\Unit\Services;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SubscriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SubscriptionService();
        Mail::fake(); // email queue'ya girmesin
    }

    // ── subscribeFree ─────────────────────────────────────────────────────────

    public function test_subscribe_free_creates_active_subscription(): void
    {
        $owner = $this->createRestaurantOwner(withSubscription: false);
        $plan = SubscriptionPlan::factory()->free()->create(['is_active' => true]);

        $sub = $this->service->subscribeFree($owner, $plan);

        $this->assertEquals(Subscription::STATUS_ACTIVE, $sub->status);
        $this->assertNull($sub->ends_at);
        $this->assertEquals('0.00', $sub->amount_paid);
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $owner->id,
            'subscription_plan_id' => $plan->id,
            'status' => Subscription::STATUS_ACTIVE,
        ]);
    }

    public function test_subscribe_free_records_payment(): void
    {
        $owner = $this->createRestaurantOwner(withSubscription: false);
        $plan = SubscriptionPlan::factory()->free()->create(['is_active' => true]);

        $this->service->subscribeFree($owner, $plan);

        $this->assertDatabaseHas('payments', [
            'user_id' => $owner->id,
            'provider' => Payment::PROVIDER_FREE,
            'amount' => 0,
            'status' => Payment::STATUS_SUCCEEDED,
        ]);
    }

    public function test_subscribe_free_cancels_existing_active_subscription(): void
    {
        $owner = $this->createRestaurantOwner(); // has active subscription
        $plan = SubscriptionPlan::factory()->free()->create(['is_active' => true]);

        $this->service->subscribeFree($owner, $plan);

        // Only the new one should be active; old one cancelled
        $activeCount = $owner->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->count();
        $this->assertEquals(1, $activeCount);
    }

    public function test_subscribe_free_sets_user_active_plan(): void
    {
        $owner = $this->createRestaurantOwner(withSubscription: false);
        $plan = SubscriptionPlan::factory()->free()->create(['is_active' => true]);

        $this->service->subscribeFree($owner, $plan);

        $owner->clearSubscriptionCache();
        $this->assertEquals($plan->id, $owner->activePlan()?->id);
    }

    // ── cancel ────────────────────────────────────────────────────────────────

    public function test_cancel_sets_status_to_cancelled(): void
    {
        $owner = $this->createRestaurantOwner();
        $subscription = $owner->subscriptions()->active()->first();

        $cancelled = $this->service->cancel($subscription);

        $this->assertEquals(Subscription::STATUS_CANCELLED, $cancelled->status);
        $this->assertNotNull($cancelled->cancelled_at);
    }

    public function test_cancel_does_not_delete_subscription(): void
    {
        $owner = $this->createRestaurantOwner();
        $subscription = $owner->subscriptions()->active()->first();

        $this->service->cancel($subscription);

        $this->assertDatabaseHas('subscriptions', ['id' => $subscription->id]);
    }

    public function test_cancelled_subscription_still_reports_active_until_period_end(): void
    {
        $owner = $this->createRestaurantOwner();
        $subscription = $owner->subscriptions()->active()->first();

        $cancelled = $this->service->cancel($subscription);

        // ends_at is in the future (set to +1 month in factory) so isActive() still true
        $this->assertTrue($cancelled->isActive());
        $this->assertTrue($cancelled->isCancelled());
    }

    // ── getActive ─────────────────────────────────────────────────────────────

    public function test_get_active_returns_subscription_for_restaurant(): void
    {
        $owner = $this->createRestaurantOwner();

        $active = $this->service->getActive($owner);

        $this->assertNotNull($active);
        $this->assertEquals(Subscription::STATUS_ACTIVE, $active->status);
    }

    public function test_get_active_returns_null_when_no_subscription(): void
    {
        $owner = $this->createRestaurantOwner(withSubscription: false);

        $active = $this->service->getActive($owner);

        $this->assertNull($active);
    }
}
