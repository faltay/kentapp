<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Listing;
use App\Models\ListingView;
use App\Services\ListingViewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListingController extends ApiController
{
    public function __construct(private ListingViewService $viewService) {}

    public function index(Request $request): JsonResponse
    {
        $query = Listing::with('user')
            ->where('status', Listing::STATUS_ACTIVE)
            ->when($request->type, fn($q, $v) => $q->where('type', $v))
            ->when($request->province, fn($q, $v) => $q->where('province', $v))
            ->when($request->district, fn($q, $v) => $q->where('district', $v))
            ->when($request->zoning_status, fn($q, $v) => $q->where('zoning_status', $v))
            ->when($request->search, function ($q, $v) {
                $q->where(function ($q2) use ($v) {
                    $q2->where('ada_no', 'like', "%{$v}%")
                        ->orWhere('parcel_no', 'like', "%{$v}%")
                        ->orWhere('neighborhood', 'like', "%{$v}%")
                        ->orWhere('district', 'like', "%{$v}%")
                        ->orWhere('province', 'like', "%{$v}%");
                });
            });

        $query->when(
            $request->sort === 'oldest',
            fn($q) => $q->oldest(),
            fn($q) => $q->latest()
        );

        $listings = $query->paginate($request->integer('per_page', 15));
        $user     = $request->user();

        // İçin hangi ilanların açık olduğunu işaretle
        $unlockedIds = $user
            ? ListingView::where('contractor_id', $user->id)
                ->whereIn('listing_id', $listings->pluck('id'))
                ->pluck('listing_id')
                ->flip()
            : collect();

        return $this->success([
            'listings' => $listings->map(fn($l) => $this->formatListing($l, $unlockedIds->has($l->id))),
            'meta'     => [
                'current_page' => $listings->currentPage(),
                'last_page'    => $listings->lastPage(),
                'total'        => $listings->total(),
                'per_page'     => $listings->perPage(),
            ],
        ]);
    }

    public function featured(Request $request): JsonResponse
    {
        $listings = Listing::with('user')
            ->where('status', Listing::STATUS_ACTIVE)
            ->where('is_featured', true)
            ->latest()
            ->paginate(10);

        $user        = $request->user();
        $unlockedIds = $user
            ? ListingView::where('contractor_id', $user->id)
                ->whereIn('listing_id', $listings->pluck('id'))
                ->pluck('listing_id')
                ->flip()
            : collect();

        return $this->success([
            'listings' => $listings->map(fn($l) => $this->formatListing($l, $unlockedIds->has($l->id))),
        ]);
    }

    public function show(Request $request, Listing $listing): JsonResponse
    {
        if ($listing->status !== Listing::STATUS_ACTIVE) {
            return $this->notFound('İlan bulunamadı.');
        }

        $user       = $request->user();
        $isUnlocked = $user ? $this->viewService->isUnlocked($user, $listing) : false;

        return $this->success([
            'listing' => $this->formatListing($listing->load('user'), $isUnlocked, true),
        ]);
    }

    public function unlock(Request $request, Listing $listing): JsonResponse
    {
        $user = $request->user();

        if (! in_array($user->type, [\App\Models\User::TYPE_CONTRACTOR, \App\Models\User::TYPE_AGENT])) {
            return $this->forbidden('Sadece müteahhit ve emlak danışmanları ilan açabilir.');
        }

        try {
            $result = $this->viewService->unlock($user, $listing);

            return $this->success([
                'already_unlocked' => $result['already_unlocked'],
                'credits_spent'    => $result['credits_spent'],
                'contact'          => $this->getContactInfo($listing),
            ], $result['already_unlocked'] ? 'İlan zaten açık.' : '1 kontör harcandı, iletişim bilgileri açıldı.');
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Listing unlock failed', ['error' => $e->getMessage()]);
            return $this->error('İlan açılamadı.');
        }
    }

    private function formatListing(Listing $listing, bool $isUnlocked, bool $withDetails = false): array
    {
        $data = [
            'id'            => $listing->id,
            'type'          => $listing->type,
            'province'      => $listing->province,
            'district'      => $listing->district,
            'neighborhood'  => $listing->neighborhood,
            'ada_no'        => $listing->ada_no,
            'parcel_no'     => $listing->parcel_no,
            'area_m2'       => $listing->area_m2,
            'is_featured'   => $listing->is_featured,
            'view_count'    => $listing->view_count,
            'expires_at'    => $listing->expires_at?->toISOString(),
            'created_at'    => $listing->created_at->toISOString(),
            'is_unlocked'   => $isUnlocked,
            'contact'       => $isUnlocked ? $this->getContactInfo($listing) : ['locked' => true],
            'photos'        => $listing->getMedia('photos')->map(fn($m) => ['id' => $m->id, 'url' => $m->getUrl()])->values(),
        ];

        if ($withDetails) {
            $data['address']          = $listing->address;
            $data['floor_count']      = $listing->floor_count;
            $data['zoning_status']    = $listing->zoning_status;
            $data['taks']             = $listing->taks;
            $data['kaks']             = $listing->kaks;
            $data['pafta']            = $listing->pafta;
            $data['gabari']           = $listing->gabari;
            $data['description']      = $listing->description;
            $data['parcel_geometry']  = $listing->parcel_geometry;
            $data['documents']        = $listing->getMedia('documents')->map(fn($m) => [
                'id'   => $m->id,
                'name' => $m->file_name,
                'url'  => $m->getUrl(),
            ])->values();
            $data['owner'] = [
                'id'   => $listing->user?->id,
                'name' => $listing->user?->name,
                'type' => $listing->user?->type,
            ];
        }

        return $data;
    }

    private function getContactInfo(Listing $listing): array
    {
        return [
            'locked' => false,
            'name'   => $listing->user?->name,
            'phone'  => $listing->user?->phone,
            'email'  => $listing->user?->email,
        ];
    }
}
