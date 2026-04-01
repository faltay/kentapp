<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('listing_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['purchase', 'spend', 'refund']);
            $table->integer('amount');
            $table->unsignedInteger('balance_after');
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['listing_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
