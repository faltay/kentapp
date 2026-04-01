<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add prices JSON column
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->json('prices')->nullable()->after('description');
        });

        // 2. Migrate existing data
        $plans = DB::table('subscription_plans')->get();

        foreach ($plans as $plan) {
            $currency = $plan->currency ?? 'USD';
            $monthly = (float) ($plan->price ?? 0);
            $yearly = $plan->price_yearly ? (float) $plan->price_yearly : null;

            if ($monthly == 0 && !$yearly) {
                $prices = (object) [];
            } else {
                $priceData = ['monthly' => $monthly];
                if ($yearly !== null) {
                    $priceData['yearly'] = $yearly;
                }
                $prices = [$currency => $priceData];
            }

            DB::table('subscription_plans')
                ->where('id', $plan->id)
                ->update(['prices' => json_encode($prices)]);
        }

        // 3. Drop old columns
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['price', 'price_yearly', 'currency']);
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->after('description');
            $table->decimal('price_yearly', 10, 2)->nullable()->after('price');
            $table->string('currency', 3)->default('USD')->after('price_yearly');
        });

        // Reverse migrate: take first currency from prices JSON
        $plans = DB::table('subscription_plans')->get();

        foreach ($plans as $plan) {
            $prices = json_decode($plan->prices, true) ?? [];
            $currency = 'USD';
            $monthly = 0;
            $yearly = null;

            if (!empty($prices)) {
                $currency = array_key_first($prices);
                $monthly = $prices[$currency]['monthly'] ?? 0;
                $yearly = $prices[$currency]['yearly'] ?? null;
            }

            DB::table('subscription_plans')
                ->where('id', $plan->id)
                ->update([
                    'price' => $monthly,
                    'price_yearly' => $yearly,
                    'currency' => $currency,
                ]);
        }

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('prices');
        });
    }
};
