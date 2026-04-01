<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreNeighborhoodRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'province'     => ['required', 'string', 'max:100'],
            'district'     => ['required', 'string', 'max:100'],
            'neighborhood' => ['required', 'string', 'max:100'],
        ];
    }
}
