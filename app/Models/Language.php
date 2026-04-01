<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    protected $fillable = [
        'code',
        'name',
        'native',
        'flag',
        'direction',
        'is_active',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // -------------------------------------------------------------------------
    // Static Helpers (cached)
    // -------------------------------------------------------------------------

    public static function getActiveLanguages(): Collection
    {
        return Cache::remember('languages.active', 3600, function () {
            return static::active()->get();
        });
    }

    public static function getDefaultLanguage(): ?self
    {
        return static::getActiveLanguages()->firstWhere('is_default', true)
            ?? static::getActiveLanguages()->first();
    }

    public static function getDefaultCode(): string
    {
        return static::getDefaultLanguage()?->code ?? 'en';
    }

    public static function getActiveCodes(): array
    {
        return static::getActiveLanguages()->pluck('code')->toArray();
    }

    public static function clearCache(): void
    {
        Cache::forget('languages.active');
    }
}
