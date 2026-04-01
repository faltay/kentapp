<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends ApiController
{
    public function __construct(private PaymentService $paymentService) {}

    public function iyzico(Request $request): Response
    {
        $token = $request->input('token');

        if (! $token) {
            Log::warning('Iyzico callback: token eksik');
            return response('BAD_REQUEST', 400);
        }

        try {
            $payment = $this->paymentService->handleIyzicoCallback($token);

            Log::info('Iyzico callback processed', [
                'payment_id' => $payment->id,
                'status'     => $payment->status,
            ]);

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Iyzico callback failed', ['error' => $e->getMessage(), 'token' => $token]);
            return response('ERROR', 500);
        }
    }
}
