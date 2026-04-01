<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('super_admin')->first();
        if (! $admin) {
            return;
        }

        // Skip if posts already exist
        if (Post::count() > 0) {
            return;
        }

        $newsCategory = BlogCategory::where('slug->en', 'news')->first();
        $tipsCategory = BlogCategory::where('slug->en', 'tips-tricks')->first();
        $updatesCategory = BlogCategory::where('slug->en', 'product-updates')->first();

        $posts = [
            [
                'title' => ['en' => 'How QR Menus Are Transforming Restaurants', 'tr' => 'QR Menüler Restoranları Nasıl Dönüştürüyor'],
                'slug' => 'how-qr-menus-are-transforming-restaurants',
                'excerpt' => [
                    'en' => 'Discover how digital QR menus are helping restaurants save costs and improve customer experience.',
                    'tr' => 'Dijital QR menülerin restoranlara maliyetleri düşürme ve müşteri deneyimini geliştirme konusunda nasıl yardımcı olduğunu keşfedin.',
                ],
                'content' => [
                    'en' => '<p>The restaurant industry is rapidly adopting QR code menus as a cost-effective and hygienic alternative to traditional printed menus.</p><p>With a simple scan, customers can view the full menu, including high-quality photos and detailed descriptions, right from their smartphones.</p><p>Restaurant owners benefit from instant menu updates without reprinting costs, detailed analytics on popular items, and improved operational efficiency.</p>',
                    'tr' => '<p>Restoran sektörü, geleneksel basılı menülere uygun maliyetli ve hijyenik bir alternatif olarak QR kod menülerini hızla benimsemektedir.</p><p>Basit bir tarama ile müşteriler, akıllı telefonlarından yüksek kaliteli fotoğraflar ve ayrıntılı açıklamalar dahil tam menüyü görebilir.</p><p>Restoran sahipleri, yeniden baskı maliyeti olmadan anında menü güncellemelerinden, popüler ürünler hakkında ayrıntılı analizlerden ve geliştirilmiş operasyonel verimlilikten yararlanır.</p>',
                ],
                'blog_category_id' => $newsCategory?->id,
                'is_published' => true,
                'published_at' => now()->subDays(30),
                'sort_order' => 1,
            ],
            [
                'title' => ['en' => '5 Tips to Optimize Your Digital Menu', 'tr' => 'Dijital Menünüzü Optimize Etmek İçin 5 İpucu'],
                'slug' => '5-tips-to-optimize-your-digital-menu',
                'excerpt' => [
                    'en' => 'Learn the best practices for creating a digital menu that increases orders and delights customers.',
                    'tr' => 'Siparişleri artıran ve müşterileri memnun eden dijital bir menü oluşturmak için en iyi uygulamaları öğrenin.',
                ],
                'content' => [
                    'en' => '<p>A well-designed digital menu can significantly increase your average order value. Here are five proven tips.</p><p><strong>1. Use high-quality photos</strong> — Dishes with photos receive 30% more orders on average.</p><p><strong>2. Write compelling descriptions</strong> — Describe taste, texture, and key ingredients.</p><p><strong>3. Highlight bestsellers</strong> — Mark your most popular items to guide customers.</p><p><strong>4. Keep it updated</strong> — Remove unavailable items promptly to avoid disappointment.</p><p><strong>5. Use categories wisely</strong> — Logical grouping helps customers find what they want faster.</p>',
                    'tr' => '<p>İyi tasarlanmış bir dijital menü, ortalama sipariş değerinizi önemli ölçüde artırabilir. İşte kanıtlanmış beş ipucu.</p><p><strong>1. Yüksek kaliteli fotoğraflar kullanın</strong> — Fotoğraflı yemekler ortalama %30 daha fazla sipariş alır.</p><p><strong>2. Çekici açıklamalar yazın</strong> — Tat, doku ve temel malzemeleri açıklayın.</p><p><strong>3. En çok satanları vurgulayın</strong> — Müşterilere rehberlik etmek için en popüler ürünleri işaretleyin.</p><p><strong>4. Güncel tutun</strong> — Hayal kırıklığını önlemek için mevcut olmayan ürünleri hemen kaldırın.</p><p><strong>5. Kategorileri akıllıca kullanın</strong> — Mantıklı gruplama, müşterilerin istediklerini daha hızlı bulmasına yardımcı olur.</p>',
                ],
                'blog_category_id' => $tipsCategory?->id,
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'sort_order' => 2,
            ],
            [
                'title' => ['en' => 'AI-Powered Menu Translation: A Game Changer', 'tr' => 'Yapay Zeka Destekli Menü Çevirisi: Oyunu Değiştiriyor'],
                'slug' => 'ai-powered-menu-translation-game-changer',
                'excerpt' => [
                    'en' => 'How our AI translation feature helps restaurants reach international customers effortlessly.',
                    'tr' => 'Yapay zeka çeviri özelliğimizin restoranlara uluslararası müşterilere zahmetsizce ulaşmalarına nasıl yardımcı olduğu.',
                ],
                'content' => [
                    'en' => '<p>Serving international guests used to require expensive professional translation services. Not anymore.</p><p>Our AI-powered translation feature can translate your entire menu into multiple languages in seconds, making your restaurant accessible to tourists and international visitors.</p><p>The AI understands culinary context, so "Schnitzel" stays "Schnitzel" rather than being awkwardly translated as "thin meat".</p>',
                    'tr' => '<p>Uluslararası misafirlere hizmet vermek eskiden pahalı profesyonel çeviri hizmetleri gerektiriyordu. Artık değil.</p><p>Yapay zeka destekli çeviri özelliğimiz, tüm menünüzü saniyeler içinde birden fazla dile çevirerek restoranınızı turistler ve uluslararası ziyaretçilere erişilebilir kılabilir.</p><p>Yapay zeka mutfak bağlamını anlar, bu nedenle "Schnitzel" garip bir şekilde "ince et" olarak çevrilmek yerine "Schnitzel" olarak kalır.</p>',
                ],
                'blog_category_id' => $updatesCategory?->id,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'sort_order' => 3,
            ],
            [
                'title' => ['en' => 'Coming Soon: Table Ordering Feature', 'tr' => 'Yakında: Masa Sipariş Özelliği'],
                'slug' => 'coming-soon-table-ordering-feature',
                'excerpt' => [
                    'en' => 'We are working on an exciting new feature that will allow customers to place orders directly from the QR menu.',
                    'tr' => 'Müşterilerin QR menüden doğrudan sipariş verebilmesini sağlayacak heyecan verici yeni bir özellik üzerinde çalışıyoruz.',
                ],
                'content' => [
                    'en' => '<p>We are thrilled to announce that we are working on a table ordering feature for our QR menu platform.</p><p>This feature will allow customers to browse the menu, add items to their cart, and send their order directly to the kitchen — all without needing to call a waiter.</p><p>Stay tuned for updates!</p>',
                    'tr' => '<p>QR menü platformumuz için bir masa sipariş özelliği üzerinde çalıştığımızı duyurmaktan heyecan duyuyoruz.</p><p>Bu özellik, müşterilerin menüye göz atmasına, sepetlerine ürün eklemesine ve garson çağırmadan doğrudan mutfağa sipariş göndermesine olanak tanıyacak.</p><p>Güncellemeler için bizi takip etmeye devam edin!</p>',
                ],
                'blog_category_id' => $updatesCategory?->id,
                'is_published' => false,
                'published_at' => null,
                'sort_order' => 4,
            ],
        ];

        foreach ($posts as $postData) {
            Post::firstOrCreate(
                ['slug' => $postData['slug']],
                array_merge($postData, ['user_id' => $admin->id])
            );
        }
    }
}
