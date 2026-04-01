<?php

namespace App\Services\Admin;

use App\Models\CreditPackage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditPackageService
{
    public function create(array $data): CreditPackage
    {
        DB::beginTransaction();

        try {
            $package = CreditPackage::create($data);
            DB::commit();

            return $package;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit package creation failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function update(CreditPackage $package, array $data): CreditPackage
    {
        DB::beginTransaction();

        try {
            $package->update($data);
            DB::commit();

            return $package->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit package update failed', [
                'package_id' => $package->id,
                'error'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function delete(CreditPackage $package): void
    {
        DB::beginTransaction();

        try {
            $package->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Credit package deletion failed', [
                'package_id' => $package->id,
                'error'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
