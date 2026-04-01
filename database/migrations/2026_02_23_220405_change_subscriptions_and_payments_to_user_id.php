<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // ── subscriptions: restaurant_id → user_id ────────────────────────────
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropIndex(['restaurant_id', 'status']);
            $table->dropIndex(['restaurant_id', 'ends_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('restaurant_id', 'user_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'ends_at']);
        });

        // ── payments: restaurant_id → user_id ─────────────────────────────────
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropIndex(['restaurant_id', 'status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('restaurant_id', 'user_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
        });

        // ── restaurants: subscription_plan_id artık user planından geliyor ────
        // Kolon korunuyor (admin panelinde görsel referans için) ama
        // SubscriptionService artık bunu güncellemeyecek.
        // Tamamen kaldırmak isterseniz ayrı migration yazılabilir.
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'ends_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'restaurant_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->index(['restaurant_id', 'status']);
            $table->index(['restaurant_id', 'ends_at']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('user_id', 'restaurant_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->index(['restaurant_id', 'status']);
        });
    }
};
