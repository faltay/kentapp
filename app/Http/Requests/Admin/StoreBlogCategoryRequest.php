<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\BlogCategory::class);
    }

    public function rules(): array
    {
        $lang = $this->input('lang', default_language_code());

        return [
            'lang' => 'required|string|in:' . implode(',', active_language_codes()),
            'name' => 'required|array',
            'name.' . $lang => 'required|string|max:255',
            'slug' => 'nullable|array',
            'slug.' . $lang => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.' . $lang => 'nullable|string|max:500',
            'meta_description' => 'nullable|array',
            'meta_description.' . $lang => 'nullable|string|max:160',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
