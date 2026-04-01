<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class () extends Migration {
    public function up(): void
    {
        $branches = DB::table('branches')->whereNull('slug')->get(['id', 'restaurant_id', 'name']);

        foreach ($branches as $branch) {
            $name = json_decode($branch->name, true) ?? [];
            $raw  = $name['en'] ?? $name['tr'] ?? reset($name) ?? '';
            $base = Str::slug($raw) ?: 'branch';

            $slug    = $base;
            $counter = 1;

            while (
                DB::table('branches')
                    ->where('restaurant_id', $branch->restaurant_id)
                    ->where('slug', $slug)
                    ->where('id', '!=', $branch->id)
                    ->exists()
            ) {
                $slug = $base . '-' . $counter++;
            }

            DB::table('branches')->where('id', $branch->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Geri alınamaz
    }
};
