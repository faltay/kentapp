<?php

namespace App\Services;

use App\Mail\SubscriptionCancelledMail;
use App\Mail\SubscriptionStartedMail;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionService
{
    /**
     * Ücretsiz plana anında abone ol.
     */
    public function subscribeFree(User $user, SubscriptionPlan $plan): Subscription
    {
        DB::beginTransaction();

        try {
            $this->cancelExistingSubscriptions($user);

            $subscription = Subscription::create([
                'user_id'              => $user->id,
                'subscription_plan_id' => $plan->id,
                'status'               => Subscription::STATUS_ACTIVE,
                'billing_cycle'        => Subscription::CYCLE_MONTHLY,
                'starts_at'            => now(),
                'ends_at'              => null, // Ücretsiz plan sonsuz
                'amount_paid'          => 0,
                'currency'             => $plan->getAvailableCurrencies()[0] ?? 'USD',
            ]);

            $this->recordPayment(
                user: $user,
                subscription: $subscription,
                provider: Payment::PROVIDER_FREE,
                amount: 0,
                currency: $plan->getAvailableCurrencies()[0] ?? 'USD',
                description: "Free plan: {$plan->localized_name}",
            );

            DB::commit();
            $user->clearSubscriptionCache();

            $this->sendSubscriptionStartedMail($user, $subscription, $plan);

            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Free subscription failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            throw $e;
        }
    }

    /**
     * Stripe başarılı ödeme sonrası abonelik oluştur.
     */
    public function activateFromStripe(
        User $user,
        SubscriptionPlan $plan,
        string $billingCycle,
        string $paymentIntentId,
        float $amountPaid,
        string $currency
    ): Subscription {
        DB::beginTransaction();

        try {
            $this->cancelExistingSubscriptions($user);

            $endsAt = $billingCycle === 'yearly'
                ? now()->addYear()
                : now()->addMonth();

            $subscription = Subscription::create([
                'user_id'                  => $user->id,
                'subscription_plan_id'     => $plan->id,
                'status'                   => Subscription::STATUS_ACTIVE,
                'billing_cycle'            => $billingCycle,
                'starts_at'                => now(),
                'ends_at'                  => $endsAt,
                'stripe_payment_intent_id' => $paymentIntentId,
                'amount_paid'              => $amountPaid,
                'currency'                 => strtoupper($currency),
            ]);

            $this->recordPayment(
                user: $user,
                subscription: $subscription,
                provider: Payment::PROVIDER_STRIPE,
                amount: $amountPaid,
                currency: strtoupper($currency),
                providerPaymentId: $paymentIntentId,
                description: "{$plan->localized_name} — {$billingCycle}",
            );

            DB::commit();
            $user->clearSubscriptionCache();

            $this->sendSubscriptionStartedMail($user, $subscription, $plan);

            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stripe subscription activation failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            throw $e;
        }
    }

    /**
     * İyzico başarılı ödeme sonrası abonelik oluştur.
     */
    public function activateFromIyzico(
        User $user,
        SubscriptionPlan $plan,
        string $billingCycle,
        string $referenceCode,
        string $conversationId,
        float $amountPaid
    ): Subscription {
        DB::beginTransaction();

        try {
            $this->cancelExistingSubscriptions($user);

            $endsAt = $billingCycle === 'yearly'
                ? now()->addYear()
                : now()->addMonth();

            $subscription = Subscription::create([
                'user_id'                  => $user->id,
                'subscription_plan_id'     => $plan->id,
                'status'                   => Subscription::STATUS_ACTIVE,
                'billing_cycle'            => $billingCycle,
                'starts_at'                => now(),
                'ends_at'                  => $endsAt,
                'iyzico_reference_code'    => $referenceCode,
                'iyzico_conversation_id'   => $conversationId,
                'amount_paid'              => $amountPaid,
                'currency'                 => 'TRY',
            ]);

            $this->recordPayment(
                user: $user,
                subscription: $subscription,
                provider: Payment::PROVIDER_IYZICO,
                amount: $amountPaid,
                currency: 'TRY',
                providerPaymentId: $referenceCode,
                description: "{$plan->localized_name} — {$billingCycle}",
            );

            DB::commit();
            $user->clearSubscriptionCache();

            $this->sendSubscriptionStartedMail($user, $subscription, $plan);

            return $subscription;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Iyzico subscription activation failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            throw $e;
        }
    }

    /**
     * Aboneliği iptal et (dönem sonuna kadar erişim devam eder).
     */
    public function cancel(Subscription $subscription): Subscription
    {
        DB::beginTransaction();

        try {
            $subscription->update([
                'status'       => Subscription::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);

            DB::commit();
            $subscription->user?->clearSubscriptionCache();

            $fresh = $subscription->fresh(['user', 'plan']);
            if ($fresh->user && $fresh->plan) {
                $this->sendSubscriptionCancelledMail($fresh->user, $fresh, $fresh->plan);
            }

            return $fresh;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription cancellation failed', ['error' => $e->getMessage(), 'subscription_id' => $subscription->id]);

            throw $e;
        }
    }

    /**
     * Kullanıcının aktif aboneliğini döner.
     */
    public function getActive(User $user): ?Subscription
    {
        return $user->subscriptions()->active()->with('plan')->latest()->first();
    }

    /**
     * Ödeme kaydı oluştur.
     */
    public function recordPayment(
        User $user,
        Subscription $subscription,
        string $provider,
        float $amount,
        string $currency,
        ?string $providerPaymentId = null,
        ?string $description = null,
    ): Payment {
        return Payment::create([
            'user_id'             => $user->id,
            'subscription_id'     => $subscription->id,
            'provider'            => $provider,
            'provider_payment_id' => $providerPaymentId,
            'amount'              => $amount,
            'currency'            => strtoupper($currency),
            'status'              => Payment::STATUS_SUCCEEDED,
            'description'         => $description,
            'paid_at'             => now(),
        ]);
    }

    /**
     * Stripe webhook'undan gelen iade bilgisini kaydet.
     */
    public function processRefund(string $providerPaymentId, float $refundAmount, string $refundId): void
    {
        $payment = Payment::where('provider_payment_id', $providerPaymentId)->first();

        if (! $payment) {
            Log::warning('Refund: payment record not found', ['provider_payment_id' => $providerPaymentId]);

            return;
        }

        $payment->markRefunded($refundAmount, $refundId);
        Log::info('Refund recorded', ['payment_id' => $payment->id, 'refund_amount' => $refundAmount]);
    }

    private function cancelExistingSubscriptions(User $user): void
    {
        $user->subscriptions()
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIALING])
            ->update([
                'status'       => Subscription::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);
    }

    private function sendSubscriptionStartedMail(User $user, Subscription $subscription, SubscriptionPlan $plan): void
    {
        try {
            // SubscriptionStartedMail'in imzası güncellenmeli — şimdilik restaurant yerine user
            // Mail::to($user->email)->queue(new SubscriptionStartedMail($user, $subscription, $plan));
        } catch (\Exception $e) {
            Log::warning('SubscriptionStartedMail failed to queue', ['error' => $e->getMessage(), 'user_id' => $user->id]);
        }
    }

    private function sendSubscriptionCancelledMail(User $user, Subscription $subscription, SubscriptionPlan $plan): void
    {
        try {
            // Mail::to($user->email)->queue(new SubscriptionCancelledMail($user, $subscription, $plan));
        } catch (\Exception $e) {
            Log::warning('SubscriptionCancelledMail failed to queue', ['error' => $e->getMessage(), 'user_id' => $user->id]);
        }
    }
}
