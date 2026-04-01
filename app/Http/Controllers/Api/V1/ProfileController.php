<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends ApiController
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! in_array($user->type, [User::TYPE_CONTRACTOR, User::TYPE_AGENT])) {
            return $this->forbidden('Bu profil türü bu işlemi desteklemiyor.');
        }

        $profile = $user->isContractor()
            ? $user->contractorProfile
            : $user->agentProfile;

        return $this->success(['profile' => $this->formatProfile($user, $profile)]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! in_array($user->type, [User::TYPE_CONTRACTOR, User::TYPE_AGENT])) {
            return $this->forbidden();
        }

        try {
            $profileData = collect($request->validated())
                ->except('initial_neighborhoods')
                ->toArray();

            if ($user->isContractor()) {
                $user->contractorProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            } else {
                $user->agentProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            }

            $profile = $user->isContractor()
                ? $user->fresh()->contractorProfile
                : $user->fresh()->agentProfile;

            // İlk kurulum mahalleleri — sadece mevcut liste boşsa ücretsiz ekle
            $initialNeighborhoods = $request->input('initial_neighborhoods', []);
            if (! empty($initialNeighborhoods) && empty($profile->working_neighborhoods)) {
                $unique = collect($initialNeighborhoods)
                    ->unique(fn($n) => $n['province'] . '__' . $n['district'] . '__' . $n['neighborhood'])
                    ->values()
                    ->map(fn($n) => [
                        'province'     => $n['province'],
                        'district'     => $n['district'],
                        'neighborhood' => $n['neighborhood'],
                    ])
                    ->toArray();

                $profile->update(['working_neighborhoods' => $unique]);
                $profile->refresh();
            }

            return $this->success(['profile' => $this->formatProfile($user, $profile)], 'Profil güncellendi.');
        } catch (\Exception $e) {
            Log::error('API profile update failed', ['error' => $e->getMessage()]);
            return $this->error('Profil güncellenemedi.');
        }
    }

    public function uploadCertificate(Request $request): JsonResponse
    {
        $request->validate(['certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240']]);

        $user = $request->user();

        if (! in_array($user->type, [User::TYPE_CONTRACTOR, User::TYPE_AGENT])) {
            return $this->forbidden();
        }

        try {
            $profile = $user->isContractor() ? $user->contractorProfile : $user->agentProfile;

            if (! $profile) {
                return $this->error('Profil bulunamadı.', 422);
            }

            $profile->clearMediaCollection('authority_certificate');
            $profile->addMedia($request->file('certificate'))
                ->toMediaCollection('authority_certificate');

            $profile->update(['certificate_status' => $profile::CERTIFICATE_PENDING]);

            return $this->success(message: 'Yetki belgesi yüklendi, onay bekleniyor.');
        } catch (\Exception $e) {
            Log::error('API certificate upload failed', ['error' => $e->getMessage()]);
            return $this->error('Belge yüklenemedi.');
        }
    }

    private function formatProfile(User $user, $profile): array
    {
        if (! $profile) return [];

        $certificateMedia = $profile->getFirstMedia('authority_certificate');

        return [
            'company_name'          => $profile->company_name,
            'authorized_name'       => $profile->authorized_name,
            'company_phone'         => $profile->company_phone,
            'company_email'         => $profile->company_email,
            'company_address'       => $profile->company_address,
            'credit_balance'        => $profile->credit_balance,
            'certificate_status'    => $profile->certificate_status,
            'certificate_number'    => $profile->certificate_number,
            'working_neighborhoods' => $profile->working_neighborhoods ?? [],
            'certificate_file'      => $certificateMedia ? [
                'name' => $certificateMedia->file_name,
                'url'  => $certificateMedia->getUrl(),
            ] : null,
        ];
    }
}
