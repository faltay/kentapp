# QR Menu SaaS — Geliştirme Planı

---

## AŞAMA 1: Sistem Temeli

### 1.1 Laravel Kurulumu
- [x] Laravel 12 projesi oluştur (`composer create-project laravel/laravel .`)
- [x] `.env` dosyasını yapılandır (DB, Redis, Mail, App URL)
- [x] Veritabanı bağlantısını test et
- [x] Timezone ve locale ayarla (`config/app.php`)

### 1.2 Composer Paketleri
- [x] `spatie/laravel-permission` — rol/izin yönetimi
- [x] `spatie/laravel-medialibrary` — dosya/resim yönetimi
- [x] `spatie/laravel-activitylog` — işlem logları
- [x] `mcamara/laravel-localization` — çok dil + URL yapısı
- [x] `yajra/laravel-datatables-oracle` — server-side tablolar
- [x] `simplesoftwareio/simple-qrcode` — QR kod üretimi
- [x] `stripe/stripe-php` — Stripe ödeme
- [x] `iyzico/iyzipay-php` — İyzico ödeme (Modül 10'da kuruldu)
- [x] `openai-php/laravel` — OpenAI GPT
- [x] `laravel/sanctum` — API auth
- [x] `predis/predis` — Redis client (phpredis extension yok)
- [ ] `sentry/sentry-laravel` — hata takibi (opsiyonel)

### 1.3 Dev Paketleri
- [x] `laravel/telescope` — debug/performans izleme
- [x] `larastan/larastan` — statik analiz (nunomaduro/larastan abandonded)
- [x] `friendsofphp/php-cs-fixer` — kod stili
- [x] `barryvdh/laravel-ide-helper` — IDE desteği

### 1.4 NPM Paketleri
- [x] `@tabler/core` — admin UI framework
- [x] `axios` — AJAX istekleri
- [x] `sweetalert2` — bildirim popup'ları
- [x] `jquery` — DataTables ve form işlemleri
- [x] Vite yapılandırmasını güncelle (3 ayrı entry: admin, restaurant, menu)

### 1.5 Paket Yapılandırmaları
- [x] `spatie/laravel-permission` — publish + migrate
- [x] `spatie/laravel-medialibrary` — publish + migrate
- [x] `spatie/laravel-activitylog` — publish + migrate
- [x] `mcamara/laravel-localization` — publish, `config/laravellocalization.php` düzenle (en+tr aktif, hideDefaultLocaleInURL=true)
- [x] `laravel/sanctum` — publish + migrate
- [x] `laravel/telescope` — publish + migrate (local only)
- [x] `.php-cs-fixer.php` oluştur (PSR-12 + PHP 8.2)
- [x] `phpstan.neon` oluştur (level 6)

### 1.6 Temel Mimari Sınıflar
- [x] `App\Http\Controllers\BaseController` — `success()`, `error()`, `created()` helper metodları
- [x] `App\Exceptions\RestaurantAccessException` — unauthorized, suspended, subscriptionRequired
- [x] `App\Exceptions\UsageLimitException` — exceeded(feature, limit)
- [x] Global exception handler güncelle (`bootstrap/app.php` — Laravel 12 stili)

### 1.7 Temel Middleware
- [x] `RestaurantAccess` — restoran var mı ve aktif mi kontrolü
- [x] `CheckSubscription` — aktif abonelik var mı kontrolü
- [x] `CheckUsageLimits` — plan limiti (menu_item, branch, table) kontrolü
- [x] Middleware alias'ları `bootstrap/app.php`'ye kaydet (Laravel 12'de Kernel yok)

### 1.8 Route Yapısı
- [x] `routes/web.php` — LaravelLocalization wrapper ile temel grup yapısını kur
- [x] `routes/api.php` — `api/v1` prefix ile API gruplarını kur
- [x] `routes/admin.php` — super admin panel route'ları
- [x] `routes/restaurant.php` — restoran panel route'ları
- [x] Dil değiştirme route'u ekle (`lang/{locale}`)

### 1.9 Layout & UI
- [x] `resources/views/layouts/admin.blade.php` — super admin layout (Tabler)
- [x] `resources/views/layouts/restaurant.blade.php` — restoran layout (Tabler)
- [x] `resources/views/layouts/menu.blade.php` — public menü layout
- [x] Ortak partial'lar: admin-header, admin-sidebar, restaurant-header, restaurant-sidebar, footer, alerts
- [x] Blade component'leri: `x-form.input`, `x-form.textarea`, `x-form.select`, `x-alert`
- [x] Global AJAX error handler (JavaScript — validation hatalarını yakala)
- [x] SweetAlert2 entegrasyonu (başarı/hata bildirimleri)

### 1.10 Localization Altyapısı
- [x] `resources/lang/en/common.php` — save, cancel, delete, loading vb.
- [x] `resources/lang/tr/common.php`
- [x] Dil dosyası klasör yapısını oluştur (`admin/`, `restaurant/`)
- [x] `lang/en/admin/users.php`, `lang/tr/admin/users.php`
- [x] `lang/en/admin/restaurants.php`, `lang/tr/admin/restaurants.php`
- [x] `lang/en/admin/plans.php`, `lang/tr/admin/plans.php`
- [x] `lang/en/restaurant/menu.php`, `lang/tr/restaurant/menu.php`
- [x] `lang/en/restaurant/branches.php`, `lang/tr/restaurant/branches.php`
- [x] `lang/en/restaurant/tables.php`, `lang/tr/restaurant/tables.php`

---

## AŞAMA 2: Modüller

---

### Modül 1: Kullanıcı & Kimlik Doğrulama ✅

**Migration & Model**
- [x] `users` tablosuna ek alanlar ekle (phone, is_active, restaurant_id)
- [x] `User` modeli güncelle (fillable, casts, ilişkiler, HasRoles, MustVerifyEmail)

**Auth**
- [x] Auth route'larını yapılandır (`routes/auth.php` — login, register, verify, password reset)
- [x] Login / Register / ForgotPassword / ResetPassword / VerifyEmail view'ları Tabler temasına uyarlandı
- [x] Email doğrulama akışı (MustVerifyEmail implement edildi)

**Rol Sistemi**
- [x] Sistem rollerini seeder ile oluştur: `super_admin`, `restaurant_owner`
- [x] `RoleSeeder` — temel roller + super admin kullanıcısı (`admin@qrmenu.test` / `password`)
- [x] `UserPolicy` oluştur (before() ile super_admin bypass)

**Admin — Kullanıcı Yönetimi**
- [x] `Admin\UserController` (index, create, store, show, edit, update, destroy)
- [x] `StoreUserRequest`, `UpdateUserRequest`
- [x] `UserService` (createUser, updateUser, deleteUser — DB transaction)
- [x] DataTables entegrasyonu (AJAX ile user listesi, server-side)
- [x] View'lar: index, create, edit, show
- [x] Dil dosyaları: `lang/en/admin/users.php`, `lang/tr/admin/users.php` (genişletildi)

---

### Modül 2: Abonelik Planları ✅

**Migration & Model**
- [x] `subscription_plans` tablosu (name JSON, slug, description JSON, price, price_yearly, currency, max_*, features JSON, is_active, is_featured, sort_order)
- [x] `SubscriptionPlan` modeli (fillable, casts, scopes, accessors, business logic)

**Admin — Plan Yönetimi**
- [x] `Admin\SubscriptionPlanController` (index, create, store, edit, update, destroy)
- [x] `StoreSubscriptionPlanRequest`, `UpdateSubscriptionPlanRequest`
- [x] `SubscriptionPlanService` (createPlan, updatePlan, deletePlan — DB transaction)
- [x] View'lar: index, create, edit, partials/actions
- [x] Dil dosyaları: `lang/en/admin/plans.php`, `lang/tr/admin/plans.php` (genişletildi)
- [x] `SubscriptionPlanSeeder` — Free, Standard, Pro planları oluşturuldu
- [x] Admin sidebar linkleri düzeltildi

---

### Modül 3: Restoran ✅

**Migration & Model**
- [x] `restaurants` tablosu (name JSON, slug, description JSON, phone, email, address, city, country, currency, timezone, is_active, is_suspended, user_id, subscription_plan_id)
- [x] `users` tablosuna restaurant_id FK constraint eklendi
- [x] `Restaurant` modeli (ilişkiler, accessor'lar, media collections: logo + banner, MediaLibrary)
- [x] `RestaurantPolicy` oluşturuldu (before() ile super_admin bypass)

**Admin — Restoran Yönetimi**
- [x] `Admin\RestaurantController` (index, show, edit, update, destroy)
- [x] `UpdateRestaurantRequest` (plan atama, is_active, is_suspended)
- [x] `Admin\RestaurantService` (updateRestaurant, deleteRestaurant — medya temizleme dahil)
- [x] DataTables entegrasyonu
- [x] View'lar: index, show, edit, partials/actions
- [x] Dil dosyaları: `lang/en/admin/restaurants.php`, `lang/tr/admin/restaurants.php` (genişletildi)

**Restoran — Kendi Bilgilerini Düzenleme**
- [x] `Restaurant\SettingsController` (edit, update)
- [x] `UpdateRestaurantSettingsRequest`
- [x] `Restaurant\SettingsService` (logo ve banner upload — Spatie MediaLibrary)
- [x] View'lar: settings/edit (3 tab: Genel, Marka, Konum)
- [x] Dil dosyaları: `lang/en/restaurant/settings.php`, `lang/tr/restaurant/settings.php`

---

### Modül 4: Şube (Branch) ✅

**Migration & Model**
- [x] `branches` tablosu (restaurant_id, name JSON, address, city, phone, email, is_active, is_main, sort_order)
- [x] `Branch` modeli (casts, scopes, accessors, full_address)
- [x] `BranchPolicy` oluşturuldu (is_main şube silinemez kontrolü dahil)
- [x] `Restaurant` modeline `branches()` ilişkisi eklendi

**Restoran — Şube Yönetimi**
- [x] `Restaurant\BranchController` (index, create, store, edit, update, destroy)
- [x] `StoreBranchRequest`, `UpdateBranchRequest`
- [x] `BranchService` (createBranch, updateBranch, deleteBranch — ana şube kontrolü dahil)
- [x] DataTables entegrasyonu
- [x] View'lar: index, create, edit, partials/form, partials/actions
- [x] Dil dosyaları: `lang/en/restaurant/branches.php`, `lang/tr/restaurant/branches.php` (genişletildi)
- [x] `CheckUsageLimits` middleware yeni mimariye uyarlandı (getPlanLimit, -1=sınırsız)
- [x] create/store route'larına `check_usage_limits:branch` middleware uygulandı
- [x] Restaurant sidebar'a Branches ve Settings linkleri eklendi

---

### Modül 5: Menü Kategorisi ✅

**Migration & Model**
- [x] `menu_categories` tablosu (restaurant_id, name JSON, description JSON, slug, is_active, sort_order — unique slug per restaurant)
- [x] `MenuCategory` modeli (MediaLibrary: image + thumb/webp conversion, scopes, localized accessors)
- [x] `MenuCategoryPolicy` oluşturuldu
- [x] `Restaurant` modeline `menuCategories()` ilişkisi eklendi

**Restoran — Kategori Yönetimi**
- [x] `Restaurant\MenuCategoryController` (index, create, store, edit, update, destroy, reorder)
- [x] `StoreMenuCategoryRequest`, `UpdateMenuCategoryRequest` (restaurant bazlı unique slug)
- [x] `MenuCategoryService` (createCategory, updateCategory, deleteCategory, reorder)
- [x] Sürükle-bırak sıralama (SortableJS CDN + AJAX reorder endpoint)
- [x] View'lar: index (sortable tablo), create, edit, partials/form (shared), partials/actions
- [x] Dil dosyaları: `lang/en/restaurant/categories.php`, `lang/tr/restaurant/categories.php`
- [x] Sidebar'a Kategoriler linki eklendi

---

### Modül 6: Menü Ürünü ✅

**Migration & Model**
- [x] `menu_items` tablosu (restaurant_id, menu_category_id, name/description JSON, slug unique/restaurant, price, compare_price, is_active, is_featured, is_vegetarian, is_vegan, is_spicy, is_gluten_free, preparation_time, calories, sort_order, view_count)
- [x] `MenuItem` modeli (MediaLibrary: image + gallery collections, thumb/medium/webp conversions)
- [x] `MenuItemPolicy` oluşturuldu
- [x] `Restaurant` ve `MenuCategory` modellerine `menuItems()` ilişkisi eklendi

**Restoran — Ürün Yönetimi**
- [x] `Restaurant\MenuItemController` (index+kategori filtresi, create, store, show, edit, update, destroy, duplicate)
- [x] `StoreMenuItemRequest`, `UpdateMenuItemRequest` (restaurant bazlı unique slug)
- [x] `MenuItemService` (createItem, updateItem, deleteItem, duplicateItem — medya temizleme dahil)
- [x] Resim upload (ana görsel + galeri, Spatie MediaLibrary — thumb, medium, webp)
- [x] DataTables entegrasyonu (kategori filtresi, fiyat/indirim gösterimi, diyet badge'leri)
- [x] Duplicate özelliği (kopya pasif oluşur, slug çakışması önlenir)
- [x] View'lar: index, create, edit, partials/form (shared), partials/actions
- [x] Dil dosyaları: `lang/en/restaurant/menu-items.php`, `lang/tr/restaurant/menu-items.php`
- [x] create/store route'larına `check_usage_limits:menu_item` uygulandı
- [x] Sidebar'a Menü Ürünleri linki eklendi

---

### Modül 7: Masa ✅

**Migration & Model**
- [x] `tables` tablosu (restaurant_id, branch_id nullable, name, capacity, is_active, sort_order)
- [x] `Table` modeli (scopes: forRestaurant, active, ordered, forBranch)
- [x] `TablePolicy` oluşturuldu

**Restoran — Masa Yönetimi**
- [x] `Restaurant\TableController` (index, create, store, edit, update, destroy)
- [x] `StoreTableRequest`, `UpdateTableRequest` (restaurant bazlı unique name)
- [x] `TableService` (createTable, updateTable, deleteTable — DB transaction)
- [x] DataTables entegrasyonu
- [x] View'lar: index, create, edit, partials/form, partials/actions
- [x] Dil dosyaları: `lang/en/restaurant/tables.php`, `lang/tr/restaurant/tables.php` (genişletildi)
- [x] `CheckUsageLimits` middleware'e `table` feature eklendi
- [x] create/store route'larına `check_usage_limits:table` uygulandı
- [x] `Restaurant` modeline `tables()` ilişkisi eklendi
- [x] Sidebar'a Masalar linki eklendi

---

### Modül 8: QR Kod ✅

**Service**
- [x] `QRCodeService` (getPublicUrl, generateSvg, generatePng, generateBulkZip)
- [x] `GenerateQRCodesJob` — senkron ZIP ile çözüldü (job gerekmedi)

**Restoran — QR Yönetimi**
- [x] `Restaurant\QRCodeController` (index, download, bulkDownload)
- [x] Tek masa QR üretimi (PNG indirme)
- [x] Tüm masalar toplu QR üretimi (ZIP, php.ini'de extension=zip aktif edildi)
- [x] QR kod indirme (PNG + ZIP)
- [x] View'lar: QR kart grid, şube filtresi, checkbox seçim
- [x] Dil dosyaları: `lang/en/restaurant/qr-codes.php`, `lang/tr/restaurant/qr-codes.php`
- [x] Sidebar'a QR Kodlar linki eklendi

---

### Modül 9: Public Menü (Müşteri Tarafı) ✅

**Model**
- [x] `menu_items.view_count` artırma mekanizması — Modül 13 API `items/{item}` endpoint'inde yapıldı

**Controller & View**
- [x] `Public\MenuController` (show) — slug ile restoran + eager load kategoriler+ürünler
- [x] Public menü view'ı (mobil öncelikli, özel Bootstrap 5 tasarım)
- [x] Sticky kategori navigation (yatay scroll-spy, Intersection Observer)
- [x] Client-side arama (name + description filtresi)
- [x] Diyet rozetleri (vegetarian, vegan, spicy, gluten-free, featured)
- [x] Fiyat + indirim gösterimi (compare_price, discount %)
- [x] Kalori / hazırlık süresi bilgisi
- [x] Pasif/askıya alınmış restoran için `menu/unavailable.blade.php` (503)
- [x] `?table=` parametresiyle masa adı header'da gösterilir
- [x] Dil dosyaları: `lang/en/menu.php`, `lang/tr/menu.php`
- [x] Route: `GET menu/{slug}` (LaravelLocalization ile)
- [x] `QRCodeService::getPublicUrl()` gerçek route'a bağlandı
- [x] Ürün detay modal — Bootstrap 5 Modal + Carousel, tüm görseller + galeri, rozetler, fiyat, kalori/hazırlık
- [x] Cache entegrasyonu — Aşama 3.6'da yapıldı (API + Public menü + hasActiveSubscription)

---

### Modül 10: Ödeme & Abonelik ✅

**Migration & Model**
- [x] `subscriptions` tablosu (restaurant_id, plan_id, status, billing_cycle, starts_at, ends_at, stripe/iyzico referansları)
- [x] `Subscription` modeli (STATUS/CYCLE sabitler, isActive, isCancelled, daysLeft, scopeActive)
- [x] `Restaurant::hasActiveSubscription()` gerçek implementasyon (subscriptions tablosundan)
- [x] `Restaurant` modeline `subscriptions()` ilişkisi eklendi
- [x] `SubscriptionPlan` modeline `subscriptions()` ilişkisi eklendi (yorum kaldırıldı)
- [x] `payments` tablosu — restaurant_id, subscription_id, provider, provider_payment_id, amount, currency, status, refunded_amount, refunded_at eklendi

**Stripe**
- [x] `StripeService` (createCheckoutSession, retrieveSession, constructWebhookEvent)
- [x] Stripe Checkout Session (hosted page, kart bilgisi Stripe'ta)
- [x] `billing/stripe/success` — ödeme doğrulama ve abonelik aktivasyonu
- [x] Stripe webhook endpoint (`POST webhooks/stripe`, CSRF dışında)

**İyzico**
- [x] `IyzicoService` (createCheckoutForm, retrieveCheckoutForm)
- [x] İyzico callback endpoint (`POST webhooks/iyzico`, CSRF dışında)
- [x] `iyzico/iyzipay-php` paketi kuruldu

**Billing**
- [x] `SubscriptionService` (subscribeFree, activateFromStripe, activateFromIyzico, cancel)
- [x] `Restaurant\BillingController` (index, plans, checkout, stripeSuccess, stripeWebhook, iyzicoCallback, cancel)
- [x] Plan seçimi ve ödeme akışı (Stripe + İyzico)
- [x] Aylık/Yıllık fatura dönemi toggle
- [x] Abonelik iptali (dönem sonuna kadar erişim devam eder)
- [x] View'lar: `billing/index`, `billing/plans`
- [x] Dil dosyaları: `lang/en/restaurant/billing.php`, `lang/tr/restaurant/billing.php`
- [x] `config/services.php`'e stripe ve iyzico blokları eklendi
- [x] Sidebar'a Fatura & Abonelik linki eklendi
- [x] Fatura listeleme — restoran fatura geçmişi (`billing/history`), admin tüm ödemeler listesi

---

### Modül 11: AI — Menü Analiz & Çeviri ✅

**Service**
- [x] `AI\MenuParserService` — GPT-4o Vision ile resimden JSON menü çıkarma, DB'ye persist
- [x] `AI\MenuTranslatorService` — batch çeviri (20 item/istek), gpt-4o-mini
- [x] `ParseMenuJob` — ShouldQueue, timeout=120, failed() hook
- [x] `TranslateMenuJob` — ShouldQueue, timeout=180, failed() hook
- [x] `ai_menu_jobs` migration & `AiMenuJob` modeli (status/type sabitler, markProcessing/Completed/Failed)
- [x] QUEUE_CONNECTION=database (yerel Redis yok — database queue kullanılıyor)

**Restoran — AI Araçları**
- [x] `Restaurant\AIMenuController` (index, upload, translate, status)
- [x] Menü yükleme formu (resim — JPG/PNG/WEBP, max 5 MB)
- [x] İşlem durumu takibi (3 saniyelik polling, clearInterval on complete/fail)
- [x] Kategori eşleştirme ve ürün oluşturma/güncelleme mantığı (MenuParserService::persistToDatabase)
- [x] View: `restaurant/ai-menu/index` (parse card + translate card + recent jobs tablo)
- [x] Dil dosyaları: `lang/en/restaurant/ai.php`, `lang/tr/restaurant/ai.php`
- [x] Routes: GET ai-menu, POST ai-menu/upload, POST ai-menu/translate, GET ai-menu/status/{aiMenuJob}
- [x] Sidebar'a AI Menü linki eklendi
- [ ] PDF desteği (ileride — şu an sadece resim)

---

### Modül 12: Blog & Sayfalar ✅

**Migration & Model**
- [x] `posts` tablosu (user_id, title JSON, slug string/unique, excerpt JSON, content JSON, is_published, published_at, sort_order) — slug string olarak bırakıldı, locale prefix LaravelLocalization'dan geliyor
- [x] `pages` tablosu (title JSON, slug string/unique, content JSON, is_published, sort_order)
- [x] `Post` modeli (scopePublished, scopeOrdered, localizedTitle/Excerpt/Content accessor, isPublished())
- [x] `Page` modeli (scopePublished, scopeOrdered, localizedTitle/Content accessor)

**Admin — İçerik Yönetimi**
- [x] `Admin\PostController` (index+DT, create, store, edit, update, destroy, togglePublish)
- [x] `Admin\PageController` (index+DT, create, store, edit, update, destroy)
- [x] `PostService` (createPost, updatePost, deletePost, togglePublish, resolveSlug)
- [x] `PageService` (createPage, updatePage, deletePage, resolveSlug)
- [x] `StorePostRequest`, `UpdatePostRequest`, `StorePageRequest`, `UpdatePageRequest`
- [x] WYSIWYG editör entegrasyonu — Quill.js 1.3.7 CDN (lisanssız, MIT)
- [x] Dil sekmesi (EN/TR) — her dil için ayrı Quill instance
- [x] View'lar: admin/posts (index, create, edit, partials/form, partials/actions, partials/quill-init)
- [x] View'lar: admin/pages (index, create, edit, partials/form, partials/actions, partials/quill-init)

**Public Blog & Sayfalar**
- [x] `Public\BlogController` (index — paginate(9), show)
- [x] `Public\StaticPageController` (show)
- [x] View'lar: blog/index (kart grid), blog/show (makale detay + stil), pages/show
- [x] Dil dosyaları: `lang/en/blog.php`, `lang/tr/blog.php`
- [x] Dil dosyaları: `lang/en/admin/posts.php`, `lang/tr/admin/posts.php`
- [x] Dil dosyaları: `lang/en/admin/pages.php`, `lang/tr/admin/pages.php`
- [x] Routes: admin (posts resource + toggle-publish, pages resource), public (blog/*, pages/*)
- [x] Admin sidebar'a Blog ve Pages linkleri eklendi

---

### Modül 13: API ✅

**Endpoints**
- [x] `GET api/v1/menu/{slug}` — tam menü (restaurant + aktif kategoriler + ürünler)
- [x] `GET api/v1/menu/{slug}/categories` — sadece kategoriler (item yok)
- [x] `GET api/v1/menu/{slug}/items` — ürünler (?category, ?search, ?featured, ?per_page — max 100)
- [x] `GET api/v1/menu/{slug}/items/{item}` — tek ürün detayı + view_count artırma

**API Altyapısı**
- [x] `MenuItemResource` — name, description (locale bazlı), price, badges, gallery
- [x] `MenuCategoryResource` — name, description, slug, image, whenLoaded(items)
- [x] `RestaurantResource` — name, slug, description, currency, logo, city, country
- [x] `SetApiLocale` middleware — ?locale= query param veya Accept-Language header (en/tr)
- [x] Rate limiting: public 60/dk (`throttle:60,1`), authenticated 1000/dk
- [x] Pasif/askıya alınmış restoran → 404 (findRestaurant helper)
- [x] Locale desteği: ?locale=tr veya Accept-Language: tr header
- [x] API dokümantasyonu — Scribe ile üretildi (`public/docs/`), Postman collection + OpenAPI spec dahil. `composer docs` ile yeniden üretilir.

---

## AŞAMA 3: Tamamlama

### 3.1 Dashboard İstatistikleri ✅
- [x] `Admin\DashboardController` — total_users, total_restaurants, active_subscriptions, total_revenue
- [x] Admin dashboard — aylık gelir grafiği (ApexCharts bar, son 6 ay), plan dağılımı, son ödemeler, son restoranlar
- [x] `Restaurant\DashboardController` — categories, active_items, total_items, tables, active_tables, total_views
- [x] Restoran dashboard — abonelik uyarısı, özet kartlar, hızlı erişim, abonelik kartı (progress bar), top ürünler, son AI işleri
- [x] Dil dosyaları: `lang/en/admin/dashboard.php`, `lang/tr/admin/dashboard.php`
- [x] Dil dosyaları: `lang/en/restaurant/dashboard.php`, `lang/tr/restaurant/dashboard.php`
- [x] `common.view_all` eklendi (EN + TR)

### 3.2 E-posta Bildirimleri ✅
- [x] `WelcomeMail` — kayıt sonrası, `Registered` event listener ile tetiklenir (queue)
- [x] `SubscriptionStartedMail` — subscribeFree / activateFromStripe / activateFromIyzico sonrası (queue)
- [x] `SubscriptionCancelledMail` — abonelik iptal sonrası (queue)
- [x] `PaymentFailedMail` — Stripe `invoice.payment_failed` webhook (queue)
- [x] `SendWelcomeEmail` listener — AppServiceProvider'da `Registered` event'e bağlandı
- [x] Email view'ları: `emails/layout.blade.php` + 4 içerik şablonu
- [x] Dil dosyaları: `lang/en/emails.php`, `lang/tr/emails.php`
- [x] ShouldQueue — tüm Mailable'lar queue'ya gönderilir (database driver)

### 3.3 Factory & Seeder'lar ✅
- [x] `UserFactory` — `is_active` alanı eklendi
- [x] `RestaurantFactory` — name/description JSON, suspended/inactive state'leri
- [x] `MenuCategoryFactory` — name JSON (EN+TR map), slug, sort_order
- [x] `MenuItemFactory` — name/description JSON, price, compare_price, tüm boolean flag'ler, active/featured state'leri
- [x] `TableFactory` — name, capacity, active state
- [x] `PostFactory` — title/excerpt/content JSON, published/draft state'leri
- [x] `PageFactory` — title/content JSON
- [x] `RestaurantSeeder` — 2 demo restoran (Standard + Free plan), 4 kategori + 8/4 ürün/kategori, masalar
- [x] `PostSeeder` — 4 blog yazısı (3 published + 1 draft), EN+TR içerik
- [x] `PageSeeder` — 3 statik sayfa (About, Privacy Policy, Terms of Service), EN+TR içerik
- [x] `DatabaseSeeder` — tüm seeder'ları doğru sırayla çağırır
- [x] `db:seed --force` başarıyla çalıştı — tüm veriler oluşturuldu

### 3.4 Feature Testleri
- [x] Feature testleri — kritik akışlar

### 3.5 Unit Testleri
- [x] Unit testleri — service ve model metodları

### 3.6 Performance
- [x] Performance: eager loading kontrolü, N+1 tespiti, cache doğrulama

### 3.7 Security
- [x] Security: tüm policy'ler, rate limiting, input validation

### 3.8 Production Yapılandırması
- [x] Production `.env` yapılandırması

### 3.9 Pre-commit Hooks
- [x] Pre-commit hook kurulumu (PHP syntax, CS Fixer, PHPStan, tests)
