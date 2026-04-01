<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    // ── Constants ──────────────────────────────────────────────────────
    public const TYPE_ADMIN = 'admin';
    public const TYPE_LAND_OWNER = 'land_owner';
    public const TYPE_CONTRACTOR = 'contractor';
    public const TYPE_AGENT = 'agent';

    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
        'is_suspended',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_suspended' => 'boolean',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────

    public function contractorProfile(): HasOne
    {
        return $this->hasOne(ContractorProfile::class);
    }

    public function landOwnerProfile(): HasOne
    {
        return $this->hasOne(LandOwnerProfile::class);
    }

    public function agentProfile(): HasOne
    {
        return $this->hasOne(AgentProfile::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function listingViews(): HasMany
    {
        return $this->hasMany(ListingView::class, 'contractor_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ── Query Scopes ─────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    public function scopeLandOwners($query)
    {
        return $query->where('type', self::TYPE_LAND_OWNER);
    }

    public function scopeContractors($query)
    {
        return $query->where('type', self::TYPE_CONTRACTOR);
    }

    public function scopeAgents($query)
    {
        return $query->where('type', self::TYPE_AGENT);
    }

    // ── Business Logic ───────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function isLandOwner(): bool
    {
        return $this->type === self::TYPE_LAND_OWNER;
    }

    public function isContractor(): bool
    {
        return $this->type === self::TYPE_CONTRACTOR;
    }

    public function isAgent(): bool
    {
        return $this->type === self::TYPE_AGENT;
    }

    public function isSuspended(): bool
    {
        return (bool) $this->is_suspended;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function creditBalance(): int
    {
        return $this->contractorProfile?->credit_balance ?? 0;
    }

    public function activePlan(): ?SubscriptionPlan
    {
        return $this->subscriptions()->active()->with('plan')->latest()->first()?->plan;
    }
}
