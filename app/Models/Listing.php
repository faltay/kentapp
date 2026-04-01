<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Listing extends Model implements HasMedia
{
    use InteractsWithMedia;

    // ── Constants ──────────────────────────────────────────────────────
    public const TYPE_URBAN_RENEWAL = 'urban_renewal';
    public const TYPE_LAND          = 'land';

    public const STATUS_DRAFT   = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE  = 'active';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PASSIVE = 'passive';

    public const ZONING_ADA             = 'ada';
    public const ZONING_A_LEJANTLI      = 'a_lejantli';
    public const ZONING_ARAZI           = 'arazi';
    public const ZONING_BAG_BAHCE       = 'bag_bahce';
    public const ZONING_DEPO_ANTREPO    = 'depo_antrepo';
    public const ZONING_EGITIM          = 'egitim';
    public const ZONING_ENERJI_DEPOLAMA = 'enerji_depolama';
    public const ZONING_KONUT           = 'konut';
    public const ZONING_KULTUREL_TESIS  = 'kulturel_tesis';
    public const ZONING_MUHTELIF        = 'muhtelif';
    public const ZONING_OZEL_KULLANIM   = 'ozel_kullanim';
    public const ZONING_SAGLIK          = 'saglik';
    public const ZONING_SANAYI          = 'sanayi';
    public const ZONING_SERA            = 'sera';
    public const ZONING_SIT_ALANI       = 'sit_alani';
    public const ZONING_SPOR_ALANI      = 'spor_alani';
    public const ZONING_TARLA           = 'tarla';
    public const ZONING_TARLA_BAG       = 'tarla_bag';
    public const ZONING_TICARI          = 'ticari';
    public const ZONING_TICARI_KONUT    = 'ticari_konut';
    public const ZONING_TOPLU_KONUT     = 'toplu_konut';
    public const ZONING_TURIZM          = 'turizm';
    public const ZONING_TURIZM_KONUT    = 'turizm_konut';
    public const ZONING_TURIZM_TICARI   = 'turizm_ticari';
    public const ZONING_VILLA           = 'villa';
    public const ZONING_ZEYTINLIK       = 'zeytinlik';

    public const AGREEMENT_KAT_KARSILIGI     = 'kat_karsiligi';
    public const AGREEMENT_PARA_KARSILIGI    = 'para_karsiligi';
    public const AGREEMENT_KARMA_PARA_KAT   = 'karma_para_kat';
    public const AGREEMENT_HASILAT_PAYLASIMLI = 'hasilat_paylasimli';
    public const AGREEMENT_YAP_ISLET_DEVRET = 'yap_islet_devret';
    public const AGREEMENT_KISMI_SATIS_KAT  = 'kismi_satis_kat';

    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'province',
        'district',
        'neighborhood',
        'address',
        'ada_no',
        'parcel_no',
        'pafta',
        'area_m2',
        'floor_count',
        'zoning_status',
        'agreement_model',
        'taks',
        'kaks',
        'gabari',
        'description',
        'is_featured',
        'featured_credit_spent',
        'view_count',
        'expires_at',
        'parcel_geometry',
    ];

    protected $casts = [
        'is_featured'           => 'boolean',
        'featured_credit_spent' => 'boolean',
        'area_m2'          => 'decimal:2',
        'taks'             => 'decimal:2',
        'kaks'             => 'decimal:2',
        'expires_at'       => 'datetime',
        'view_count'       => 'integer',
        'parcel_geometry'  => 'array',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ListingView::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    // ── Query Scopes ─────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ── Business Logic ───────────────────────────────────────────────────

    public function isViewedBy(int $contractorId): bool
    {
        return $this->views()->where('contractor_id', $contractorId)->exists();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE   => 'bg-green-lt',
            self::STATUS_PENDING  => 'bg-yellow-lt',
            self::STATUS_REJECTED => 'bg-red-lt',
            self::STATUS_PASSIVE  => 'bg-secondary-lt',
            default               => 'bg-secondary-lt',
        };
    }

    // ── Media Collections ─────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);

        $this->addMediaCollection('photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
