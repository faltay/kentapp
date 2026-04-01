<?php

namespace App\Services;

use App\Models\AgentProfile;
use App\Models\ContractorProfile;
use App\Models\LandOwnerProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function register(array $data): array
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'] ?? null,
                'password'  => Hash::make($data['password']),
                'type'      => $data['type'],
                'is_active' => true,
            ]);

            match ($data['type']) {
                User::TYPE_CONTRACTOR => ContractorProfile::create([
                    'user_id'            => $user->id,
                    'company_name'       => $data['company_name'] ?? null,
                    'authorized_name'    => $data['authorized_name'] ?? null,
                    'company_phone'      => $data['company_phone'] ?? null,
                    'company_email'      => $data['company_email'] ?? null,
                    'certificate_status' => ContractorProfile::CERTIFICATE_NONE,
                    'credit_balance'     => 0,
                ]),
                User::TYPE_AGENT => AgentProfile::create([
                    'user_id'            => $user->id,
                    'company_name'       => $data['company_name'] ?? null,
                    'authorized_name'    => $data['authorized_name'] ?? null,
                    'company_phone'      => $data['company_phone'] ?? null,
                    'company_email'      => $data['company_email'] ?? null,
                    'certificate_status' => AgentProfile::CERTIFICATE_NONE,
                    'credit_balance'     => 0,
                ]),
                User::TYPE_LAND_OWNER => LandOwnerProfile::create([
                    'user_id' => $user->id,
                ]),
                default => null,
            };

            $token = $user->createToken('mobile')->plainTextToken;

            DB::commit();

            return ['user' => $user->fresh(['contractorProfile', 'agentProfile', 'landOwnerProfile']), 'token' => $token];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AuthService::register failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
