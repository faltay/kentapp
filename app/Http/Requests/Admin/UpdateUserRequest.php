<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'type' => ['required', 'string', 'in:land_owner,contractor,agent'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['boolean'],
            'is_suspended' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => __('admin.users.validation.email_unique'),
        ];
    }
}
