<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        $defaultLang = config('app.locale', 'en');

        // 1. Drop unique index if exists (may already be dropped from partial run)
        try {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropUnique(['slug']);
            });
        } catch (\Exception $e) {
            // Index already dropped
        }

        // 2. Convert existing string slugs to JSON strings while still VARCHAR
        $posts = DB::table('posts')->select('id', 'slug')->get();
        foreach ($posts as $post) {
            DB::table('posts')
                ->where('id', $post->id)
                ->update(['slug' => json_encode([$defaultLang => $post->slug])]);
        }

        // 3. Now change column type to JSON (all values are valid JSON)
        Schema::table('posts', function (Blueprint $table) {
            $table->json('slug')->change();
        });
    }

    public function down(): void
    {
        $defaultLang = config('app.locale', 'en');

        // 1. Read JSON data before changing column type
        $posts = DB::table('posts')->select('id', 'slug')->get();
        $slugMap = [];
        foreach ($posts as $post) {
            $slugData = json_decode($post->slug, true);
            $slugMap[$post->id] = $slugData[$defaultLang] ?? array_values($slugData ?? [])[0] ?? '';
        }

        // 2. Change column back to string
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->change();
        });

        // 3. Restore flat string slugs
        foreach ($slugMap as $id => $slug) {
            DB::table('posts')->where('id', $id)->update(['slug' => $slug]);
        }

        // 4. Re-add unique index
        Schema::table('posts', function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};
