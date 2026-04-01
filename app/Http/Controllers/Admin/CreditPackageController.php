<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreCreditPackageRequest;
use App\Http\Requests\Admin\UpdateCreditPackageRequest;
use App\Models\CreditPackage;
use App\Services\Admin\CreditPackageService;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class CreditPackageController extends BaseController
{
    public function __construct(private CreditPackageService $packageService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $packages = CreditPackage::query();

            return DataTables::of($packages)
                ->addColumn('status', fn ($p) => $p->is_active
                    ? '<span class="badge bg-green-lt">' . __('common.active') . '</span>'
                    : '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>')
                ->addColumn('formatted_price', fn ($p) => number_format($p->price, 2) . ' ' . $p->currency)
                ->addColumn('formatted_created_at', fn ($p) => $p->created_at->format('d.m.Y H:i'))
                ->addColumn('actions', fn ($p) => view('admin.credit-packages.partials.actions', ['package' => $p])->render())
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.credit-packages.index');
    }

    public function create()
    {
        return view('admin.credit-packages.create');
    }

    public function store(StoreCreditPackageRequest $request): JsonResponse
    {
        try {
            $package = $this->packageService->create($request->validated());

            return $this->created(
                __('admin.credit_packages.created_successfully'),
                ['redirect_url' => route('admin.credit-packages.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.credit_packages.creation_failed'), $e);
        }
    }

    public function edit(CreditPackage $creditPackage)
    {
        return view('admin.credit-packages.edit', compact('creditPackage'));
    }

    public function update(UpdateCreditPackageRequest $request, CreditPackage $creditPackage): JsonResponse
    {
        try {
            $this->packageService->update($creditPackage, $request->validated());

            return $this->success(
                __('admin.credit_packages.updated_successfully'),
                ['redirect_url' => route('admin.credit-packages.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.credit_packages.update_failed'), $e);
        }
    }

    public function destroy(CreditPackage $creditPackage): JsonResponse
    {
        try {
            $this->packageService->delete($creditPackage);

            return $this->success(__('admin.credit_packages.deleted_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.credit_packages.deletion_failed'), $e);
        }
    }
}
