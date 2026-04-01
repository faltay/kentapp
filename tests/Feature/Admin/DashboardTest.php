<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_dashboard_passes_required_view_data(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertViewHasAll(['stats', 'months', 'recentPayments', 'recentRestaurants', 'planDistribution']);
    }

    public function test_restaurant_owner_cannot_access_admin_dashboard(): void
    {
        $owner = $this->createRestaurantOwner();

        $response = $this->actingAs($owner)->get(route('admin.dashboard'));

        // 403 veya redirect beklenir
        $this->assertContains($response->status(), [302, 403]);
    }

    public function test_admin_can_access_users_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_access_restaurants_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.restaurants.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_access_payments_list(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.payments.index'));

        $response->assertStatus(200);
    }
}
