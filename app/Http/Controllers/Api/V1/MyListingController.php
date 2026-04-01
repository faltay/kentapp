<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreListingRequest;
use App\Http\Requests\Api\V1\UpdateListingRequest;
use App\Models\Listing;
use App\Models\User;
use App\Services\Admin\ListingService;
use App\Services\CreditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MyListingController extends ApiController
{
    public function __construct(
        private ListingService $listingService,
        private CreditService $creditService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $listings = $request->user()->listings()
            ->withCount('views')
            ->latest()
            ->paginate(15);

        return $this->success([
            'listings' => $listings->map(fn($l) => $this->format($l)),
            'meta'     => [
                'current_page' => $listings->currentPage(),
                'last_page'    => $listings->lastPage(),
                'total'        => $listings->total(),
            ],
        ]);
    }

    public function store(StoreListingRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! in_array($user->type, [User::TYPE_LAND_OWNER, User::TYPE_AGENT])) {
            return $this->forbidden('Sadece arsa sahipleri ve emlak danışmanları ilan oluşturabilir.');
        }

        $requestFeatured = $request->boolean('request_featured');

        if ($requestFeatured && $this->creditService->getBalance($user) < 10) {
            return $this->error('Vitrin için yeterli kontörünüz yok. En az 10 kontör gereklidir.', 422);
        }

        try {
            $data = array_merge($request->safe()->except(['documents', 'photos', 'request_featured']), [
                'user_id' => $user->id,
                'status'  => Listing::STATUS_PENDING,
                'type'    => $request->input('type', Listing::TYPE_URBAN_RENEWAL),
            ]);

            if ($requestFeatured) {
                $data['is_featured']          = true;
                $data['featured_credit_spent'] = true;
            }

            $listing = $this->listingService->createListing(
                $data,
                $request->file('documents', []),
                $request->file('photos', [])
            );

            if ($requestFeatured) {
                $this->creditService->spend($user, 10, $listing->id, 'Vitrin ücreti — ilan #' . $listing->id);
            }

            return $this->created(['listing' => $this->format($listing)], 'İlan oluşturuldu, onay bekliyor.');
        } catch (\Exception $e) {
            Log::error('API listing store failed', ['error' => $e->getMessage()]);
            return $this->error('İlan oluşturulamadı.');
        }
    }

    public function show(Request $request, Listing $listing): JsonResponse
    {
        if ($listing->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        return $this->success(['listing' => $this->format($listing->load('media'), true)]);
    }

    public function update(UpdateListingRequest $request, Listing $listing): JsonResponse
    {
        if ($listing->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        try {
            $data = $request->safe()->except(['documents', 'photos', 'remove_documents', 'remove_photos']);

            $this->listingService->updateListing(
                $listing,
                $data,
                $request->file('documents', []),
                $request->file('photos', []),
                $request->input('remove_documents', []),
                $request->input('remove_photos', [])
            );

            return $this->success(['listing' => $this->format($listing->fresh())], 'İlan güncellendi.');
        } catch (\Exception $e) {
            Log::error('API listing update failed', ['error' => $e->getMessage()]);
            return $this->error('İlan güncellenemedi.');
        }
    }

    public function destroy(Request $request, Listing $listing): JsonResponse
    {
        if ($listing->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        try {
            $this->listingService->deleteListing($listing);
            return $this->success(message: 'İlan silindi.');
        } catch (\Exception $e) {
            Log::error('API listing destroy failed', ['error' => $e->getMessage()]);
            return $this->error('İlan silinemedi.');
        }
    }

    public function views(Request $request, Listing $listing): JsonResponse
    {
        if ($listing->user_id !== $request->user()->id) {
            return $this->forbidden();
        }

        $views = $listing->views()->with('contractor')->latest('viewed_at')->paginate(20);

        return $this->success([
            'views' => $views->map(fn($v) => [
                'id'            => $v->id,
                'contractor'    => ['id' => $v->contractor?->id, 'name' => $v->contractor?->name],
                'credits_spent' => $v->credits_spent,
                'viewed_at'     => $v->viewed_at?->toISOString(),
            ]),
            'total' => $listing->view_count,
        ]);
    }

    private function format(Listing $listing, bool $withDetails = false): array
    {
        $data = [
            'id'          => $listing->id,
            'type'        => $listing->type,
            'status'      => $listing->status,
            'province'    => $listing->province,
            'district'    => $listing->district,
            'neighborhood'=> $listing->neighborhood,
            'ada_no'      => $listing->ada_no,
            'parcel_no'   => $listing->parcel_no,
            'area_m2'     => $listing->area_m2,
            'is_featured' => $listing->is_featured,
            'view_count'  => $listing->view_count,
            'expires_at'  => $listing->expires_at?->toISOString(),
            'created_at'  => $listing->created_at->toISOString(),
        ];

        if ($withDetails) {
            $data['address']       = $listing->address;
            $data['floor_count']   = $listing->floor_count;
            $data['zoning_status'] = $listing->zoning_status;
            $data['taks']          = $listing->taks;
            $data['kaks']          = $listing->kaks;
            $data['pafta']         = $listing->pafta;
            $data['gabari']        = $listing->gabari;
            $data['description']   = $listing->description;
            $data['photos']           = $listing->getMedia('photos')->map(fn($m) => ['id' => $m->id, 'url' => $m->getUrl()])->values();
            $data['documents']        = $listing->getMedia('documents')->map(fn($m) => ['id' => $m->id, 'name' => $m->file_name, 'url' => $m->getUrl()])->values();
            $data['parcel_geometry']  = $listing->parcel_geometry;
        }

        return $data;
    }
}
