<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Page::class);
    }

    public function rules(): array
    {
        $lang = $this->input('lang', default_language_code());

        return [
            'lang' => 'required|string|in:' . implode(',', active_language_codes()),
            'title' => 'required|array',
            'title.' . $lang => 'required|string|max:255',
            'slug' => 'nullable|array',
            'slug.' . $lang => 'nullable|string|max:255',
            'meta_description' => 'nullable|array',
            'meta_description.' . $lang => 'nullable|string|max:160',
            'content' => 'required|array',
            'content.' . $lang => 'required|string',
            'is_published' => 'nullable|boolean',
            'is_homepage' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
