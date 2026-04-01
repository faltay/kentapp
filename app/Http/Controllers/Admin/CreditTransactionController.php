<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreCreditTransactionRequest;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CreditTransactionController extends BaseController
{
    public function index()
    {
        if (request()->ajax()) {
            $query = CreditTransaction::query()
                ->join('users', 'users.id', '=', 'credit_transactions.user_id')
                ->leftJoin('listings', 'listings.id', '=', 'credit_transactions.listing_id')
                ->select(
                    'credit_transactions.*',
                    'users.name as user_name',
                    'users.email as user_email',
                    'listings.province as listing_province',
                    'listings.district as listing_district',
                );

            if ($type = request('type')) {
                $query->where('type', $type);
            }

            return DataTables::of($query)
                ->addColumn('user_name', fn ($t) => $t->user_name ?? '—')
                ->addColumn('user_email', fn ($t) => $t->user_email ?? '—')
                ->addColumn('listing_info', fn ($t) => $t->listing_province
                    ? $t->listing_province . ' / ' . $t->listing_district
                    : '—')
                ->addColumn('type_badge', fn ($t) => view('admin.credit-transactions.partials.type-badge', ['transaction' => $t])->render())
                ->addColumn('formatted_amount', fn ($t) => ($t->amount > 0 ? '+' : '') . $t->amount)
                ->addColumn('formatted_created_at', fn ($t) => $t->created_at->format('d.m.Y H:i'))
                ->rawColumns(['type_badge'])
                ->make(true);
        }

        return view('admin.credit-transactions.index');
    }

    public function create()
    {
        return view('admin.credit-transactions.create');
    }

    public function store(StoreCreditTransactionRequest $request): JsonResponse
    {
        $user = User::findOrFail($request->user_id);

        abort_unless($user->isContractor() || $user->isAgent(), 422);

        DB::beginTransaction();

        try {
            $profileRelation = $user->isContractor() ? 'contractorProfile' : 'agentProfile';
            $profileModel    = $user->isContractor()
                ? \App\Models\ContractorProfile::class
                : \App\Models\AgentProfile::class;

            $profile = $user->$profileRelation()->lockForUpdate()->first()
                ?? $profileModel::create(['user_id' => $user->id, 'credit_balance' => 0]);

            $currentBalance = $profile->credit_balance;
            $amount         = (int) $request->amount;
            $balanceAfter   = $currentBalance + $amount;

            CreditTransaction::create([
                'user_id'       => $user->id,
                'type'          => $request->type,
                'amount'        => $amount,
                'balance_after' => $balanceAfter,
                'description'   => $request->description,
            ]);

            $profile->update(['credit_balance' => $balanceAfter]);

            DB::commit();

            return $this->created(
                __('admin.credit_transactions.assigned_successfully'),
                ['redirect_url' => route('admin.credit-transactions.index')]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit assignment failed', ['error' => $e->getMessage(), 'user_id' => $request->user_id]);

            return $this->error(__('admin.credit_transactions.assignment_failed'), $e);
        }
    }

    public function searchUsers(): JsonResponse
    {
        $q = request('q', '');

        $users = User::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->whereIn('type', [User::TYPE_CONTRACTOR, User::TYPE_AGENT])
            ->with(['contractorProfile', 'agentProfile'])
            ->limit(20)
            ->get()
            ->map(fn($u) => [
                'id'      => $u->id,
                'name'    => $u->name,
                'email'   => $u->email,
                'type'    => $u->type,
                'balance' => $u->isContractor()
                    ? ($u->contractorProfile?->credit_balance ?? 0)
                    : ($u->agentProfile?->credit_balance ?? 0),
            ]);

        return response()->json($users);
    }
}
