<?php

namespace App\Services\Admin;

use App\Models\LandOwnerProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LandOwnerService
{
    public function createLandOwner(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'phone'             => $data['phone'] ?? null,
                'type'              => User::TYPE_LAND_OWNER,
                'is_active'         => $data['is_active'] ?? true,
                'email_verified_at' => now(),
            ]);

            LandOwnerProfile::create([
                'user_id'   => $user->id,
                'tc_number' => $data['tc_number'] ?? null,
            ]);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Land owner creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateLandOwner(User $user, array $data): User
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

            $user->landOwnerProfile()->updateOrCreate(
                ['user_id' => $user->id],
                ['tc_number' => $data['tc_number'] ?? null]
            );

            DB::commit();

            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Land owner update failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }

    public function deleteLandOwner(User $user): void
    {
        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Land owner deletion failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }
}
