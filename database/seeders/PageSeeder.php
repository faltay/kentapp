<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if pages already exist
        if (Page::count() > 0) {
            return;
        }

        $pages = [
            [
                'slug' => 'about',
                'title' => ['en' => 'About Us', 'tr' => 'Hakkımızda'],
                'content' => [
                    'en' => '<h2>About QR Menu</h2><p>QR Menu is a modern SaaS platform that helps restaurants create and manage digital menus accessible via QR codes.</p><p>Our mission is to make digital transformation simple and affordable for every restaurant, from small cafes to large chains.</p><h3>Our Story</h3><p>Founded in 2024, we built this platform because we believed every restaurant deserved a beautiful, easy-to-update digital menu without the complexity or high costs of traditional solutions.</p>',
                    'tr' => '<h2>QR Menu Hakkında</h2><p>QR Menu, restoranların QR kodlar aracılığıyla erişilebilen dijital menüler oluşturmasına ve yönetmesine yardımcı olan modern bir SaaS platformudur.</p><p>Misyonumuz, küçük kafelerden büyük zincirlere kadar her restoran için dijital dönüşümü basit ve uygun fiyatlı hale getirmektir.</p><h3>Hikayemiz</h3><p>2024 yılında kurulan şirketimiz, her restoranın geleneksel çözümlerin karmaşıklığı veya yüksek maliyetleri olmadan güzel, kolayca güncellenebilir bir dijital menüyü hak ettiğine inandığımız için bu platformu inşa ettik.</p>',
                ],
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'privacy-policy',
                'title' => ['en' => 'Privacy Policy', 'tr' => 'Gizlilik Politikası'],
                'content' => [
                    'en' => '<h2>Privacy Policy</h2><p>Last updated: January 1, 2025</p><p>Your privacy is important to us. This privacy policy explains how we collect, use, and protect your personal information.</p><h3>Information We Collect</h3><p>We collect information you provide when registering for an account, including your name, email address, and business information.</p><h3>How We Use Your Information</h3><p>We use your information to provide and improve our services, send you important updates, and communicate with you about your account.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p><h3>Contact Us</h3><p>If you have questions about this privacy policy, please contact us at privacy@qrmenu.com.</p>',
                    'tr' => '<h2>Gizlilik Politikası</h2><p>Son güncelleme: 1 Ocak 2025</p><p>Gizliliğiniz bizim için önemlidir. Bu gizlilik politikası, kişisel bilgilerinizi nasıl topladığımızı, kullandığımızı ve koruduğumuzu açıklamaktadır.</p><h3>Topladığımız Bilgiler</h3><p>Bir hesap için kaydolurken sağladığınız adınız, e-posta adresiniz ve iş bilgileriniz dahil bilgileri topluyoruz.</p><h3>Bilgilerinizi Nasıl Kullanırız</h3><p>Bilgilerinizi hizmetlerimizi sağlamak ve geliştirmek, size önemli güncellemeler göndermek ve hesabınız hakkında sizinle iletişim kurmak için kullanırız.</p><h3>Veri Güvenliği</h3><p>Kişisel bilgilerinizi yetkisiz erişim, değiştirme, ifşa etme veya imhadan korumak için uygun güvenlik önlemleri uyguluyoruz.</p><h3>Bize Ulaşın</h3><p>Bu gizlilik politikası hakkında sorularınız varsa lütfen privacy@qrmenu.com adresinden bizimle iletişime geçin.</p>',
                ],
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'terms-of-service',
                'title' => ['en' => 'Terms of Service', 'tr' => 'Kullanım Koşulları'],
                'content' => [
                    'en' => '<h2>Terms of Service</h2><p>Last updated: January 1, 2025</p><p>By using QR Menu, you agree to these terms of service. Please read them carefully.</p><h3>Use of Service</h3><p>You may use our service only for lawful purposes and in accordance with these terms. You agree not to use the service in any way that violates any applicable laws or regulations.</p><h3>Accounts</h3><p>You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer.</p><h3>Subscription and Payments</h3><p>Subscription fees are billed in advance on a monthly or yearly basis. Refunds are handled on a case-by-case basis.</p><h3>Termination</h3><p>We may terminate or suspend your account immediately if you breach these terms.</p>',
                    'tr' => '<h2>Kullanım Koşulları</h2><p>Son güncelleme: 1 Ocak 2025</p><p>QR Menu\'yu kullanarak bu kullanım koşullarını kabul etmiş olursunuz. Lütfen dikkatlice okuyun.</p><h3>Hizmetin Kullanımı</h3><p>Hizmetimizi yalnızca yasal amaçlar için ve bu koşullara uygun olarak kullanabilirsiniz. Hizmeti geçerli yasaları veya düzenlemeleri ihlal edecek herhangi bir şekilde kullanmamayı kabul edersiniz.</p><h3>Hesaplar</h3><p>Hesabınızın ve şifrenizin gizliliğini korumaktan ve bilgisayarınıza erişimi kısıtlamaktan siz sorumlusunuz.</p><h3>Abonelik ve Ödemeler</h3><p>Abonelik ücretleri aylık veya yıllık olarak peşin faturalandırılır. İadeler her vakaya göre değerlendirilir.</p><h3>Fesih</h3><p>Bu koşulları ihlal etmeniz durumunda hesabınızı derhal feshedebilir veya askıya alabiliriz.</p>',
                ],
                'is_published' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(['slug' => $pageData['slug']], $pageData);
        }
    }
}
