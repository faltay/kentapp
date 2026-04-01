<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\SubscriptionPlan;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Stripe Checkout Session oluşturur ve URL döner.
     */
    public function createCheckoutSession(
        Restaurant $restaurant,
        SubscriptionPlan $plan,
        string $billingCycle,
        string $currency,
        float $amount
    ): string {
        $currency = strtolower($currency);

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => (int) round($amount * 100), // kuruş cinsinden
                    'product_data' => [
                        'name' => $plan->localized_name . ' — ' . ucfirst($billingCycle),
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'restaurant_id' => $restaurant->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
            ],
            'success_url' => route('restaurant.billing.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('restaurant.billing.plans'),
            'customer_email' => $restaurant->owner?->email,
        ]);

        return $session->url;
    }

    /**
     * Session ID'ye göre ödeme bilgilerini döner.
     */
    public function retrieveSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }

    /**
     * Webhook event'ini doğrular ve döner.
     */
    public function constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('services.stripe.webhook_secret')
        );
    }
}
