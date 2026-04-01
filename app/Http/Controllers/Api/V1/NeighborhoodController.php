<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreNeighborhoodRequest;
use App\Models\User;
use App\Services\CreditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NeighborhoodController extends ApiController
{
    private const NEIGHBORHOOD_CREDIT_COST = 10;

    public function __construct(private CreditService $creditService) {}

    public function index(Request $request): JsonResponse
    {
        $profile = $this->getProfile($request->user());

        if (! $profile) return $this->forbidden();

        return $this->success(['neighborhoods' => $profile->working_neighborhoods ?? []]);
    }

    public function store(StoreNeighborhoodRequest $request): JsonResponse
    {
        $user    = $request->user();
        $profile = $this->getProfile($user);

        if (! $profile) return $this->forbidden();

        $balance = $this->creditService->getBalance($user);

        if ($balance < self::NEIGHBORHOOD_CREDIT_COST) {
            return $this->error(
                'Yeni mahalle eklemek için ' . self::NEIGHBORHOOD_CREDIT_COST . ' kontör gerekiyor. Mevcut bakiye: ' . $balance,
                422
            );
        }

        $neighborhoods = $profile->working_neighborhoods ?? [];
        $newEntry      = $request->only(['province', 'district', 'neighborhood']);

        // Aynı mahalle zaten eklenmiş mi?
        $exists = collect($neighborhoods)->contains(fn($n) =>
            $n['province']     === $newEntry['province'] &&
            $n['district']     === $newEntry['district'] &&
            $n['neighborhood'] === $newEntry['neighborhood']
        );

        if ($exists) {
            return $this->error('Bu mahalle zaten ekli.', 422);
        }

        try {
            $this->creditService->spend(
                $user,
                self::NEIGHBORHOOD_CREDIT_COST,
                null,
                'Mahalle ekleme: ' . $newEntry['neighborhood']
            );

            $neighborhoods[] = $newEntry;
            $profile->update(['working_neighborhoods' => $neighborhoods]);

            return $this->created([
                'neighborhoods'  => $neighborhoods,
                'credits_spent'  => self::NEIGHBORHOOD_CREDIT_COST,
            ], 'Mahalle eklendi.');
        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Exception $e) {
            Log::error('Neighborhood store failed', ['error' => $e->getMessage()]);
            return $this->error('Mahalle eklenemedi.');
        }
    }

    public function destroy(Request $request, int $index): JsonResponse
    {
        $profile = $this->getProfile($request->user());

        if (! $profile) return $this->forbidden();

        $neighborhoods = $profile->working_neighborhoods ?? [];

        if (! isset($neighborhoods[$index])) {
            return $this->notFound('Mahalle bulunamadı.');
        }

        array_splice($neighborhoods, $index, 1);
        $profile->update(['working_neighborhoods' => array_values($neighborhoods)]);

        return $this->success(['neighborhoods' => $neighborhoods], 'Mahalle kaldırıldı.');
    }

    private function getProfile(User $user)
    {
        if (! in_array($user->type, [User::TYPE_CONTRACTOR, User::TYPE_AGENT])) {
            return null;
        }

        return $user->isContractor() ? $user->contractorProfile : $user->agentProfile;
    }
}
