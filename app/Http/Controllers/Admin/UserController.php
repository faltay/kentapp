<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends BaseController
{
    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        if (request()->ajax()) {
            $users = User::with('roles')->select('users.*');

            return DataTables::of($users)
                ->addColumn('role', fn ($user) => $user->roles->map(fn ($r) => __('admin.users.roles.' . $r->name))->join(', '))
                ->addColumn('status', function ($user) {
                    if ($user->is_suspended) {
                        return '<span class="badge bg-red-lt">' . __('admin.users.suspended') . '</span>';
                    }

                    return $user->is_active
                        ? '<span class="badge bg-green-lt">' . __('common.active') . '</span>'
                        : '<span class="badge bg-secondary-lt">' . __('common.inactive') . '</span>';
                })
                ->addColumn('actions', fn ($user) => view('admin.users.partials.actions', compact('user'))->render())
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['super_admin'])->pluck('name', 'name');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return $this->created(
                __('admin.users.created_successfully'),
                ['redirect_url' => route('admin.users.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.users.creation_failed'), $e);
        }
    }

    public function show(User $user)
    {
        $user->load(['roles', 'contractorProfile', 'landOwnerProfile']);

        $subscription = $user->subscriptions()
            ->active()
            ->with('plan')
            ->latest()
            ->first();

        $payments = $user->payments()
            ->with('subscription.plan')
            ->orderByDesc('paid_at')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact('user', 'subscription', 'payments'));
    }

    public function edit(User $user)
    {
        $roles = Role::whereNotIn('name', ['super_admin'])->pluck('name', 'name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $this->userService->updateUser($user, $request->validated());

            return $this->success(
                __('admin.users.updated_successfully'),
                ['redirect_url' => route('admin.users.index')]
            );
        } catch (\Exception $e) {
            return $this->error(__('admin.users.update_failed'), $e);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        try {
            $this->userService->deleteUser($user);

            return $this->success(__('admin.users.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('User deletion failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            return $this->error(__('admin.users.deletion_failed'), $e);
        }
    }
}
