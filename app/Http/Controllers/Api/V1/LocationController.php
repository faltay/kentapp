<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends ApiController
{
    public function provinces(): JsonResponse
    {
        $provinces = Province::orderBy('name')->get(['id', 'name', 'code']);

        return $this->success(['provinces' => $provinces]);
    }

    public function districts(Request $request): JsonResponse
    {
        $request->validate(['province_id' => ['required', 'exists:provinces,id']]);

        $districts = District::where('province_id', $request->province_id)
            ->orderBy('name')
            ->get(['id', 'province_id', 'name']);

        return $this->success(['districts' => $districts]);
    }

    public function neighborhoods(Request $request): JsonResponse
    {
        $request->validate(['district_id' => ['required', 'exists:districts,id']]);

        $neighborhoods = Neighborhood::where('district_id', $request->district_id)
            ->orderBy('name')
            ->get(['id', 'district_id', 'name']);

        return $this->success(['neighborhoods' => $neighborhoods]);
    }
}
