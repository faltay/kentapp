<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AgentProfile extends Model implements HasMedia
{
    use InteractsWithMedia;

    // ── Constants ──────────────────────────────────────────────────────
    public const CERTIFICATE_NONE     = 'none';
    public const CERTIFICATE_PENDING  = 'pending';
    public const CERTIFICATE_APPROVED = 'approved';
    public const CERTIFICATE_REJECTED = 'rejected';

    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'company_name',
        'authorized_name',
        'company_phone',
        'company_email',
        'company_address',
        'working_neighborhoods',
        'certificate_status',
        'certificate_number',
        'certificate_rejection_reason',
        'credit_balance',
    ];

    protected $casts = [
        'working_neighborhoods' => 'array',
        'credit_balance'        => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Media Collections ─────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('authority_certificate')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);
    }
}
