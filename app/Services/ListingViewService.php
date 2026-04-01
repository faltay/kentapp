<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\ListingView;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListingViewService
{
    public function __construct(private CreditService $creditService) {}

    /**
     * İlanı açar: daha önce görüldüyse ücretsiz, ilk kez görüldüyse 1 kontör harcar.
     * Returns: ['already_unlocked' => bool, 'credits_spent' => int]
     */
    public function unlock(User $user, Listing $listing): array
    {
        $existing = ListingView::where('listing_id', $listing->id)
            ->where('contractor_id', $user->id)
            ->first();

        if ($existing) {
            return ['already_unlocked' => true, 'credits_spent' => 0];
        }

        if ($listing->status !== Listing::STATUS_ACTIVE) {
            throw new \RuntimeException('Bu ilan aktif değil.');
        }

        $balance = $this->creditService->getBalance($user);

        if ($balance < 1) {
            throw new \RuntimeException('Yetersiz kontör bakiyesi.');
        }

        DB::transaction(function () use ($user, $listing) {
            $this->creditService->spend($user, 1, $listing->id, 'İlan görüntüleme #' . $listing->id);

            ListingView::create([
                'listing_id'    => $listing->id,
                'contractor_id' => $user->id,
                'credits_spent' => 1,
                'viewed_at'     => now(),
            ]);

            $listing->increment('view_count');
        });

        return ['already_unlocked' => false, 'credits_spent' => 1];
    }

    public function isUnlocked(User $user, Listing $listing): bool
    {
        return ListingView::where('listing_id', $listing->id)
            ->where('contractor_id', $user->id)
            ->exists();
    }
}
