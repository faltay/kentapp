<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['urban_renewal', 'land'])->default('urban_renewal');
            $table->enum('status', ['draft', 'pending', 'active', 'rejected', 'passive'])->default('pending');
            $table->string('province', 100);
            $table->string('district', 100);
            $table->string('neighborhood', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('ada_no', 50)->nullable();
            $table->string('parcel_no', 50)->nullable();
            $table->decimal('area_m2', 10, 2)->nullable();
            $table->unsignedTinyInteger('floor_count')->nullable();
            $table->enum('zoning_status', ['residential', 'commercial', 'mixed', 'unplanned'])->nullable();
            $table->decimal('taks', 5, 2)->nullable();
            $table->decimal('kaks', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['district', 'status']);
            $table->index(['status', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
