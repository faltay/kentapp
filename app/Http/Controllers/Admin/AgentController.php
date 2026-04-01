<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreAgentRequest;
use App\Http\Requests\Admin\UpdateAgentRequest;
use App\Models\User;
use App\Services\Admin\AgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AgentController extends BaseController
{
    public function __construct(private AgentService $agentService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $agents = User::agents()
                ->with('agentProfile')
                ->select('users.*');

            return DataTables::of($agents)
                ->addColumn('company_name', fn($u) => $u->agentProfile?->company_name ?? '—')
                ->addColumn('listings_count', fn($u) => $u->listings()->count())
                ->addColumn('credit_balance', fn($u) => $u->agentProfile?->credit_balance ?? 0)
                ->addColumn('status', function ($u) {
                    if ($u->is_suspended) {
                        return '<span class="badge bg-red-lt">' . __('admin.users.suspended') . '</span>';
                    }
                    return $u->is_active
                        ? '<span class="badge bg-green-lt">' . __('common.active') . '</span>'
                        : '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>';
                })
                ->addColumn('actions', fn($u) => view('admin.agents.partials.actions', ['agent' => $u])->render())
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.agents.index');
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(StoreAgentRequest $request): JsonResponse
    {
        try {
            $agent = $this->agentService->createAgent($request->validated());

            if ($request->hasFile('certificate_file')) {
                $agent->agentProfile
                    ->addMediaFromRequest('certificate_file')
                    ->toMediaCollection('authority_certificate');
            }

            return $this->created(
                __('admin.agents.created_successfully'),
                ['redirect_url' => route('admin.agents.show', $agent)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.agents.creation_failed'), $e);
        }
    }

    public function show(User $agent)
    {
        abort_unless($agent->isAgent(), 404);

        $agent->load(['agentProfile', 'creditTransactions' => fn($q) => $q->latest()->limit(10)]);

        $listings = $agent->listings()->latest()->limit(10)->get();

        $recentViews = $agent->listingViews()
            ->with('listing')
            ->latest('viewed_at')
            ->limit(5)
            ->get();

        return view('admin.agents.show', compact('agent', 'listings', 'recentViews'));
    }

    public function edit(User $agent)
    {
        abort_unless($agent->isAgent(), 404);

        $agent->load('agentProfile');

        return view('admin.agents.edit', compact('agent'));
    }

    public function update(UpdateAgentRequest $request, User $agent): JsonResponse
    {
        abort_unless($agent->isAgent(), 404);

        try {
            $this->agentService->updateAgent($agent, $request->validated());

            $profile = $agent->agentProfile;

            if ($request->hasFile('certificate_file')) {
                $profile->addMediaFromRequest('certificate_file')
                    ->toMediaCollection('authority_certificate');
            } elseif ($request->input('remove_certificate') == '1') {
                $profile->clearMediaCollection('authority_certificate');
            }

            return $this->success(
                __('admin.agents.updated_successfully'),
                ['redirect_url' => route('admin.agents.show', $agent)]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.agents.update_failed'), $e);
        }
    }

    public function destroy(User $agent): JsonResponse
    {
        abort_unless($agent->isAgent(), 404);

        try {
            $this->agentService->deleteAgent($agent);

            return $this->success(__('admin.agents.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Agent deletion failed', ['error' => $e->getMessage(), 'user_id' => $agent->id]);

            return $this->error(__('admin.agents.deletion_failed'), $e);
        }
    }
}
