# KentApp — Proje Rehberi

## Proje Özeti

**KentApp** — Kentsel dönüşüm ve arsa geliştirme ağı. Arsa sahiplerini müteahhitler ve emlak danışmanlarıyla buluşturan bir platform.

Mobil uygulama için **API backend** + **Super Admin Paneli** içerir.

---

## Teknoloji Stack

- **Backend**: Laravel 12+, PHP 8.2+
- **Veritabanı**: MySQL, Redis (cache/session/queue)
- **Admin UI**: Tabler CSS
- **Ödeme**: Stripe (yurtdışı), İyzico (yurtiçi)
- **Temel Paketler**: Spatie Permission, Spatie MediaLibrary, Spatie ActivityLog, LaravelLocalization, Yajra DataTables

---

## Domain Modeli

### Kullanıcı Rolleri (3 tip)

| `users.type` | Sabit | Açıklama |
|---|---|---|
| `land_owner` | `User::TYPE_LAND_OWNER` | Arsa sahibi — ilan oluşturur |
| `contractor` | `User::TYPE_CONTRACTOR` | Müteahhit — kontör harcayarak ilanları görüntüler |
| `agent` | `User::TYPE_AGENT` | Emlak danışmanı |
| `admin` | `User::TYPE_ADMIN` | Super admin |

Spatie rolleri: `super_admin` (admin paneli), `verified_contractor` (yetki belgesi onaylı müteahhit).

### Temel Modüller

#### 1. İlanlar (`listings`)
Arsa sahiplerinin oluşturduğu ilanlar.

| Kolon | Tip | Açıklama |
|---|---|---|
| `user_id` | FK | İlan sahibi (land_owner) |
| `type` | enum | `urban_renewal` \| `land` |
| `status` | enum | `draft` \| `pending` \| `active` \| `rejected` \| `passive` |
| `province` | string | İl |
| `district` | string | İlçe |
| `neighborhood` | string | Mahalle |
| `address` | text | Açık adres |
| `ada_no` | string | Ada numarası |
| `parcel_no` | string | Parsel numarası |
| `area_m2` | decimal | Alan (m²) |
| `floor_count` | int | Kat adedi |
| `zoning_status` | enum | `residential` \| `commercial` \| `mixed` \| `unplanned` |
| `taks` | decimal | TAKS oranı |
| `kaks` | decimal | KAKS oranı |
| `description` | text | Açıklama |
| `is_featured` | bool | Vitrin/sponsorlu ilan |
| `view_count` | int | Görüntülenme sayısı |
| `expires_at` | datetime | İlan bitiş tarihi |

İlişkiler: `user` (land_owner), `media` (tapu belgesi), `views` (görüntülemeler), `reviews`

#### 2. Kontör Sistemi (`credit_packages`, `credit_transactions`)

**Kontör paketleri (`credit_packages`):**
| Kolon | Tip | Açıklama |
|---|---|---|
| `name` | string | Paket adı (5 Kontör, 20 Kontör...) |
| `credits` | int | Kontör miktarı |
| `price` | decimal | Fiyat |
| `currency` | string | Para birimi |
| `is_active` | bool | - |

**Kontör işlemleri (`credit_transactions`):**
| Kolon | Tip | Açıklama |
|---|---|---|
| `user_id` | FK | Müteahhit |
| `listing_id` | FK nullable | Hangi ilan için harcandı |
| `type` | enum | `purchase` \| `spend` \| `refund` |
| `amount` | int | + satın alma, - harcama |
| `balance_after` | int | İşlem sonrası bakiye |
| `description` | string | İşlem açıklaması |

Müteahhit bir ilanı görüntülemek için kontör harcar → ilan sahibinin iletişim bilgisi açılır. Aynı ilana tekrar bakmak kontör harcamaz (bir kez ödeme).

**Mahalle ekleme:** `ContractorProfile.working_neighborhoods` JSON array. Yeni mahalle eklemek 10 kontör/yıl harcar.

