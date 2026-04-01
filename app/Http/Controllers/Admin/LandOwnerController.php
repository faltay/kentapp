<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreLandOwnerRequest;
use App\Http\Requests\Admin\UpdateLandOwnerRequest;
use App\Models\User;
use App\Services\Admin\LandOwnerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LandOwnerController extends BaseController
{
    public function __construct(private LandOwnerService $landOwnerService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $landOwners = User::landOwners()
                ->with('landOwnerProfile')
                ->select('users.*');

            return DataTables::of($landOwners)
                ->addColumn('listings_count', fn($u) => $u->listings()->count())
                ->addColumn('status', function ($u) {
                    if ($u->is_suspended) {
                        return '<span class="badge bg-red-lt">' . __('admin.users.suspended') . '</span>';
                    }
                    return $u->is_active
                        ? '<span class="badge bg-green-lt">' . __('common.active') . '</span>'
                        : '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>';
                })
                ->addColumn('actions', fn($u) => view('admin.land-owners.partials.actions', ['landOwner' => $u])->render())
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.land-owners.index');
    }

    public function create()
    {
        return view('admin.land-owners.create');
    }

    public function store(StoreLandOwnerRequest $request): JsonResponse
    {
        try {
            $landOwner = $this->landOwnerService->createLandOwner($request->validated());

            return $this->created(
                __('admin.land_owners.created_successfully'),
                ['redirect_url' => route('admin.land-owners.show', $landOwner)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.land_owners.creation_failed'), $e);
        }
    }

    public function show(User $landOwner)
    {
        abort_unless($landOwner->isLandOwner(), 404);

        $landOwner->load([
            'landOwnerProfile',
            'creditTransactions' => fn($q) => $q->latest()->limit(15),
        ]);

        $listings = $landOwner->listings()->latest()->limit(10)->get();

        return view('admin.land-owners.show', compact('landOwner', 'listings'));
    }

    public function edit(User $landOwner)
    {
        abort_unless($landOwner->isLandOwner(), 404);

        $landOwner->load('landOwnerProfile');

        return view('admin.land-owners.edit', compact('landOwner'));
    }

    public function update(UpdateLandOwnerRequest $request, User $landOwner): JsonResponse
    {
        abort_unless($landOwner->isLandOwner(), 404);

        try {
            $this->landOwnerService->updateLandOwner($landOwner, $request->validated());

            return $this->success(
                __('admin.land_owners.updated_successfully'),
                ['redirect_url' => route('admin.land-owners.show', $landOwner)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.land_owners.update_failed'), $e);
        }
    }

    public function destroy(User $landOwner): JsonResponse
    {
        abort_unless($landOwner->isLandOwner(), 404);

        try {
            $this->landOwnerService->deleteLandOwner($landOwner);

            return $this->success(__('admin.land_owners.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Land owner deletion failed', ['error' => $e->getMessage(), 'user_id' => $landOwner->id]);

            return $this->error(__('admin.land_owners.deletion_failed'), $e);
        }
    }
}
