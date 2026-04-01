<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Language::class);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:2', 'alpha', 'unique:languages,code'],
            'name' => ['required', 'string', 'max:100'],
            'native' => ['required', 'string', 'max:100'],
            'flag' => ['nullable', 'string', 'max:10'],
            'direction' => ['required', 'string', 'in:ltr,rtl'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_default' => $this->boolean('is_default'),
            'code' => strtolower($this->code ?? ''),
        ]);
    }
}
