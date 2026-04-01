<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\User::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'type' => ['required', 'string', 'in:land_owner,contractor,agent'],
            'role' => ['required', 'string', 'exists:roles,name', Rule::notIn(['super_admin'])],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('admin.users.validation.email_unique'),
        ];
    }
}
