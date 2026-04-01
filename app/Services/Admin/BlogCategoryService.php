<?php

namespace App\Services\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogCategoryService
{
    public function createCategory(array $data): BlogCategory
    {
        DB::beginTransaction();

        try {
            $lang = $data['lang'] ?? default_language_code();
            unset($data['lang']);

            $data['slug'] = $this->resolveSlugForLang($data['slug'] ?? [], $data['name'] ?? [], $lang);
            $data['is_active'] = (bool) ($data['is_active'] ?? true);
            $data['sort_order'] = $data['sort_order'] ?? $this->nextSortOrder();

            $category = BlogCategory::create($data);

            DB::commit();

            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BlogCategory creation failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function updateCategory(BlogCategory $category, array $data): BlogCategory
    {
        DB::beginTransaction();

        try {
            $lang = $data['lang'] ?? default_language_code();
            unset($data['lang']);

            // Merge translatable fields with existing data
            $data['name'] = array_merge($category->name ?? [], $data['name'] ?? []);
            $data['description'] = array_merge($category->description ?? [], $data['description'] ?? []);
            $data['meta_description'] = array_merge($category->meta_description ?? [], $data['meta_description'] ?? []);

            // Resolve slug only for edited language, merge with existing
            $userSlug = trim($data['slug'][$lang] ?? '');
            $fallback = $data['name'][$lang] ?? $data['name'][default_language_code()] ?? '';
            $existingSlugs = $category->slug ?? [];
            $existingSlugs[$lang] = $this->resolveSlug($userSlug, $fallback, $lang, $category->id);
            $data['slug'] = $existingSlugs;

            $data['is_active'] = (bool) ($data['is_active'] ?? false);

            $category->update($data);

            DB::commit();

            return $category->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BlogCategory update failed', ['id' => $category->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function deleteCategory(BlogCategory $category): void
    {
        DB::beginTransaction();

        try {
            $category->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BlogCategory deletion failed', ['id' => $category->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function reorder(array $items): void
    {
        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                BlogCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BlogCategory reorder failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function toggleActive(BlogCategory $category): BlogCategory
    {
        try {
            $category->is_active = ! $category->is_active;
            $category->save();

            return $category;
        } catch (\Exception $e) {
            Log::error('BlogCategory toggle active failed', ['id' => $category->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    private function nextSortOrder(): int
    {
        return (int) BlogCategory::max('sort_order') + 1;
    }

    private function resolveSlugForLang(array $slugs, array $names, string $lang, ?int $ignoreId = null): array
    {
        $userSlug = trim($slugs[$lang] ?? '');
        $fallback = $names[$lang] ?? '';

        return [$lang => $this->resolveSlug($userSlug, $fallback, $lang, $ignoreId)];
    }

    private function resolveSlug(string $slug, string $fallback, string $locale, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $fallback);

        if ($base === '') {
            $base = 'category';
        }

        $final = $base;
        $i = 1;

        while (
            BlogCategory::where("slug->{$locale}", $final)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $final = $base . '-' . $i++;
        }

        return $final;
    }
}
