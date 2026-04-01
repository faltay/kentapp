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
        if (Schema::hasColumn('restaurants', 'currency')) {
            Schema::table('restaurants', function (Blueprint $table) {
                $table->dropColumn(['currency', 'timezone']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->after('description');
            $table->string('timezone', 50)->default('UTC')->after('currency');
        });
    }
};
