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
            $table->enum('agreement_model', [
                'kat_karsiligi',
                'para_karsiligi',
                'karma_para_kat',
                'hasilat_paylasimli',
                'yap_islet_devret',
                'kismi_satis_kat',
            ])->nullable()->after('zoning_status');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('agreement_model');
        });
    }
};
