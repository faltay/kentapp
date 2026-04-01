<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');

            // Ödeme sağlayıcı
            $table->string('provider', 20);                     // stripe | iyzico | free
            $table->string('provider_payment_id')->nullable();  // Stripe PaymentIntent ID / İyzico payment ID
            $table->string('provider_refund_id')->nullable();   // Stripe Refund ID

            // Tutar
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');

            // Durum
            $table->string('status', 30)->default('succeeded'); // succeeded | failed | refunded | partially_refunded
            $table->decimal('refunded_amount', 10, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->text('description')->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();

            $table->index(['restaurant_id', 'status']);
            $table->index(['provider', 'provider_payment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
