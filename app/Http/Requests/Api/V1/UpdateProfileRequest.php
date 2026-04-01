<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'company_name'                        => ['sometimes', 'nullable', 'string', 'max:255'],
            'authorized_name'                     => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_phone'                       => ['sometimes', 'nullable', 'string', 'max:20'],
            'company_email'                       => ['sometimes', 'nullable', 'email', 'max:255'],
            'company_address'                     => ['sometimes', 'nullable', 'string', 'max:500'],
            'initial_neighborhoods'               => ['sometimes', 'nullable', 'array', 'max:5'],
            'initial_neighborhoods.*.province'    => ['required_with:initial_neighborhoods', 'string', 'max:100'],
            'initial_neighborhoods.*.district'    => ['required_with:initial_neighborhoods', 'string', 'max:100'],
            'initial_neighborhoods.*.neighborhood'=> ['required_with:initial_neighborhoods', 'string', 'max:100'],
        ];
    }
}
