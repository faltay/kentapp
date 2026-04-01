<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite'da (testler) orijinal migration zaten string olarak oluşturur, sadece MySQL'de dönüştür
        if (DB::getDriverName() !== 'sqlite') {
            // Önce kolon tipini değiştir (JSON → VARCHAR/TEXT)
            DB::statement('ALTER TABLE `restaurants` MODIFY `name` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `restaurants` MODIFY `description` TEXT NULL');

            // JSON verileri düz string'e dönüştür
            DB::table('restaurants')->get()->each(function ($restaurant) {
                $name = json_decode($restaurant->name, true);
                $description = json_decode($restaurant->description, true);

                DB::table('restaurants')->where('id', $restaurant->id)->update([
                    'name' => is_array($name) ? ($name['en'] ?? $name[array_key_first($name)] ?? '') : ($restaurant->name ?? ''),
                    'description' => is_array($description) ? ($description['en'] ?? $description[array_key_first($description)] ?? null) : $restaurant->description,
                ]);
            });
        }
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
        });
    }
};
