<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        $standardPlan = SubscriptionPlan::where('slug', 'standard')->first();

        // ── Demo Kullanıcı 1: Aktif + Standard Plan ──────────────────────────
        $owner1 = User::firstOrCreate(
            ['email' => 'owner@kentapp.test'],
            [
                'name' => 'Demo Owner',
                'password' => Hash::make('password'),
                'is_active' => true,
                'type' => User::TYPE_CONTRACTOR,
                'email_verified_at' => now(),
            ]
        );
        $owner1->assignRole('verified_contractor');

        $restaurant1 = Restaurant::firstOrCreate(
            ['slug' => 'demo-business'],
            [
                'user_id' => $owner1->id,
                'name' => 'Demo Business',
                'slug' => 'demo-business',
                'description' => 'A sample business entry.',
                'is_active' => true,
            ]
        );

        Branch::firstOrCreate(
            ['restaurant_id' => $restaurant1->id, 'slug' => 'demo-business-main'],
            [
                'restaurant_id' => $restaurant1->id,
                'name' => ['en' => 'Main Branch', 'tr' => 'Ana Şube'],
                'slug' => 'demo-business-main',
                'is_active' => true,
                'is_main' => true,
                'sort_order' => 1,
            ]
        );

        if ($standardPlan) {
            Subscription::firstOrCreate(
                ['user_id' => $owner1->id, 'status' => Subscription::STATUS_ACTIVE],
                [
                    'user_id'              => $owner1->id,
                    'subscription_plan_id' => $standardPlan->id,
                    'status'               => Subscription::STATUS_ACTIVE,
                    'billing_cycle'        => Subscription::CYCLE_MONTHLY,
                    'starts_at'            => now()->subDays(15),
                    'ends_at'              => now()->addDays(15),
                    'amount_paid'          => $standardPlan->getPrice('USD') ?? 0,
                    'currency'             => 'USD',
                ]
            );
        }

        // ── Demo Kullanıcı 2: Ücretsiz Plan ──────────────────────────────────
        $owner2 = User::firstOrCreate(
            ['email' => 'owner2@kentapp.test'],
            [
                'name' => 'Free Plan Owner',
                'password' => Hash::make('password'),
                'is_active' => true,
                'type' => User::TYPE_LAND_OWNER,
                'email_verified_at' => now(),
            ]
        );
        $owner2->assignRole('land_owner');

        $restaurant2 = Restaurant::firstOrCreate(
            ['slug' => 'lite-business'],
            [
                'user_id' => $owner2->id,
                'name' => 'Lite Business',
                'slug' => 'lite-business',
                'description' => 'A small business on the free plan.',
                'is_active' => true,
            ]
        );

        Branch::firstOrCreate(
            ['restaurant_id' => $restaurant2->id, 'slug' => 'lite-business-main'],
            [
                'restaurant_id' => $restaurant2->id,
                'name' => ['en' => 'Main Branch', 'tr' => 'Ana Şube'],
                'slug' => 'lite-business-main',
                'is_active' => true,
                'is_main' => true,
                'sort_order' => 1,
            ]
        );

        if ($freePlan) {
            Subscription::firstOrCreate(
                ['user_id' => $owner2->id, 'status' => Subscription::STATUS_ACTIVE],
                [
                    'user_id'              => $owner2->id,
                    'subscription_plan_id' => $freePlan->id,
                    'status'               => Subscription::STATUS_ACTIVE,
                    'billing_cycle'        => Subscription::CYCLE_MONTHLY,
                    'starts_at'            => now()->subMonth(),
                    'ends_at'              => null,
                    'amount_paid'          => 0,
                    'currency'             => 'USD',
                ]
            );
        }
    }
}
