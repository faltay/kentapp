<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('contractor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name', 255)->nullable();
            $table->string('authorized_name', 255)->nullable();
            $table->string('company_phone', 30)->nullable();
            $table->string('company_email', 255)->nullable();
            $table->text('company_address')->nullable();
            $table->json('working_neighborhoods')->nullable();
            $table->enum('certificate_status', ['none', 'pending', 'approved', 'rejected'])->default('none');
            $table->string('certificate_number', 100)->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->unsignedInteger('credit_balance')->default(0);
            $table->timestamps();

            $table->unique('user_id');
            $table->index('certificate_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contractor_profiles');
    }
};
