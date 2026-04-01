<?php

namespace App\Services\Admin;

use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserService
{
    public function createUser(array $data): User
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'type' => $data['type'] ?? User::TYPE_LAND_OWNER,
                'is_active' => $data['is_active'] ?? true,
                'email_verified_at' => now(),
            ]);

            $user->syncRoles([$data['role']]);

            DB::commit();

            // Giriş bilgilerini içeren hoş geldin maili gönder
            Mail::to($user->email)->queue(new AccountCreatedMail($user, $data['password']));

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed', ['error' => $e->getMessage(), 'data' => \Illuminate\Support\Arr::except($data, ['password', 'password_confirmation'])]);

            throw $e;
        }
    }

    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();

        try {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'type' => $data['type'] ?? $user->type,
                'is_active' => $data['is_active'] ?? $user->is_active,
                'is_suspended' => $data['is_suspended'] ?? false,
            ];

            if (! empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);
            $user->syncRoles([$data['role']]);

            DB::commit();

            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            throw $e;
        }
    }

    public function deleteUser(User $user): void
    {
        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User deletion failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            throw $e;
        }
    }
}
