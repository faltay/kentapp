<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->json('name');                          // {"en": "Free", "tr": "Ücretsiz"}
            $table->string('slug')->unique();
            $table->json('description')->nullable();       // {"en": "...", "tr": "..."}
            $table->decimal('price', 10, 2)->default(0);  // monthly price
            $table->decimal('price_yearly', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->integer('max_restaurants')->default(1);  // -1 = unlimited
            $table->integer('max_branches')->default(1);
            $table->json('features')->nullable();          // ["Feature 1", "Feature 2"]
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
