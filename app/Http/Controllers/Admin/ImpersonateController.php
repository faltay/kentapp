<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpersonateController extends BaseController
{
    public function start(User $user): RedirectResponse
    {
        $admin = Auth::user();

        // Kendini taklit edemez
        if ($admin->id === $user->id) {
            return back()->with('error', __('admin.impersonate.cannot_self'));
        }

        // Super admin taklit edilemez
        if ($user->hasRole('super_admin')) {
            return back()->with('error', __('admin.impersonate.cannot_admin'));
        }

        // Orijinal admin ID'sini session'a kaydet
        session()->put('impersonating_from', $admin->id);
        session()->put('impersonating_name', $admin->name);

        Log::info('Impersonation started', [
            'admin_id' => $admin->id,
            'target_id' => $user->id,
            'target_email' => $user->email,
        ]);

        Auth::login($user);

        return redirect()->route('restaurant.dashboard');
    }

    public function stop(): RedirectResponse
    {
        $adminId = session()->pull('impersonating_from');
        session()->forget('impersonating_name');

        if (! $adminId) {
            return redirect()->route('admin.dashboard');
        }

        $admin = User::find($adminId);

        if (! $admin) {
            Auth::logout();

            return redirect()->route('login');
        }

        Log::info('Impersonation stopped', [
            'admin_id' => $adminId,
            'was_impersonating' => Auth::id(),
        ]);

        Auth::login($admin);

        return redirect()->route('admin.users.index');
    }
}
