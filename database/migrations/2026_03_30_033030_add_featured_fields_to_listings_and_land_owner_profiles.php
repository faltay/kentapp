<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('featured_credit_spent')->default(false)->after('is_featured');
        });

        Schema::table('land_owner_profiles', function (Blueprint $table) {
            $table->unsignedInteger('credit_balance')->default(0)->after('tc_number');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('featured_credit_spent');
        });

        Schema::table('land_owner_profiles', function (Blueprint $table) {
            $table->dropColumn('credit_balance');
        });
    }
};
