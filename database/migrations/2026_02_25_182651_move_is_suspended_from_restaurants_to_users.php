<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('is_active');
        });

        if (Schema::hasColumn('restaurants', 'is_suspended')) {
            Schema::table('restaurants', function (Blueprint $table) {
                $table->dropIndex(['is_active', 'is_suspended']);
                $table->dropColumn('is_suspended');
                $table->index('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->boolean('is_suspended')->default(false)->after('is_active');
            $table->index(['is_active', 'is_suspended']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_suspended');
        });
    }
};