#### 3. Müteahhit Profili (`contractor_profiles`)
| Kolon | Tip | Açıklama |
|---|---|---|
| `user_id` | FK | 1:1 User ile |
| `company_name` | string | Firma adı |
| `authorized_name` | string | Yetkili kişi |
| `company_phone` | string | - |
| `company_email` | string | - |
| `company_address` | text | - |
| `working_neighborhoods` | json | `[{province, district, neighborhood}]` |
| `certificate_status` | enum | `none` \| `pending` \| `approved` \| `rejected` |
| `certificate_number` | string nullable | Onaylı belge numarası |
| `founded_year` | int nullable | - |
| `credit_balance` | int | Mevcut kontör bakiyesi |

Media: `authority_certificate` (kentsel dönüşüm yetki belgesi — admin onayı gerekir)

#### 4. Arsa Sahibi Profili (`land_owner_profiles`)
| Kolon | Tip | Açıklama |
|---|---|---|
| `user_id` | FK | 1:1 User ile |
| `tc_number` | string | TC Kimlik No |

#### 5. İlan Görüntülemeleri (`listing_views`)
| Kolon | Tip | Açıklama |
|---|---|---|
| `listing_id` | FK | - |
| `contractor_id` | FK | Görüntüleyen müteahhit |
| `credits_spent` | int | Harcanan kontör |
| `viewed_at` | datetime | - |

#### 6. Değerlendirmeler (`reviews`)
| Kolon | Tip | Açıklama |
|---|---|---|
| `reviewer_id` | FK | Yorum yapan user |
| `reviewed_id` | FK | Değerlendirilen user (müteahhit) |
| `listing_id` | FK nullable | İlgili ilan |
| `rating` | tinyint | 1-5 yıldız |
| `comment` | text | Yorum metni |
| `status` | enum | `pending` \| `approved` \| `rejected` |

#### 7. Coğrafi Veri (`provinces`, `districts`, `neighborhoods`)
İl/İlçe/Mahalle hiyerarşisi — seed data olarak yüklenecek.

| Tablo | Kolonlar |
|---|---|
| `provinces` | `id`, `name`, `code` |
| `districts` | `id`, `province_id`, `name` |
| `neighborhoods` | `id`, `district_id`, `name` |

---

## Admin Paneli Modülleri

```
Kullanıcılar          → Tüm kullanıcılar (tip filtreli), askıya alma, impersonate
İlanlar               → Listeleme, onay/red/pasif, vitrin atama
Müteahhit Belgeleri   → Yetki belgesi onay/red kuyruğu
Kontör Paketleri      → Paket oluşturma/düzenleme/silme
Kontör Logları        → Tüm credit_transactions (filtreli)
Değerlendirmeler      → Moderasyon (onay/red)
Coğrafi Veri          → İl/İlçe/Mahalle yönetimi
Ödemeler              → Kontör satın alma ödemeleri
Blog & Sayfalar       → İçerik yönetimi (mevcut)
Ayarlar               → Sistem ayarları (mevcut)
```

---

## Klasör Yapısı

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # super_admin paneli
│   │   │   ├── ListingController.php
│   │   │   ├── ContractorCertificateController.php
│   │   │   ├── CreditPackageController.php
│   │   │   ├── CreditTransactionController.php
│   │   │   ├── ReviewController.php
│   │   │   └── GeographyController.php
│   │   ├── Api/V1/          # Mobil uygulama API
│   │   │   ├── AuthController.php
│   │   │   ├── ListingController.php
│   │   │   ├── ContractorController.php
│   │   │   ├── CreditController.php
│   │   │   └── ReviewController.php
│   │   ├── Auth/
│   │   └── Public/
│   ├── Requests/
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Listing.php
│   ├── ContractorProfile.php
│   ├── LandOwnerProfile.php
│   ├── ListingView.php
│   ├── Review.php
│   ├── CreditPackage.php
│   ├── CreditTransaction.php
│   ├── Province.php
│   ├── District.php
│   └── Neighborhood.php
├── Services/
│   ├── Admin/
│   │   ├── ListingService.php
│   │   ├── ContractorCertificateService.php
│   │   └── CreditPackageService.php
│   ├── CreditService.php       # Kontör satın alma + harcama mantığı
│   ├── ListingViewService.php  # İlan görüntüleme + kontör düşme
│   └── ReviewService.php
└── Policies/
    ├── ListingPolicy.php
    ├── ReviewPolicy.php
    └── CreditTransactionPolicy.php
