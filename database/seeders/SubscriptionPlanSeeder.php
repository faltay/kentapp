<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => ['en' => 'Free', 'tr' => 'Ücretsiz'],
                'slug' => 'free',
                'description' => ['en' => 'Perfect for getting started.', 'tr' => 'Başlamak için ideal.'],
                'prices' => [],
                'max_restaurants' => 1,
                'max_branches' => 1,
                'features' => [
                    'en' => ['1 Entry', '1 Branch'],
                    'tr' => ['1 Kayıt', '1 Şube'],
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => ['en' => 'Standard', 'tr' => 'Standart'],
                'slug' => 'standard',
                'description' => ['en' => 'For growing businesses.', 'tr' => 'Büyüyen işletmeler için.'],
                'prices' => [
                    'USD' => ['monthly' => 19.99, 'yearly' => 199.99],
                    'TRY' => ['monthly' => 599.99, 'yearly' => 5999.99],
                ],
                'max_restaurants' => 1,
                'max_branches' => 3,
                'features' => [
                    'en' => ['1 Entry', '3 Branches', 'Analytics'],
                    'tr' => ['1 Kayıt', '3 Şube', 'Analitik'],
                ],
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'name' => ['en' => 'Pro', 'tr' => 'Pro'],
                'slug' => 'pro',
                'description' => ['en' => 'For large chains.', 'tr' => 'Büyük zincirler için.'],
                'prices' => [
                    'USD' => ['monthly' => 49.99, 'yearly' => 499.99],
                    'TRY' => ['monthly' => 1499.99, 'yearly' => 14999.99],
                ],
                'max_restaurants' => -1,
                'max_branches' => -1,
                'features' => [
                    'en' => ['Unlimited Entries', 'Unlimited Branches', 'Analytics', 'Priority Support'],
                    'tr' => ['Sınırsız Kayıt', 'Sınırsız Şube', 'Analitik', 'Öncelikli Destek'],
                ],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::firstOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}
