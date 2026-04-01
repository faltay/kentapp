<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    // ── Constants ──────────────────────────────────────────────────────
    public const PROVIDER_IYZICO        = 'iyzico';
    public const PROVIDER_BANK_TRANSFER = 'bank_transfer';
    public const PROVIDER_GOOGLE_PAY    = 'google_pay';
    public const PROVIDER_APPLE_PAY     = 'apple_pay';

    public const STATUS_PENDING             = 'pending';
    public const STATUS_SUCCEEDED           = 'succeeded';
    public const STATUS_FAILED              = 'failed';
    public const STATUS_REFUNDED            = 'refunded';
    public const STATUS_PARTIALLY_REFUNDED  = 'partially_refunded';

    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'credit_package_id',
        'provider',
        'provider_payment_id',
        'provider_refund_id',
        'amount',
        'currency',
        'credits',
        'status',
        'refunded_amount',
        'refunded_at',
        'description',
        'paid_at',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'credits'         => 'integer',
        'paid_at'         => 'datetime',
        'refunded_at'     => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creditPackage(): BelongsTo
    {
        return $this->belongsTo(CreditPackage::class);
    }

    // ── Query Scopes ──────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeSucceeded($query)
    {
        return $query->where('status', self::STATUS_SUCCEEDED);
    }

    public function scopeRefunded($query)
    {
        return $query->whereIn('status', [self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED]);
    }

    // ── Business Logic ────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRefunded(): bool
    {
        return in_array($this->status, [self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED]);
    }

    public function netAmount(): float
    {
        return (float) $this->amount - (float) ($this->refunded_amount ?? 0);
    }

    public function markRefunded(float $refundAmount, string $refundId): void
    {
        $isFullRefund = $refundAmount >= (float) $this->amount;

        $this->update([
            'status'             => $isFullRefund ? self::STATUS_REFUNDED : self::STATUS_PARTIALLY_REFUNDED,
            'refunded_amount'    => $refundAmount,
            'provider_refund_id' => $refundId,
            'refunded_at'        => now(),
        ]);
    }
}
