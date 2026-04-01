<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('agent_profiles', function (Blueprint $table) {
            $table->dropColumn('tc_number');
            $table->string('authorized_name', 255)->nullable()->after('company_name');
            $table->string('company_phone', 30)->nullable()->after('authorized_name');
            $table->string('company_email', 255)->nullable()->after('company_phone');
            $table->text('company_address')->nullable()->after('company_email');
            $table->enum('certificate_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->after('working_neighborhoods');
            $table->string('certificate_number', 100)->nullable()->after('certificate_status');

            $table->index('certificate_status');
        });
    }

    public function down(): void
    {
        Schema::table('agent_profiles', function (Blueprint $table) {
            $table->dropIndex(['certificate_status']);
            $table->dropColumn(['authorized_name', 'company_phone', 'company_email', 'company_address', 'certificate_status', 'certificate_number']);
            $table->string('tc_number', 11)->nullable()->after('user_id');
        });
    }
};
