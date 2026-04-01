<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('page'));
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
            'content' => 'nullable|array',
            'content.' . $lang => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'is_homepage' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
