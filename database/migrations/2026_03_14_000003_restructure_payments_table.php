<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // subscription_id kaldır
            $table->dropForeign(['subscription_id']);
            $table->dropColumn('subscription_id');

            // credit_package_id ekle
            $table->foreignId('credit_package_id')->nullable()->after('user_id')
                  ->constrained()->onDelete('set null');

            // Kaç kontör satın alındı (snapshot)
            $table->unsignedInteger('credits')->default(0)->after('credit_package_id');

            // paid_at nullable yap (havale ödemesi onaylanana kadar null)
            $table->timestamp('paid_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['credit_package_id']);
            $table->dropColumn(['credit_package_id', 'credits']);

            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamp('paid_at')->nullable(false)->change();
        });
    }
};
