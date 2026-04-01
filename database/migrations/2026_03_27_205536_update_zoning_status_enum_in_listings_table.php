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
        \DB::statement("ALTER TABLE listings MODIFY COLUMN zoning_status ENUM(
            'ada','a_lejantli','arazi','bag_bahce','depo_antrepo','egitim',
            'enerji_depolama','konut','kulturel_tesis','muhtelif','ozel_kullanim',
            'saglik','sanayi','sera','sit_alani','spor_alani','tarla','tarla_bag',
            'ticari','ticari_konut','toplu_konut','turizm','turizm_konut',
            'turizm_ticari','villa','zeytinlik'
        ) NULL");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE listings MODIFY COLUMN zoning_status ENUM(
            'residential','commercial','mixed','unplanned'
        ) NULL");
    }
};
