<?php

namespace App\Services;

use App\Models\CreditPackage;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private IyzicoService $iyzico,
        private CreditService $creditService,
    ) {}

    public function initiateIyzico(User $user, CreditPackage $package): array
    {
        $conversationId = (string) Str::uuid();
        $callbackUrl    = route('v1.payments.iyzico.callback');

        $payment = Payment::create([
            'user_id'           => $user->id,
            'credit_package_id' => $package->id,
            'provider'          => Payment::PROVIDER_IYZICO,
            'amount'            => $package->price,
            'currency'          => $package->currency,
            'credits'           => $package->credits,
            'status'            => Payment::STATUS_PENDING,
            'description'       => 'conversation_id:' . $conversationId,
        ]);

        try {
            $result = $this->iyzico->createCheckoutForm($user, $package, $conversationId, $callbackUrl);

            if ($result->getStatus() !== 'success') {
                $payment->update(['status' => Payment::STATUS_FAILED]);
                throw new \RuntimeException('İyzico form oluşturulamadı: ' . $result->getErrorMessage());
            }

            return [
                'payment_id'           => $payment->id,
                'token'                => $result->getToken(),
                'payment_page_url'     => $result->getPaymentPageUrl(),
                'checkout_form_content'=> $result->getCheckoutFormContent(),
            ];
        } catch (\Exception $e) {
            $payment->update(['status' => Payment::STATUS_FAILED]);
            throw $e;
        }
    }

    public function handleIyzicoCallback(string $token): Payment
    {
        return DB::transaction(function () use ($token) {
            $form = $this->iyzico->retrieveCheckoutForm($token);

            $conversationId = $form->getConversationId();

            Log::info('Iyzico callback details', [
                'token'           => $token,
                'conversation_id' => $conversationId,
                'status'          => $form->getStatus(),
                'payment_status'  => $form->getPaymentStatus(),
                'error_message'   => $form->getErrorMessage(),
            ]);

            $payment = Payment::where('status', Payment::STATUS_PENDING)
                ->where('description', 'conversation_id:' . $conversationId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($form->getStatus() === 'success' && $form->getPaymentStatus() === 'SUCCESS') {
                $payment->update([
                    'status'              => Payment::STATUS_SUCCEEDED,
                    'provider_payment_id' => $token,
                    'paid_at'             => now(),
                ]);

                $this->creditService->addCredits(
                    $payment->user,
                    $payment->credits,
                    $payment->credits . ' kontör satın alındı',
                    'purchase'
                );
            } else {
                $payment->update(['status' => Payment::STATUS_FAILED]);
            }

            return $payment->fresh();
        });
    }
}
