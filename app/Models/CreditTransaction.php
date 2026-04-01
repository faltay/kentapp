<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends Model
{
    // ── Constants ──────────────────────────────────────────────────────
    public const TYPE_PURCHASE = 'purchase';
    public const TYPE_SPEND    = 'spend';
    public const TYPE_REFUND   = 'refund';

    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'listing_id',
        'type',
        'amount',
        'balance_after',
        'description',
    ];

    protected $casts = [
        'amount'        => 'integer',
        'balance_after' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    // ── Query Scopes ─────────────────────────────────────────────────────

    public function scopePurchases($query)
    {
        return $query->where('type', self::TYPE_PURCHASE);
    }

    public function scopeSpends($query)
    {
        return $query->where('type', self::TYPE_SPEND);
    }
}
