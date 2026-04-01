<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CreditPackage;
use Illuminate\Http\JsonResponse;

class CreditPackageController extends ApiController
{
    public function index(): JsonResponse
    {
        $packages = CreditPackage::active()
            ->orderBy('sort_order')
            ->orderBy('credits')
            ->get();

        return $this->success([
            'packages' => $packages->map(fn($p) => [
                'id'         => $p->id,
                'name'       => $p->name,
                'credits'    => $p->credits,
                'price'      => (float) $p->price,
                'currency'   => $p->currency,
                'price_per_credit' => round((float) $p->price / $p->credits, 2),
            ]),
        ]);
    }
}
