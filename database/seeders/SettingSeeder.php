<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'QR Menu', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Digital menu solution for restaurants', 'group' => 'general'],
            ['key' => 'meta_title', 'value' => 'QR Menu — Digital Restaurant Menu', 'group' => 'general'],
            ['key' => 'meta_description', 'value' => 'Create QR code menus for your restaurant. Easy setup, beautiful design.', 'group' => 'general'],

            // Contact
            ['key' => 'contact_email', 'value' => null, 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => null, 'group' => 'contact'],
            ['key' => 'address', 'value' => null, 'group' => 'contact'],

            // Social
            ['key' => 'facebook', 'value' => null, 'group' => 'social'],
            ['key' => 'instagram', 'value' => null, 'group' => 'social'],
            ['key' => 'twitter', 'value' => null, 'group' => 'social'],
            ['key' => 'youtube', 'value' => null, 'group' => 'social'],
            ['key' => 'tiktok', 'value' => null, 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
