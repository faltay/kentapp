<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // SQLite does not support adding foreign keys via ALTER TABLE reliably
        // when circular FK dependencies exist (users ↔ restaurants).
        // The FK is enforced at application level via Eloquent relationships.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('restaurant_id')
                  ->references('id')
                  ->on('restaurants')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
        });
    }
};
