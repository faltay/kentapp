<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'content',
        'is_published',
        'is_homepage',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'slug' => 'array',
        'meta_description' => 'array',
        'content' => 'array',
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
    ];

    // ── Query Scopes ──────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeHomepage(Builder $query): Builder
    {
        return $query->where('is_homepage', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    public function scopeBySlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        $locale = $locale ?? app()->getLocale();

        return $query->where("slug->{$locale}", $slug);
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

    public function hasTranslation(string $langCode): bool
    {
        return isset($this->title[$langCode]) && trim($this->title[$langCode]) !== '';
    }
}
