<?php

namespace App\Services\Admin;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PageService
{
    public function createPage(array $data): Page
    {
        DB::beginTransaction();

        try {
            $lang = $data['lang'] ?? default_language_code();
            unset($data['lang']);

            $data['slug'] = $this->resolveSlugForLang($data['slug'] ?? [], $data['title'] ?? [], $lang);
            $data['is_published'] = (bool) ($data['is_published'] ?? false);
            $data['is_homepage'] = (bool) ($data['is_homepage'] ?? false);

            if ($data['is_homepage']) {
                Page::where('is_homepage', true)->update(['is_homepage' => false]);
            }

            $page = Page::create($data);

            DB::commit();

            return $page;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page creation failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function updatePage(Page $page, array $data): Page
    {
        DB::beginTransaction();

        try {
            $lang = $data['lang'] ?? default_language_code();
            unset($data['lang']);

            // Merge translatable fields with existing data
            $data['title'] = array_merge($page->title ?? [], $data['title'] ?? []);
            $data['meta_description'] = array_merge($page->meta_description ?? [], $data['meta_description'] ?? []);
            $data['content'] = array_merge($page->content ?? [], $data['content'] ?? []);

            // Resolve slug only for edited language, merge with existing
            $userSlug = trim($data['slug'][$lang] ?? '');
            $fallback = $data['title'][$lang] ?? $data['title'][default_language_code()] ?? '';
            $existingSlugs = $page->slug ?? [];
            $existingSlugs[$lang] = $this->resolveSlug($userSlug, $fallback, $lang, $page->id);
            $data['slug'] = $existingSlugs;

            $data['is_published'] = (bool) ($data['is_published'] ?? false);
            $data['is_homepage'] = (bool) ($data['is_homepage'] ?? false);

            if ($data['is_homepage']) {
                Page::where('is_homepage', true)->where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }

            $page->update($data);

            DB::commit();

            return $page->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page update failed', ['id' => $page->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function deletePage(Page $page): void
    {
        DB::beginTransaction();

        try {
            $page->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Page deletion failed', ['id' => $page->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    private function resolveSlugForLang(array $slugs, array $titles, string $lang, ?int $ignoreId = null): array
    {
        $userSlug = trim($slugs[$lang] ?? '');
        $fallback = $titles[$lang] ?? '';

        return [$lang => $this->resolveSlug($userSlug, $fallback, $lang, $ignoreId)];
    }

    private function resolveSlug(string $slug, string $fallback, string $locale, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $fallback);

        if ($base === '') {
            $base = 'page';
        }

        $final = $base;
        $i = 1;

        while (
            Page::where("slug->{$locale}", $final)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $final = $base . '-' . $i++;
        }

        return $final;
    }
}
