<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContractorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            // User
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->route('contractor'))],
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'is_active'    => ['boolean'],
            'is_suspended' => ['boolean'],

            // ContractorProfile
            'company_name'       => ['nullable', 'string', 'max:255'],
            'authorized_name'    => ['nullable', 'string', 'max:255'],
            'company_phone'      => ['nullable', 'string', 'max:20'],
            'company_email'      => ['nullable', 'email', 'max:255'],
            'company_address'    => ['nullable', 'string', 'max:500'],
            'working_neighborhoods'   => ['nullable', 'array'],
            'working_neighborhoods.*' => ['string', 'max:200'],
            'certificate_status'  => ['nullable', 'string', 'in:none,pending,approved,rejected'],
            'certificate_number'  => ['nullable', 'string', 'max:100'],
            'certificate_file'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'remove_certificate'  => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('admin.users.validation.email_unique'),
        ];
    }
}
