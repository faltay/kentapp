<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingService
{
    public function updateSettings(array $data): void
    {
        DB::beginTransaction();

        try {
            // General
            $generalKeys = ['site_name', 'site_description', 'meta_title', 'meta_description'];
            foreach ($generalKeys as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::set($key, $data[$key], 'general');
                }
            }

            // Contact
            $contactKeys = ['contact_email', 'contact_phone', 'address'];
            foreach ($contactKeys as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::set($key, $data[$key], 'contact');
                }
            }

            // Social
            $socialKeys = ['facebook', 'instagram', 'twitter', 'youtube', 'tiktok'];
            foreach ($socialKeys as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::set($key, $data[$key], 'social');
                }
            }

            DB::commit();

            Setting::clearCache();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Settings update failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function updateMedia(array $files): void
    {
        try {
            $mediaSetting = Setting::firstOrCreate(
                ['key' => 'site_media'],
                ['value' => null, 'group' => 'general']
            );

            if (isset($files['logo'])) {
                $mediaSetting->addMedia($files['logo'])
                    ->toMediaCollection('logo');
            }

            if (isset($files['favicon'])) {
                $mediaSetting->addMedia($files['favicon'])
                    ->toMediaCollection('favicon');
            }
        } catch (\Exception $e) {
            Log::error('Settings media update failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function getMediaSetting(): ?Setting
    {
        return Setting::where('key', 'site_media')->first();
    }
}
