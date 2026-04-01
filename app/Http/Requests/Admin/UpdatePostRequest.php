<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('post'));
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
            'excerpt' => 'nullable|array',
            'excerpt.' . $lang => 'nullable|string|max:500',
            'content' => 'nullable|array',
            'content.' . $lang => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'blog_category_id' => 'nullable|integer|exists:blog_categories,id',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
