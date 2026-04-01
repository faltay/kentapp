<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreditTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'user_id'     => ['required', 'integer', 'exists:users,id'],
            'type'        => ['required', 'string', 'in:purchase,refund'],
            'amount'      => ['required', 'integer', 'min:1', 'max:99999'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
