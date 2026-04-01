<?php

namespace Tests;

use App\Models\Branch;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    /**
     * Spatie Permission cache sorununu önlemek için her testte rolleri hazırla.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Test ortamında Vite build dosyası arama
        $this->withoutVite();

        // Spatie Permission cache temizle
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Temel rolleri oluştur
        Role::firstOrCreate(['name' => 'super_admin',      'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'restaurant_owner', 'guard_name' => 'web']);

        // Dilleri oluştur ve cache'i temizle
        Language::firstOrCreate(['code' => 'en'], [
            'name' => 'English', 'native' => 'English', 'flag' => '🇬🇧',
            'direction' => 'ltr', 'is_active' => true, 'is_default' => true, 'sort_order' => 1,
        ]);
        Language::firstOrCreate(['code' => 'tr'], [
            'name' => 'Turkish', 'native' => 'Türkçe', 'flag' => '🇹🇷',
            'direction' => 'ltr', 'is_active' => true, 'is_default' => false, 'sort_order' => 2,
        ]);
        Language::clearCache();
    }

    /**
     * Aktif aboneligi olan restoran sahibi olusturur.
     * Dönen kullanıcının restoranına $user->ownedRestaurants->first() ile erişilebilir.
     */
    protected function createRestaurantOwner(
        ?SubscriptionPlan $plan = null,
        bool $withSubscription = true,
    ): User {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('restaurant_owner');

        $restaurant = Restaurant::factory()->create(['user_id' => $user->id]);

        // Her restoran için bir şube ve menü oluştur (test yardımcıları için)
        $branch = Branch::factory()->create([
            'restaurant_id' => $restaurant->id,
            'is_main'       => true,
        ]);
        Menu::factory()->create([
            'restaurant_id' => $restaurant->id,
            'branch_id'     => $branch->id,
            'is_active'     => true,
        ]);

        if ($withSubscription) {
            $plan ??= SubscriptionPlan::factory()->create([
                'slug' => 'test-plan-' . uniqid(),
                'prices' => [],
                'max_menu_items' => 100,
                'max_branches' => 5,
                'max_tables' => 30,
                'max_restaurants' => 1,
                'is_active' => true,
            ]);

            Subscription::factory()->create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'status' => Subscription::STATUS_ACTIVE,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonth(),
                'billing_cycle' => 'monthly',
                'amount_paid' => 0,
                'currency' => 'USD',
            ]);

            $restaurant->update(['subscription_plan_id' => $plan->id]);
        }

        // Session'da aktif restoran ayarla (currentRestaurant() için)
        session(['current_restaurant_id' => $restaurant->id]);

        return $user->fresh();
    }

    /**
     * Owner'ın sahip olduğu restoranı döner (test helper).
     */
    protected function getRestaurant(User $owner): Restaurant
    {
        return $owner->ownedRestaurants()->first();
    }

    /**
     * Owner'ın restoranına ait ilk menüyü döner (test helper).
     */
    protected function getMenu(User $owner): Menu
    {
        return Menu::where('restaurant_id', $this->getRestaurant($owner)->id)->firstOrFail();
    }

    /**
     * Super admin olusturur.
     */
    protected function createAdmin(): User
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'type' => User::TYPE_ADMIN,
        ]);
        $user->assignRole('super_admin');

        return $user;
    }
}
