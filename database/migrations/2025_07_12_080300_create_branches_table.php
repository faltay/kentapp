<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');

            $table->json('name');              // {"en": "Main Branch", "tr": "Ana Şube"}
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_main')->default(false);  // Ana şube işareti
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['restaurant_id', 'is_active']);
            $table->index(['restaurant_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
