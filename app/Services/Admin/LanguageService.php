<?php

namespace App\Services\Admin;

use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LanguageService
{
    public function createLanguage(array $data): Language
    {
        DB::beginTransaction();

        try {
            if (!empty($data['is_default'])) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            $data['sort_order'] = $data['sort_order'] ?? ((int) Language::max('sort_order') + 1);

            $language = Language::create($data);

            DB::commit();

            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Language creation failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function updateLanguage(Language $language, array $data): Language
    {
        DB::beginTransaction();

        try {
            if (!empty($data['is_default']) && !$language->is_default) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            $language->update($data);

            DB::commit();

            return $language->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Language update failed', ['id' => $language->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function deleteLanguage(Language $language): void
    {
        DB::beginTransaction();

        try {
            if ($language->is_default) {
                throw new \RuntimeException('Cannot delete the default language.');
            }

            $language->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Language deletion failed', ['id' => $language->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    public function toggleActive(Language $language): Language
    {
        try {
            if ($language->is_default && $language->is_active) {
                throw new \RuntimeException('Cannot deactivate the default language.');
            }

            $language->is_active = !$language->is_active;
            $language->save();

            return $language;
        } catch (\Exception $e) {
            Log::error('Language toggle active failed', ['id' => $language->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }
}
