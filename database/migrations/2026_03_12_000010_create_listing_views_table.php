<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('listing_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('credits_spent')->default(0);
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['listing_id', 'contractor_id']);
            $table->index('contractor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_views');
    }
};
