<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        if (BlogCategory::count() > 0) {
            return;
        }

        $categories = [
            [
                'name' => ['en' => 'News', 'tr' => 'Haberler'],
                'slug' => ['en' => 'news', 'tr' => 'haberler'],
                'description' => [
                    'en' => 'Latest news and announcements.',
                    'tr' => 'Son haberler ve duyurular.',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => ['en' => 'Tips & Tricks', 'tr' => 'İpuçları'],
                'slug' => ['en' => 'tips-tricks', 'tr' => 'ipuclari'],
                'description' => [
                    'en' => 'Helpful tips and tricks for restaurant owners.',
                    'tr' => 'Restoran sahipleri için faydalı ipuçları ve püf noktaları.',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => ['en' => 'Product Updates', 'tr' => 'Ürün Güncellemeleri'],
                'slug' => ['en' => 'product-updates', 'tr' => 'urun-guncellemeleri'],
                'description' => [
                    'en' => 'New features and platform improvements.',
                    'tr' => 'Yeni özellikler ve platform iyileştirmeleri.',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $data) {
            BlogCategory::create($data);
        }
    }
}
