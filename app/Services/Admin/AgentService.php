<?php

namespace App\Services\Admin;

use App\Models\AgentProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AgentService
{
    public function createAgent(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'phone'             => $data['phone'] ?? null,
                'type'              => User::TYPE_AGENT,
                'is_active'         => $data['is_active'] ?? true,
                'email_verified_at' => now(),
            ]);

            AgentProfile::create([
                'user_id'               => $user->id,
                'company_name'          => $data['company_name'] ?? null,
                'authorized_name'       => $data['authorized_name'] ?? null,
                'company_phone'         => $data['company_phone'] ?? null,
                'company_email'         => $data['company_email'] ?? null,
                'company_address'       => $data['company_address'] ?? null,
                'working_neighborhoods' => array_values(array_filter($data['working_neighborhoods'] ?? [])),
                'certificate_status'    => $data['certificate_status'] ?? AgentProfile::CERTIFICATE_NONE,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agent creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateAgent(User $user, array $data): User
    {
        DB::beginTransaction();

        try {
            $userUpdate = [
                'name'         => $data['name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'] ?? null,
                'is_active'    => $data['is_active'] ?? $user->is_active,
                'is_suspended' => $data['is_suspended'] ?? false,
            ];

            if (!empty($data['password'])) {
                $userUpdate['password'] = Hash::make($data['password']);
            }

            $user->update($userUpdate);

            $user->agentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name'          => $data['company_name'] ?? null,
                    'authorized_name'       => $data['authorized_name'] ?? null,
                    'company_phone'         => $data['company_phone'] ?? null,
                    'company_email'         => $data['company_email'] ?? null,
                    'company_address'       => $data['company_address'] ?? null,
                    'working_neighborhoods' => array_values(array_filter($data['working_neighborhoods'] ?? [])),
                    'certificate_status'    => $data['certificate_status'] ?? AgentProfile::CERTIFICATE_NONE,
                    'certificate_number'    => $data['certificate_number'] ?? null,
                ]
            );

            DB::commit();

            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agent update failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }

    public function deleteAgent(User $user): void
    {
        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Agent deletion failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }
}
