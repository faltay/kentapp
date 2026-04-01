<?php

namespace App\Services\Admin;

use App\Models\ContractorProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContractorService
{
    public function createContractor(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'phone'             => $data['phone'] ?? null,
                'type'              => User::TYPE_CONTRACTOR,
                'is_active'         => $data['is_active'] ?? true,
                'email_verified_at' => now(),
            ]);

            ContractorProfile::create([
                'user_id'            => $user->id,
                'company_name'       => $data['company_name'] ?? null,
                'authorized_name'    => $data['authorized_name'] ?? null,
                'company_phone'      => $data['company_phone'] ?? null,
                'company_email'      => $data['company_email'] ?? null,
                'company_address'    => $data['company_address'] ?? null,
                'working_neighborhoods' => $this->parseNeighborhoods($data['working_neighborhoods'] ?? []),
                'certificate_status'   => $data['certificate_status'] ?? ContractorProfile::CERTIFICATE_NONE,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contractor creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateContractor(User $user, array $data): User
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

            $profileData = [
                'company_name'       => $data['company_name'] ?? null,
                'authorized_name'    => $data['authorized_name'] ?? null,
                'company_phone'      => $data['company_phone'] ?? null,
                'company_email'      => $data['company_email'] ?? null,
                'company_address'    => $data['company_address'] ?? null,
                'working_neighborhoods' => $this->parseNeighborhoods($data['working_neighborhoods'] ?? []),
                'certificate_status'   => $data['certificate_status'] ?? ContractorProfile::CERTIFICATE_NONE,
                'certificate_number' => $data['certificate_number'] ?? null,
                'credit_balance'     => $data['credit_balance'] ?? 0,
            ];

            $user->contractorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            DB::commit();

            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contractor update failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }

    private function parseNeighborhoods(array $items): array
    {
        return array_values(array_filter(array_map(function ($item) {
            if (is_array($item)) return $item;
            $decoded = json_decode($item, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $item;
        }, $items)));
    }

    public function deleteContractor(User $user): void
    {
        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contractor deletion failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }
}
