<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Post::class);
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
            'content' => 'required|array',
            'content.' . $lang => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'blog_category_id' => 'nullable|integer|exists:blog_categories,id',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
