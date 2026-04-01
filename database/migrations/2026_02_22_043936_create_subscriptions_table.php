<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');

            // active | trialing | cancelled | expired | past_due
            $table->string('status', 20)->default('active');
            // monthly | yearly
            $table->string('billing_cycle', 10)->default('monthly');

            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();         // null = no expiry
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Gateway referansları
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('iyzico_reference_code')->nullable();
            $table->string('iyzico_conversation_id')->nullable();

            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->string('currency', 3)->nullable();

            $table->timestamps();

            $table->index(['restaurant_id', 'status']);
            $table->index(['restaurant_id', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
