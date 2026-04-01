<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreListingRequest;
use App\Http\Requests\Admin\UpdateListingRequest;
use App\Models\Listing;
use App\Models\Province;
use App\Models\User;
use App\Services\Admin\ListingService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class ListingController extends BaseController
{
    public function __construct(private ListingService $listingService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $listings = Listing::with(['user'])
                ->select('listings.*');

            return DataTables::of($listings)
                ->addColumn('owner_name', fn ($l) => $l->user?->name ?? '—')
                ->addColumn('location', fn ($l) => $l->province . ' / ' . $l->district)
                ->addColumn('type_label', fn ($l) => $l->type === Listing::TYPE_URBAN_RENEWAL
                    ? __('admin.listings.type_urban_renewal')
                    : __('admin.listings.type_land'))
                ->addColumn('status_badge', fn ($l) => view('admin.listings.partials.status-badge', ['listing' => $l])->render())
                ->addColumn('featured_badge', fn ($l) => $l->is_featured
                    ? '<span class="badge bg-yellow-lt">' . __('admin.listings.featured') . '</span>'
                    : '')
                ->addColumn('formatted_created_at', fn ($l) => $l->created_at->format('d.m.Y H:i'))
                ->addColumn('actions', fn ($l) => view('admin.listings.partials.actions', ['listing' => $l])->render())
                ->rawColumns(['status_badge', 'featured_badge', 'actions'])
                ->make(true);
        }

        return view('admin.listings.index');
    }

    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        $owners = User::whereIn('type', [User::TYPE_LAND_OWNER, User::TYPE_AGENT])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'type']);

        return view('admin.listings.create', compact('provinces', 'owners'));
    }

    public function store(StoreListingRequest $request): JsonResponse
    {
        try {
            $data = $request->safe()->except(['documents', 'photos']);
            $data['is_featured'] = $request->boolean('is_featured');

            $listing = $this->listingService->createListing(
                $data,
                $request->file('documents', []),
                $request->file('photos', [])
            );

            return $this->created(
                __('admin.listings.created_successfully'),
                ['redirect_url' => route('admin.listings.show', $listing)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.creation_failed'), $e);
        }
    }

    public function edit(Listing $listing)
    {
        $listing->load('media');
        $provinces = Province::orderBy('name')->get();
        $owners = User::whereIn('type', [User::TYPE_LAND_OWNER, User::TYPE_AGENT])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'type']);

        return view('admin.listings.edit', compact('listing', 'provinces', 'owners'));
    }

    public function update(UpdateListingRequest $request, Listing $listing): JsonResponse
    {
        try {
            $data = $request->safe()->except(['documents', 'photos', 'remove_documents', 'remove_photos']);
            $data['is_featured'] = $request->boolean('is_featured');

            if (isset($data['parcel_geometry']) && $data['parcel_geometry'] !== '') {
                $data['parcel_geometry'] = json_decode($data['parcel_geometry'], true);
            } else {
                $data['parcel_geometry'] = null;
            }

            $this->listingService->updateListing(
                $listing,
                $data,
                $request->file('documents', []),
                $request->file('photos', []),
                $request->input('remove_documents', []),
                $request->input('remove_photos', [])
            );

            return $this->success(__('admin.listings.updated_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.update_failed'), $e);
        }
    }

    public function show(Listing $listing)
    {
        $listing->load(['user', 'user.landOwnerProfile', 'views', 'reviews']);

        return view('admin.listings.show', compact('listing'));
    }

    public function approve(Listing $listing): JsonResponse
    {
        try {
            $this->listingService->updateStatus($listing, Listing::STATUS_ACTIVE);

            return $this->success(__('admin.listings.approved_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.approval_failed'), $e);
        }
    }

    public function reject(Listing $listing): JsonResponse
    {
        try {
            $this->listingService->updateStatus($listing, Listing::STATUS_REJECTED);

            return $this->success(__('admin.listings.rejected_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.rejection_failed'), $e);
        }
    }

    public function passive(Listing $listing): JsonResponse
    {
        try {
            $this->listingService->updateStatus($listing, Listing::STATUS_PASSIVE);

            return $this->success(__('admin.listings.passived_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.passive_failed'), $e);
        }
    }

    public function toggleFeatured(Listing $listing): JsonResponse
    {
        try {
            $updated = $this->listingService->toggleFeatured($listing);

            $message = $updated->is_featured
                ? __('admin.listings.featured_enabled')
                : __('admin.listings.featured_disabled');

            return $this->success($message);
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.featured_failed'), $e);
        }
    }

    public function destroy(Listing $listing): JsonResponse
    {
        try {
            $this->listingService->deleteListing($listing);

            return $this->success(__('admin.listings.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.listings.deletion_failed'), $e);
        }
    }
}
