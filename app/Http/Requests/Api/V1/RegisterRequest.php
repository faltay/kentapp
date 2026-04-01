<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'type'           => ['required', 'string', 'in:land_owner,contractor,agent'],
            'company_name'   => ['nullable', 'string', 'max:255'],
            'authorized_name'=> ['nullable', 'string', 'max:255'],
            'company_phone'  => ['nullable', 'string', 'max:20'],
            'company_email'  => ['nullable', 'email', 'max:255'],
        ];
    }
}
