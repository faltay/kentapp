<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use App\Services\Admin\SettingService;
use Illuminate\Http\JsonResponse;

class SettingsController extends BaseController
{
    public function __construct(private SettingService $service)
    {
    }

    public function edit()
    {
        $settings = Setting::getAll();
        $mediaSetting = $this->service->getMediaSetting();

        return view('admin.settings.edit', compact('settings', 'mediaSetting'));
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        try {
            $this->service->updateSettings($request->validated());

            if ($request->hasFile('logo') || $request->hasFile('favicon')) {
                $files = [];
                if ($request->hasFile('logo')) {
                    $files['logo'] = $request->file('logo');
                }
                if ($request->hasFile('favicon')) {
                    $files['favicon'] = $request->file('favicon');
                }
                $this->service->updateMedia($files);
            }

            return $this->success(__('admin.settings.updated_successfully'));
        } catch (\Exception $e) {
            return $this->error(__('admin.settings.update_failed'), $e);
        }
    }
}
