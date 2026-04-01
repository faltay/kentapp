<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $standardPlan = SubscriptionPlan::where('slug', 'standard')->first();
        $proPlan = SubscriptionPlan::where('slug', 'pro')->first();
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();

        if (! $standardPlan || ! $proPlan || ! $freePlan) {
            $this->command->warn('Plans not found. Run SubscriptionPlanSeeder first.');
            return;
        }

        // ── Mevcut owner'lar ─────────────────────────────────────────────────────
        $owner1 = User::where('email', 'owner@qrmenu.test')->first();
        $owner2 = User::where('email', 'owner2@qrmenu.test')->first();

        // ── Ek test kullanıcılar ─────────────────────────────────────────────────
        $users = [];
        $testUsers = [
            ['name' => 'Ahmet Yılmaz',    'email' => 'ahmet@qrmenu.test'],
            ['name' => 'Fatma Demir',      'email' => 'fatma@qrmenu.test'],
            ['name' => 'John Smith',       'email' => 'john@qrmenu.test'],
            ['name' => 'Elif Kaya',        'email' => 'elif@qrmenu.test'],
            ['name' => 'Mehmet Öz',        'email' => 'mehmet@qrmenu.test'],
        ];

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'type' => User::TYPE_LAND_OWNER,
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('land_owner');
            $users[] = $user;
        }

        // Owner1 ve owner2'yi de dahil et
        if ($owner1) {
            array_unshift($users, $owner1);
        }
        if ($owner2) {
            $users[] = $owner2;
        }

        // ── Abonelik + ödeme kayıtları ───────────────────────────────────────────
        $payments = [
            // 1. Stripe aylık USD — başarılı
            [
                'user'     => $users[0] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'USD',
                'amount'   => 19.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 30,
            ],
            // 2. Stripe yıllık USD — başarılı
            [
                'user'     => $users[0] ?? null,
                'plan'     => $proPlan,
                'cycle'    => Subscription::CYCLE_YEARLY,
                'currency' => 'USD',
                'amount'   => 499.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 5,
            ],
            // 3. İyzico aylık TRY — başarılı
            [
                'user'     => $users[1] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'TRY',
                'amount'   => 599.99,
                'provider' => Payment::PROVIDER_IYZICO,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 15,
            ],
            // 4. İyzico yıllık TRY — başarılı
            [
                'user'     => $users[2] ?? null,
                'plan'     => $proPlan,
                'cycle'    => Subscription::CYCLE_YEARLY,
                'currency' => 'TRY',
                'amount'   => 14999.99,
                'provider' => Payment::PROVIDER_IYZICO,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 60,
            ],
            // 5. Stripe — başarısız (failed)
            [
                'user'     => $users[3] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'USD',
                'amount'   => 19.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_FAILED,
                'days_ago' => 10,
            ],
            // 6. Stripe — tam iade (refunded)
            [
                'user'     => $users[3] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'USD',
                'amount'   => 19.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_REFUNDED,
                'days_ago' => 45,
                'refund'   => ['amount' => 19.99, 'days_ago' => 40],
            ],
            // 7. İyzico — kısmi iade
            [
                'user'     => $users[4] ?? null,
                'plan'     => $proPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'TRY',
                'amount'   => 1499.99,
                'provider' => Payment::PROVIDER_IYZICO,
                'status'   => Payment::STATUS_PARTIALLY_REFUNDED,
                'days_ago' => 20,
                'refund'   => ['amount' => 500.00, 'days_ago' => 12],
            ],
            // 8. Free plan kaydı
            [
                'user'     => $users[5] ?? null,
                'plan'     => $freePlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'USD',
                'amount'   => 0,
                'provider' => Payment::PROVIDER_FREE,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 90,
            ],
            // 9. Stripe aylık EUR — başarılı
            [
                'user'     => $users[5] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'EUR',
                'amount'   => 18.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 3,
            ],
            // 10. Stripe yıllık USD — başarılı (eski ödeme)
            [
                'user'     => $users[6] ?? null,
                'plan'     => $proPlan,
                'cycle'    => Subscription::CYCLE_YEARLY,
                'currency' => 'USD',
                'amount'   => 499.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 120,
            ],
            // 11. İyzico aylık TRY — başarılı (güncel)
            [
                'user'     => $users[4] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'TRY',
                'amount'   => 599.99,
                'provider' => Payment::PROVIDER_IYZICO,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 2,
            ],
            // 12. Stripe aylık USD — yenileme ödemesi
            [
                'user'     => $users[0] ?? null,
                'plan'     => $standardPlan,
                'cycle'    => Subscription::CYCLE_MONTHLY,
                'currency' => 'USD',
                'amount'   => 19.99,
                'provider' => Payment::PROVIDER_STRIPE,
                'status'   => Payment::STATUS_SUCCEEDED,
                'days_ago' => 0,
            ],
        ];

        foreach ($payments as $data) {
            if (! $data['user']) {
                continue;
            }

            $paidAt = now()->subDays($data['days_ago']);
            $isYearly = $data['cycle'] === Subscription::CYCLE_YEARLY;

            // Abonelik oluştur
            $subscription = Subscription::create([
                'user_id'              => $data['user']->id,
                'subscription_plan_id' => $data['plan']->id,
                'status'               => $this->mapPaymentStatusToSubscription($data['status']),
                'billing_cycle'        => $data['cycle'],
                'starts_at'            => $paidAt,
                'ends_at'              => $data['amount'] > 0
                    ? $paidAt->copy()->addMonths($isYearly ? 12 : 1)
                    : null,
                'amount_paid'          => $data['amount'],
                'currency'             => $data['currency'],
                'stripe_subscription_id'  => $data['provider'] === Payment::PROVIDER_STRIPE ? 'sub_' . Str::random(24) : null,
                'stripe_payment_intent_id' => $data['provider'] === Payment::PROVIDER_STRIPE ? 'pi_' . Str::random(24) : null,
                'iyzico_reference_code'    => $data['provider'] === Payment::PROVIDER_IYZICO ? Str::random(12) : null,
                'iyzico_conversation_id'   => $data['provider'] === Payment::PROVIDER_IYZICO ? Str::random(20) : null,
            ]);

            // Ödeme kaydı
            $payment = Payment::create([
                'user_id'             => $data['user']->id,
                'subscription_id'     => $subscription->id,
                'provider'            => $data['provider'],
                'provider_payment_id' => $this->fakeProviderId($data['provider']),
                'amount'              => $data['amount'],
                'currency'            => $data['currency'],
                'status'              => $data['status'],
                'description'         => $data['plan']->localized_name . ' — ' . ucfirst($data['cycle']),
                'paid_at'             => $paidAt,
            ]);

            // İade varsa
            if (isset($data['refund'])) {
                $payment->update([
                    'refunded_amount'    => $data['refund']['amount'],
                    'refunded_at'        => now()->subDays($data['refund']['days_ago']),
                    'provider_refund_id' => 're_' . Str::random(24),
                ]);
            }
        }

        $this->command->info('PaymentSeeder: ' . count(array_filter($payments, fn ($p) => $p['user'] !== null)) . ' payment records created.');
    }

    private function mapPaymentStatusToSubscription(string $paymentStatus): string
    {
        return match ($paymentStatus) {
            Payment::STATUS_SUCCEEDED           => Subscription::STATUS_ACTIVE,
            Payment::STATUS_FAILED              => Subscription::STATUS_EXPIRED,
            Payment::STATUS_REFUNDED            => Subscription::STATUS_CANCELLED,
            Payment::STATUS_PARTIALLY_REFUNDED  => Subscription::STATUS_ACTIVE,
            default                             => Subscription::STATUS_ACTIVE,
        };
    }

    private function fakeProviderId(string $provider): string
    {
        return match ($provider) {
            Payment::PROVIDER_STRIPE => 'ch_' . Str::random(24),
            Payment::PROVIDER_IYZICO => Str::random(20),
            default                  => 'free_' . Str::random(12),
        };
    }
}
