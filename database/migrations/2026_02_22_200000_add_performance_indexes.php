<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // İndeksler zaten create_subscriptions_table migration'ında tanımlı
    }

    public function down(): void
    {
        //
    }
};
