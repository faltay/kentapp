<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
        'description' => 'array',
        'meta_description' => 'array',
        'is_active' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // ── Query Scopes ──────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function scopeBySlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        $locale = $locale ?? app()->getLocale();

        return $query->where("slug->{$locale}", $slug);
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->name[$locale] ?? $this->name[default_language_code()] ?? '';
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->description[$locale] ?? $this->description[default_language_code()] ?? '';
    }

    public function getLocalizedMetaDescriptionAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->meta_description[$locale] ?? $this->meta_description[default_language_code()] ?? '';
    }

    public function getLocalizedSlugAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->slug[$locale] ?? $this->slug[default_language_code()] ?? '';
    }

    // ── Business Logic ────────────────────────────────────────────────────────

    public function hasTranslation(string $langCode): bool
    {
        return isset($this->name[$langCode]) && trim($this->name[$langCode]) !== '';
    }
}
