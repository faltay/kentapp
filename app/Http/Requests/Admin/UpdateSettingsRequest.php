<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            // General
            'site_name'        => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',

            // Contact
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',

            // Social
            'facebook'  => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter'   => 'nullable|url|max:255',
            'youtube'   => 'nullable|url|max:255',
            'tiktok'    => 'nullable|url|max:255',

            // Media
            'logo'    => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
        ];
    }
}
