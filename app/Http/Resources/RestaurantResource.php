<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->localized_name,
            'slug' => $this->slug,
            'description' => $this->localized_description ?: null,
            'currency' => $this->currency,
            'logo' => $this->getFirstMediaUrl('logo') ?: null,
        ];
    }
}
