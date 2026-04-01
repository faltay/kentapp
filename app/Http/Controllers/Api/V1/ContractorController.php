<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Listing;
use App\Models\Review;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractorController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $users = User::whereIn('type', [User::TYPE_CONTRACTOR, User::TYPE_AGENT])
            ->where('is_active', true)
            ->where('is_suspended', false)
            ->where(fn ($q) =>
                $q->whereHas('contractorProfile', fn ($q2) => $q2->where('certificate_status', 'approved'))
                  ->orWhereHas('agentProfile',    fn ($q2) => $q2->where('certificate_status', 'approved'))
            )
            ->with(['contractorProfile', 'agentProfile'])
            ->when($request->search, fn ($q, $v) =>
                $q->where('name', 'like', "%{$v}%")
                  ->orWhereHas('contractorProfile', fn ($q2) =>
                      $q2->where('company_name', 'like', "%{$v}%")
                  )
            )
            ->when($request->type, fn ($q, $v) => $q->where('type', $v))
            ->when($request->integer('exclude_notified_for'), function ($q, $listingId) {
                $notifiedIds = UserNotification::where('type', 'listing_invite')
                    ->whereJsonContains('data->listing_id', $listingId)
                    ->pluck('user_id');
                $q->whereNotIn('id', $notifiedIds);
            })
            ->paginate($request->integer('per_page', 20));

        return $this->success([
            'contractors' => collect($users->items())->map(fn ($u) => $this->formatContractor($u)),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        if (! in_array($user->type, [User::TYPE_CONTRACTOR, User::TYPE_AGENT])) {
            return $this->notFound('Kullanıcı bulunamadı.');
        }

        $reviews = Review::where('reviewed_id', $user->id)
            ->where('status', Review::STATUS_APPROVED)
            ->with('reviewer:id,name')
            ->latest()
            ->limit(10)
            ->get();

        $avgRating   = $reviews->avg('rating');
        $activeCount = Listing::where('user_id', $user->id)->where('status', Listing::STATUS_ACTIVE)->count();

        $data = $this->formatContractor($user);
        $data['stats']   = [
            'active_listings' => $activeCount,
            'review_count'    => $reviews->count(),
            'avg_rating'      => $avgRating ? round($avgRating, 1) : null,
        ];
        $data['reviews'] = $reviews->map(fn ($r) => [
            'id'         => $r->id,
            'rating'     => $r->rating,
            'comment'    => $r->comment,
            'reviewer'   => ['id' => $r->reviewer?->id, 'name' => $r->reviewer?->name],
            'created_at' => $r->created_at->toISOString(),
        ]);

        $profile   = $user->isContractor() ? $user->contractorProfile : $user->agentProfile;
        $certMedia = $profile?->getFirstMedia('authority_certificate');
        $data['certificate_file'] = $certMedia ? ['url' => $certMedia->getUrl()] : null;

        return $this->success(['contractor' => $data]);
    }

    private function formatContractor(User $user): array
    {
        $profile = $user->isContractor() ? $user->contractorProfile : $user->agentProfile;

        return [
            'id'                 => $user->id,
            'name'               => $user->name,
            'type'               => $user->type,
            'company_name'       => $profile?->company_name,
            'authorized_name'    => $profile?->authorized_name,
            'company_phone'      => $profile?->company_phone,
            'company_email'      => $profile?->company_email,
            'certificate_status' => $profile?->certificate_status ?? 'none',
        ];
    }
}
