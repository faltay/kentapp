<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'code' => 'en',
                'name' => 'English',
                'native' => 'English',
                'flag' => '🇬🇧',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'tr',
                'name' => 'Turkish',
                'native' => 'Türkçe',
                'flag' => '🇹🇷',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($languages as $lang) {
            Language::updateOrCreate(
                ['code' => $lang['code']],
                $lang
            );
        }
    }
}
