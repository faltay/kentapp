<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('phone');
            // Foreign key constraint Modül 3'te (restaurants tablosu oluşunca) eklenecek
            $table->unsignedBigInteger('restaurant_id')->nullable()->after('is_active');
            $table->index('restaurant_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['restaurant_id']);
            $table->dropColumn(['phone', 'is_active', 'restaurant_id']);
        });
    }
};