```

---

## Zorunlu Mimari Kararlar

### 1. AJAX/JSON Response (ZORUNLU)
Admin panelindeki **tüm form işlemleri** AJAX ile yapılır, sayfa yenilenmez.

```php
// Başarılı (store → 201, update/delete → 200)
return response()->json(['success' => true, 'message' => __('admin.listings.created'), 'data' => ['id' => $item->id]], 201);

// Hata
return response()->json(['success' => false, 'message' => __('admin.listings.creation_failed'), 'error' => config('app.debug') ? $e->getMessage() : null], 500);
```

**HTTP Status Codes**: `200` GET/PUT/DELETE · `201` POST · `422` validation · `403` permission · `500` server error

### 2. Service Layer (ZORUNLU)
İş mantığı controller'da yazılmaz. DB transaction gerektiren her işlem Service'te.

### 3. Form Request (ZORUNLU)
Validation controller'da değil, ayrı `FormRequest` sınıfında.

### 4. Try-Catch + Log (ZORUNLU)
Tüm `store`, `update`, `destroy` metodlarında try-catch ve `Log::error`.

---

## İsimlendirme Kuralları

| Öğe | Kural | Örnek |
|---|---|---|
| Controller | PascalCase + "Controller" | `ListingController` |
| Model | Singular PascalCase | `Listing`, `ContractorProfile` |
| Service | PascalCase + "Service" | `CreditService` |
| DB Tablo | snake_case plural | `listings`, `credit_transactions` |
| Route URL | kebab-case | `/credit-packages`, `/listing-views` |
| Route name | dot.notation | `admin.listings.index` |

**Route pattern**: `{panel}.{resource}.{action}`
```
admin.listings.index
admin.contractor-certificates.approve
api.v1.listings.show
```

---

## Model Standartları

### Property Sırası
1. Constants
2. `$fillable` / `$casts`
3. Static Methods
4. Relationships
5. Query Scopes
6. Accessors / Mutators
7. Business Logic Methods
8. `registerMediaCollections()`
9. `getActivitylogOptions()`

---

## Database Standartları

```php
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->index(['user_id', 'status']);
$table->index(['district', 'status']);
```

---

## API Standartları (Mobil)

- Tüm API endpoint'leri `/api/v1/` prefix'i altında
- Auth: Laravel Sanctum token
- Public endpoint'ler: `throttle:60,1`
- Authenticated: `throttle:1000,1`
- Response format:
```json
{ "success": true, "data": {}, "message": "" }
{ "success": false, "message": "", "errors": {} }
```

---

## İş Kuralları

1. **Kontör harcama**: Müteahhit bir ilanı ilk görüntülediğinde `listing_views` kaydı oluşur + kontör düşer. Aynı ilan tekrar açılırsa kontör harcanmaz.
2. **İletişim bilgisi**: Müteahhit ilanı görüntüledikten sonra ilan sahibinin telefon numarasına erişebilir.
3. **Yetki belgesi**: Müteahhit kaydı tamamlanınca `certificate_status = pending`. Admin onaylarsa `approved` + `verified_contractor` rolü atanır.
4. **İlan onayı**: Yeni ilan `pending` statüsüyle oluşur, admin onaylayana kadar `active` olmaz.
5. **Vitrin ilan**: `is_featured = true` olan ilanlar, müteahhit ana sayfasında öne çıkar. Ödeme veya admin atamasıyla aktif olur.
6. **Mahalle ekleme**: Müteahhitin `working_neighborhoods` listesine yeni ekleme yaparken `CreditService::spend(10)` çağrılır.

---

## Mevcut Şablon Modüller (qrmenu kalıntısı — silme)

`Restaurant` ve `Branch` modülleri yeni modüller yazılırken **şablon** olarak kullanılır (CRUD pattern referansı), ardından silinebilir. Aktif iş mantığı içermezler.

---

## Git Standartları

### Commit Mesajı: `type(scope): description`
```
feat(listings): add listing approval workflow
feat(credits): implement credit spending on listing view
fix(contractor): certificate upload validation
```
