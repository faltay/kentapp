<?php

namespace App\Services\Admin;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostService
{
    public function createPost(array $data, int $userId): Post
    {
        DB::beginTransaction();

        try {
            $lang = $data['lang'] ?? default_language_code();
            unset($data['lang']);

            $data['user_id'] = $userId;
            $data['slug'] = $this->resolveSlugForLang($data['slug'] ?? [], $data['title'] ?? [], $lang);
            $data['is_published'] = (bool) ($data['is_published'] ?? false);
            $data['published_at'] = $data['is_published']
                ? ($data['published_at'] ?? now())
                : null;

            $post = Post::create($data);

            if (! empty($data['image'])) {
                $post->addMedia($data['image'])->toMediaCollection('image');
            }

            DB::commit();

            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post creation failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function updatePost(Post $post, array $data): Post
    {
        DB::beginTransaction();

        try {
            $lang = $data['lang'] ?? default_language_code();
            unset($data['lang']);

            // Merge translatable fields with existing data
            $data['title'] = array_merge($post->title ?? [], $data['title'] ?? []);
            $data['meta_description'] = array_merge($post->meta_description ?? [], $data['meta_description'] ?? []);
            $data['excerpt'] = array_merge($post->excerpt ?? [], $data['excerpt'] ?? []);
            $data['content'] = array_merge($post->content ?? [], $data['content'] ?? []);

            // Resolve slug only for edited language, merge with existing
            $userSlug = trim($data['slug'][$lang] ?? '');
            $fallback = $data['title'][$lang] ?? $data['title'][default_language_code()] ?? '';
            $existingSlugs = $post->slug ?? [];
            $existingSlugs[$lang] = $this->resolveSlug($userSlug, $fallback, $lang, $post->id);
            $data['slug'] = $existingSlugs;

            $data['is_published'] = (bool) ($data['is_published'] ?? false);

            if ($data['is_published'] && ! $post->is_published) {
                $data['published_at'] = $data['published_at'] ?? now();
            } elseif (! $data['is_published']) {
                $data['published_at'] = null;
            }

            if (! empty($data['image'])) {
                $post->addMedia($data['image'])->toMediaCollection('image');
            }
            unset($data['image']);

            $post->update($data);

            DB::commit();

            return $post->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post update failed', ['id' => $post->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function deletePost(Post $post): void
    {
        DB::beginTransaction();

        try {
            $post->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Post deletion failed', ['id' => $post->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function togglePublish(Post $post): Post
    {
        try {
            $post->is_published = ! $post->is_published;
            $post->published_at = $post->is_published ? ($post->published_at ?? now()) : null;
            $post->save();

            return $post;
        } catch (\Exception $e) {
            Log::error('Post toggle publish failed', ['id' => $post->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    /**
     * Tek dil için slug çözümle.
     */
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
            $base = 'post';
        }

        $final = $base;
        $i = 1;

        while (
            Post::where("slug->{$locale}", $final)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $final = $base . '-' . $i++;
        }

        return $final;
    }
}
