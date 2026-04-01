<?php

namespace App\Services;

use App\Models\AgentProfile;
use App\Models\ContractorProfile;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditService
{
    public function getProfile(User $user): ContractorProfile|AgentProfile|\App\Models\LandOwnerProfile|null
    {
        return match ($user->type) {
            User::TYPE_CONTRACTOR => $user->contractorProfile,
            User::TYPE_AGENT      => $user->agentProfile,
            User::TYPE_LAND_OWNER => $user->landOwnerProfile,
            default               => null,
        };
    }

    public function getBalance(User $user): int
    {
        return $this->getProfile($user)?->credit_balance ?? 0;
    }

    public function spend(User $user, int $amount, ?int $listingId, string $description): CreditTransaction
    {
        return DB::transaction(function () use ($user, $amount, $listingId, $description) {
            $profile = $this->getProfileLocked($user);

            if (! $profile || $profile->credit_balance < $amount) {
                throw new \RuntimeException('Yetersiz kontör bakiyesi.');
            }

            $balanceAfter = $profile->credit_balance - $amount;

            $tx = CreditTransaction::create([
                'user_id'       => $user->id,
                'listing_id'    => $listingId,
                'type'          => CreditTransaction::TYPE_SPEND,
                'amount'        => -$amount,
                'balance_after' => $balanceAfter,
                'description'   => $description,
            ]);

            $profile->update(['credit_balance' => $balanceAfter]);

            return $tx;
        });
    }

    public function addCredits(User $user, int $amount, string $description, string $type = CreditTransaction::TYPE_PURCHASE): CreditTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description, $type) {
            $profile = $this->getProfileLocked($user);

            $currentBalance = $profile?->credit_balance ?? 0;
            $balanceAfter   = $currentBalance + $amount;

            $tx = CreditTransaction::create([
                'user_id'       => $user->id,
                'type'          => $type,
                'amount'        => $amount,
                'balance_after' => $balanceAfter,
                'description'   => $description,
            ]);

            if ($profile) {
                $profile->update(['credit_balance' => $balanceAfter]);
            }

            return $tx;
        });
    }

    private function getProfileLocked(User $user): ContractorProfile|AgentProfile|\App\Models\LandOwnerProfile|null
    {
        return match ($user->type) {
            User::TYPE_CONTRACTOR => ContractorProfile::where('user_id', $user->id)->lockForUpdate()->first(),
            User::TYPE_AGENT      => AgentProfile::where('user_id', $user->id)->lockForUpdate()->first(),
            User::TYPE_LAND_OWNER => \App\Models\LandOwnerProfile::where('user_id', $user->id)->lockForUpdate()->first(),
            default               => null,
        };
    }
}
