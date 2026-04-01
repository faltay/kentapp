<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLandOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'tc_number' => ['nullable', 'string', 'size:11', 'regex:/^[0-9]{11}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'      => __('admin.users.validation.email_unique'),
            'tc_number.size'    => __('admin.land_owners.validation.tc_number_size'),
            'tc_number.regex'   => __('admin.land_owners.validation.tc_number_regex'),
        ];
    }
}
