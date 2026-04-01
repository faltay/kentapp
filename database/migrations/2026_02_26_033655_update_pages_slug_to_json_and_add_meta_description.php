<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        // Convert existing string slugs to JSON
        foreach (DB::table('pages')->get() as $page) {
            $defaultCode = app(\App\Models\Language::class)::getDefaultCode();
            DB::table('pages')->where('id', $page->id)->update([
                'slug' => json_encode([$defaultCode => $page->slug]),
            ]);
        }

        Schema::table('pages', function (Blueprint $table) {
            $table->json('slug')->change();
            $table->json('meta_description')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('meta_description');
            $table->string('slug')->change();
            $table->unique('slug');
        });
    }
};
