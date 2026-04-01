<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Listing;
use App\Models\ListingView;
use App\Models\User;
use App\Services\CreditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends ApiController
{
    public function __construct(private CreditService $creditService) {}

    public function index(Request $request): JsonResponse
    {
        $user  = $request->user();
        $stats = [];

        if (in_array($user->type, [User::TYPE_LAND_OWNER, User::TYPE_AGENT])) {
            $listings      = $user->listings();
            $totalListings  = $listings->count();
            $activeListings = $listings->clone()->where('status', Listing::STATUS_ACTIVE)->count();
            $totalViews     = ListingView::whereIn('listing_id', $user->listings()->pluck('id'))->count();

            $stats['as_owner'] = [
                'total_listings'  => $totalListings,
                'active_listings' => $activeListings,
                'pending_listings'=> $listings->clone()->where('status', Listing::STATUS_PENDING)->count(),
                'total_views'     => $totalViews,
            ];
        }

        if (in_array($user->type, [User::TYPE_CONTRACTOR, User::TYPE_AGENT])) {
            $unlockedCount = ListingView::where('contractor_id', $user->id)->count();
            $totalSpent    = ListingView::where('contractor_id', $user->id)->sum('credits_spent');

            $stats['as_contractor'] = [
                'credit_balance'  => $this->creditService->getBalance($user),
                'unlocked_listings'=> $unlockedCount,
                'total_credits_spent' => (int) $totalSpent,
            ];
        }

        return $this->success(['stats' => $stats]);
    }
}
