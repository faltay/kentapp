<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PurchaseRequest;
use App\Models\CreditPackage;
use App\Services\CreditService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CreditController extends ApiController
{
    public function __construct(
        private CreditService  $creditService,
        private PaymentService $paymentService,
    ) {}

    public function balance(Request $request): JsonResponse
    {
        return $this->success([
            'balance' => $this->creditService->getBalance($request->user()),
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $txs = $request->user()->creditTransactions()
            ->with('listing:id,province,district')
            ->latest()
            ->paginate(20);

        return $this->success([
            'transactions' => $txs->map(fn($tx) => [
                'id'            => $tx->id,
                'type'          => $tx->type,
                'amount'        => $tx->amount,
                'balance_after' => $tx->balance_after,
                'description'   => $tx->description,
                'listing'       => $tx->listing ? [
                    'id'       => $tx->listing->id,
                    'province' => $tx->listing->province,
                    'district' => $tx->listing->district,
                ] : null,
                'created_at'    => $tx->created_at->toISOString(),
            ]),
            'meta' => [
                'current_page' => $txs->currentPage(),
                'last_page'    => $txs->lastPage(),
                'total'        => $txs->total(),
            ],
        ]);
    }

    public function testPurchase(PurchaseRequest $request): JsonResponse
    {
        $user    = $request->user();
        $package = CreditPackage::where('id', $request->credit_package_id)
            ->where('is_active', true)
            ->firstOrFail();

        $this->creditService->addCredits(
            $user,
            $package->credits,
            $package->credits . ' kontör satın alındı (test)',
            'purchase'
        );

        return $this->success([
            'balance' => $this->creditService->getBalance($user),
        ], $package->credits . ' kontör hesabınıza yüklendi.');
    }

    public function purchase(PurchaseRequest $request): JsonResponse
    {
        $user    = $request->user();
        $package = CreditPackage::where('id', $request->credit_package_id)
            ->where('is_active', true)
            ->firstOrFail();

        try {
            $result = $this->paymentService->initiateIyzico($user, $package);

            return $this->created($result, 'Ödeme başlatıldı.');
        } catch (\Exception $e) {
            Log::error('API purchase failed', ['error' => $e->getMessage()]);
            return $this->error('Ödeme başlatılamadı.');
        }
    }
}
