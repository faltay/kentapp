<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\UpdateMeRequest;
use App\Http\Requests\Api\V1\UpdatePasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends ApiController
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());

            return $this->created([
                'token' => $result['token'],
                'user'  => $this->formatUser($result['user']),
            ], 'Kayıt başarılı.');
        } catch (\Exception $e) {
            Log::error('API register failed', ['error' => $e->getMessage()]);
            return $this->error('Kayıt sırasında bir hata oluştu.');
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error('E-posta veya şifre hatalı.', 401);
        }

        $user = Auth::user();

        if (! $user->is_active || $user->is_suspended) {
            Auth::logout();
            return $this->error('Hesabınız askıya alınmış.', 403);
        }

        $user->tokens()->where('name', 'mobile')->delete();
        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => $this->formatUser($user->load(['contractorProfile', 'agentProfile', 'landOwnerProfile'])),
        ], 'Giriş başarılı.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(message: 'Çıkış yapıldı.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['contractorProfile', 'agentProfile', 'landOwnerProfile']);

        return $this->success(['user' => $this->formatUser($user)]);
    }

    public function updateMe(UpdateMeRequest $request): JsonResponse
    {
        $request->user()->update($request->validated());

        return $this->success(
            ['user' => $this->formatUser($request->user()->fresh())],
            'Profil güncellendi.'
        );
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $request->user()->update(['password' => Hash::make($request->password)]);

        return $this->success(message: 'Şifre güncellendi.');
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate(['avatar' => ['required', 'image', 'max:2048']]);

        $user = $request->user();
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return $this->success(['avatar_url' => asset('storage/' . $path)], 'Fotoğraf güncellendi.');
    }

    private function formatUser($user): array
    {
        $profile = null;

        if ($user->isContractor() && $user->contractorProfile) {
            $p = $user->contractorProfile;
            $profile = [
                'company_name'        => $p->company_name,
                'authorized_name'     => $p->authorized_name,
                'company_phone'       => $p->company_phone,
                'company_email'       => $p->company_email,
                'credit_balance'      => $p->credit_balance,
                'certificate_status'  => $p->certificate_status,
                'working_neighborhoods' => $p->working_neighborhoods ?? [],
            ];
        } elseif ($user->isAgent() && $user->agentProfile) {
            $p = $user->agentProfile;
            $profile = [
                'company_name'        => $p->company_name,
                'authorized_name'     => $p->authorized_name,
                'company_phone'       => $p->company_phone,
                'company_email'       => $p->company_email,
                'credit_balance'      => $p->credit_balance,
                'certificate_status'  => $p->certificate_status,
                'working_neighborhoods' => $p->working_neighborhoods ?? [],
            ];
        }

        return [
            'id'      => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'phone'   => $user->phone,
            'type'    => $user->type,
            'profile' => $profile,
        ];
    }
}
