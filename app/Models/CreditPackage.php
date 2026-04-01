<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPackage extends Model
{
    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'name',
        'credits',
        'price',
        'currency',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'credits'   => 'integer',
        'is_active' => 'boolean',
    ];

    // ── Query Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
