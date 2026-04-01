<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandOwnerProfile extends Model
{
    // ── Fillable ────────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'tc_number',
        'credit_balance',
    ];

    protected $casts = [
        'credit_balance' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
