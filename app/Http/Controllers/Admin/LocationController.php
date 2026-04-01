<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Province;
use Illuminate\Http\JsonResponse;

class LocationController extends BaseController
{
    public function districts(): JsonResponse
    {
        $provinceId = request('province_id');
        $districts = District::where('province_id', $provinceId)->orderBy('name')->get(['id', 'name']);

        return response()->json($districts);
    }

    public function neighborhoods(): JsonResponse
    {
        $districtId = request('district_id');
        $neighborhoods = Neighborhood::where('district_id', $districtId)->orderBy('name')->get(['id', 'name']);

        return response()->json($neighborhoods);
    }

    public function search(): JsonResponse
    {
        $q = trim(request('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $results = [];

        // İller
        Province::where('name', 'like', "%{$q}%")
            ->limit(5)
            ->pluck('name')
            ->each(fn($name) => $results[] = ['value' => $name, 'text' => $name]);

        // İlçeler
        District::with('province')
            ->where('name', 'like', "%{$q}%")
            ->limit(8)
            ->get()
            ->each(function ($d) use (&$results) {
                $val = $d->name . ' / ' . $d->province->name;
                $results[] = ['value' => $val, 'text' => $val];
            });

        // Mahalleler
        Neighborhood::with('district.province')
            ->where('name', 'like', "%{$q}%")
            ->limit(8)
            ->get()
            ->each(function ($n) use (&$results) {
                $val = $n->name . ' / ' . $n->district->name . ' / ' . $n->district->province->name;
                $results[] = ['value' => $val, 'text' => $val];
            });

        return response()->json($results);
    }
}
