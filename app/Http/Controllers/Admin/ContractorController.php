<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreContractorRequest;
use App\Http\Requests\Admin\UpdateContractorRequest;
use App\Models\User;
use App\Services\Admin\ContractorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ContractorController extends BaseController
{
    public function __construct(private ContractorService $contractorService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $contractors = User::contractors()
                ->with('contractorProfile')
                ->select('users.*');

            return DataTables::of($contractors)
                ->addColumn('company_name', fn($u) => $u->contractorProfile?->company_name ?? '—')
                ->addColumn('credit_balance', fn($u) => $u->contractorProfile?->credit_balance ?? 0)
                ->addColumn('certificate_status', fn($u) => view('admin.contractors.partials.certificate-badge', ['profile' => $u->contractorProfile])->render())
                ->addColumn('status', function ($u) {
                    if ($u->is_suspended) {
                        return '<span class="badge bg-red-lt">' . __('admin.users.suspended') . '</span>';
                    }
                    return $u->is_active
                        ? '<span class="badge bg-green-lt">' . __('common.active') . '</span>'
                        : '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>';
                })
                ->addColumn('actions', fn($u) => view('admin.contractors.partials.actions', ['contractor' => $u])->render())
                ->rawColumns(['certificate_status', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.contractors.index');
    }

    public function create()
    {
        return view('admin.contractors.create');
    }

    public function store(StoreContractorRequest $request): JsonResponse
    {
        try {
            $contractor = $this->contractorService->createContractor($request->validated());

            if ($request->hasFile('certificate_file')) {
                $contractor->contractorProfile
                    ->addMediaFromRequest('certificate_file')
                    ->toMediaCollection('authority_certificate');
            }

            return $this->created(
                __('admin.contractors.created_successfully'),
                ['redirect_url' => route('admin.contractors.show', $contractor)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.contractors.creation_failed'), $e);
        }
    }

    public function show(User $contractor)
    {
        abort_unless($contractor->isContractor(), 404);

        $contractor->load(['contractorProfile', 'creditTransactions' => fn($q) => $q->latest()->limit(10)]);

        $recentViews = $contractor->listingViews()
            ->with('listing')
            ->latest('viewed_at')
            ->limit(5)
            ->get();

        return view('admin.contractors.show', compact('contractor', 'recentViews'));
    }

    public function edit(User $contractor)
    {
        abort_unless($contractor->isContractor(), 404);

        $contractor->load('contractorProfile');

        return view('admin.contractors.edit', compact('contractor'));
    }

    public function update(UpdateContractorRequest $request, User $contractor): JsonResponse
    {
        abort_unless($contractor->isContractor(), 404);

        try {
            $this->contractorService->updateContractor($contractor, $request->validated());

            $profile = $contractor->contractorProfile;

            if ($request->hasFile('certificate_file')) {
                $profile->addMediaFromRequest('certificate_file')
                    ->toMediaCollection('authority_certificate');
            } elseif ($request->input('remove_certificate') == '1') {
                $profile->clearMediaCollection('authority_certificate');
            }

            return $this->success(
                __('admin.contractors.updated_successfully'),
                ['redirect_url' => route('admin.contractors.show', $contractor)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.contractors.update_failed'), $e);
        }
    }

    public function approveCertificate(User $contractor): JsonResponse
    {
        abort_unless($contractor->isContractor(), 404);
        $contractor->contractorProfile?->update(['certificate_status' => 'approved']);
        return $this->success('Sertifika onaylandı.');
    }

    public function rejectCertificate(User $contractor): JsonResponse
    {
        abort_unless($contractor->isContractor(), 404);

        $reason = request()->validate(['reason' => ['required', 'string', 'max:500']])['reason'];

        $contractor->contractorProfile?->update([
            'certificate_status'           => 'rejected',
            'certificate_rejection_reason' => $reason,
        ]);

        return $this->success('Sertifika reddedildi.');
    }

    public function destroy(User $contractor): JsonResponse
    {
        abort_unless($contractor->isContractor(), 404);

        try {
            $this->contractorService->deleteContractor($contractor);

            return $this->success(__('admin.contractors.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Contractor deletion failed', ['error' => $e->getMessage(), 'user_id' => $contractor->id]);

            return $this->error(__('admin.contractors.deletion_failed'), $e);
        }
    }
}
