<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'blog_category_id',
        'title',
        'slug',
        'meta_description',
        'excerpt',
        'content',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'slug' => 'array',
        'meta_description' => 'array',
        'excerpt' => 'array',
        'content' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }

    // ── Query Scopes ──────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeBySlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        $locale = $locale ?? app()->getLocale();

        return $query->where("slug->{$locale}", $slug);
    }

    public function scopeInCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('blog_category_id', $categoryId);
    }

    public function scopeTranslatedIn(Builder $query, ?string $locale = null): Builder
    {
        $locale = $locale ?? app()->getLocale();

        return $query->whereNotNull("title->{$locale}")
                     ->where("title->{$locale}", '!=', '');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getLocalizedTitleAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->title[$locale] ?? $this->title[default_language_code()] ?? '';
    }

    public function getLocalizedExcerptAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->excerpt[$locale] ?? $this->excerpt[default_language_code()] ?? '';
    }

    public function getLocalizedSlugAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->slug[$locale] ?? $this->slug[default_language_code()] ?? '';
    }

    public function getLocalizedContentAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->content[$locale] ?? $this->content[default_language_code()] ?? '';
    }

    public function getLocalizedMetaDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->meta_description[$locale] ?? $this->meta_description[default_language_code()] ?? '';
    }

    // ── Business Logic ────────────────────────────────────────────────────────

    public function isPublished(): bool
    {
        return $this->is_published
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    public function hasTranslation(string $langCode): bool
    {
        return isset($this->title[$langCode]) && trim($this->title[$langCode]) !== '';
    }

    // ── Media ─────────────────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('image');

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600)
            ->performOnCollections('image');

        $this->addMediaConversion('webp')
            ->format('webp')
            ->width(800)
            ->performOnCollections('image');
    }
}
