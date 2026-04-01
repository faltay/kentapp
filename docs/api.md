# KentApp API Dokümantasyonu

**Base URL:** `https://domain.com/api/v1`
**Auth:** Laravel Sanctum — `Authorization: Bearer {token}`
**Content-Type:** `application/json` (dosya yükleme: `multipart/form-data`)

---

## Genel Response Formatı

```json
// Başarılı
{ "success": true, "message": "...", "data": { ... } }

// Hatalı
{ "success": false, "message": "...", "errors": { "field": ["mesaj"] } }
```

**HTTP Status Kodları:**
| Kod | Açıklama |
|-----|----------|
| 200 | Başarılı |
| 201 | Oluşturuldu |
| 401 | Giriş gerekli |
| 403 | Yetkisiz |
| 404 | Bulunamadı |
| 422 | Validation hatası veya iş kuralı ihlali |
| 500 | Sunucu hatası |

---

## Kullanıcı Tipleri

| `type` | Açıklama | İlan Oluşturabilir | İlan Açabilir (kontör) |
|--------|----------|-------------------|------------------------|
| `land_owner` | Arsa sahibi | ✅ | ❌ |
| `contractor` | Müteahhit | ❌ | ✅ |
| `agent` | Emlak danışmanı | ✅ | ✅ |

---

## 1. AUTH

### POST `/auth/register`
Yeni kullanıcı kaydı. Kullanıcı tipine göre profil otomatik oluşturulur.

**Public — Auth gerekmez**

**Request:**
```json
{
  "name": "Ahmet Yılmaz",
  "email": "ahmet@example.com",
  "phone": "+905321234567",
  "password": "secret123",
  "password_confirmation": "secret123",
  "type": "contractor",

  // Sadece contractor ve agent için (opsiyonel):
  "company_name": "Yıldız İnşaat A.Ş.",
  "authorized_name": "Ahmet Yılmaz",
  "company_phone": "+902125554444",
  "company_email": "info@yildizinsaat.com"
}
```

`type` değerleri: `land_owner` | `contractor` | `agent`

**Response (201):**
```json
{
  "success": true,
  "message": "Kayıt başarılı.",
  "data": {
    "token": "1|abc123...",
    "user": {
      "id": 5,
      "name": "Ahmet Yılmaz",
      "email": "ahmet@example.com",
      "phone": "+905321234567",
      "type": "contractor",
      "profile": {
        "company_name": "Yıldız İnşaat A.Ş.",
        "credit_balance": 0,
        "certificate_status": "none",
        "working_neighborhoods": []
      }
    }
  }
}
```

**Notlar:**
- `contractor` ve `agent` için `certificate_status` başlangıçta `none` olur
- `credit_balance` başlangıçta `0`
- Token direkt kullanıma hazır

---

### POST `/auth/login`
**Public — Auth gerekmez**

**Request:**
```json
{ "email": "ahmet@example.com", "password": "secret123" }
```

**Response (200):**
```json
{
  "data": {
    "token": "2|xyz789...",
    "user": { /* formatUser() yapısı */ }
  }
}
```

**Hata durumları:**
- `401` — E-posta veya şifre hatalı
- `403` — Hesap askıya alınmış

---

### POST `/auth/logout`
**Auth gerekir**

Mevcut `mobile` tokenı iptal eder.

**Response (200):** `{ "message": "Çıkış yapıldı." }`

---

### GET `/auth/me`
**Auth gerekir**

Giriş yapmış kullanıcının bilgilerini döner.

**Response (200):**
```json
{
  "data": {
    "user": {
      "id": 5,
      "name": "Ahmet Yılmaz",
      "email": "ahmet@example.com",
      "phone": "+905321234567",
      "type": "contractor",
      "profile": {
        "company_name": "Yıldız İnşaat A.Ş.",
        "authorized_name": "Ahmet Yılmaz",
        "company_phone": "+902125554444",
        "company_email": "info@yildizinsaat.com",
        "company_address": "Kadıköy, İstanbul",
        "credit_balance": 15,
        "certificate_status": "approved",
        "certificate_number": "KD-2019-04821",
        "working_neighborhoods": [
          { "province": "İstanbul", "district": "Kadıköy", "neighborhood": "Fenerbahçe" }
        ]
      }
    }
  }
}
```

`land_owner` için `profile` alanı `null` döner.

---

### PUT `/auth/me`
**Auth gerekir**

Ad ve telefon güncelleme.

**Request:** `{ "name": "Ahmet Kaya", "phone": "+905329999999" }`

**Response (200):** `{ "data": { "user": { ... } } }`

---

### PUT `/auth/me/password`
**Auth gerekir**

**Request:**
```json
{
  "current_password": "eskisifre",
  "password": "yenisifre123",
  "password_confirmation": "yenisifre123"
}
```

---

### POST `/auth/me/avatar`
**Auth gerekir** | `multipart/form-data`

**Request:** `avatar` — image, max 2MB

**Response (200):** `{ "data": { "avatar_url": "https://..." } }`

---

## 2. İLANLAR (Browsing)

Browsing endpoint'leri tüm authenticated kullanıcılara açık olmakla birlikte, `unlock` sadece `contractor` ve `agent` kullanabilir.

---

### GET `/listings`
**Auth gerekir**

Aktif ilanları listeler. Her ilanda `is_unlocked` flag'i bulunur. İletişim bilgileri sadece `is_unlocked: true` olanlarda açıktır.

**Query Params:**
| Param | Tip | Açıklama |
|-------|-----|----------|
| `type` | string | `urban_renewal` veya `land` |
| `province` | string | İl adı — örn. `"İstanbul"` |
| `district` | string | İlçe adı |
| `zoning_status` | string | `residential` `commercial` `mixed` `unplanned` |
| `search` | string | Ada no, parsel no, mahalle/ilçe/il adı arar |
| `sort` | string | `newest` (varsayılan) veya `oldest` |
| `per_page` | integer | Sayfa başı kayıt (varsayılan: 15) |

**Response (200):**
```json
{
  "data": {
    "listings": [
      {
        "id": 12,
        "type": "urban_renewal",
        "province": "İstanbul",
        "district": "Kadıköy",
        "neighborhood": "Fenerbahçe",
        "ada_no": "123",
        "parcel_no": "45",
        "area_m2": "520.00",
        "is_featured": true,
        "view_count": 34,
        "expires_at": "2026-12-31T23:59:59.000000Z",
        "created_at": "2026-03-01T10:00:00.000000Z",
        "is_unlocked": false,
        "contact": { "locked": true },
        "photos": [
          { "id": 3, "url": "https://..." }
        ]
      }
    ],
    "meta": {
      "current_page": 1,
      "last_page": 8,
      "total": 112,
      "per_page": 15
    }
  }
}
```

**Notlar:**
- Sadece `status = active` ilanlar döner
- `contact.locked = true` ise kullanıcı bu ilanı henüz açmamış demektir
- `is_unlocked = true` ise `contact` alanı `phone`, `email`, `name` içerir

---

### GET `/listings/featured`
**Auth gerekir**

Vitrin ilanlar (`is_featured = true`). Sayfa başı 10 kayıt.

Response yapısı `GET /listings` ile aynıdır.

---

### GET `/listings/{id}`
**Auth gerekir**

Tekil ilan detayı. `is_unlocked` durumuna göre `contact` açık veya kilitli döner.

**Response (200):**
```json
{
  "data": {
    "listing": {
      "id": 12,
      "type": "urban_renewal",
      "province": "İstanbul",
      "district": "Kadıköy",
      "neighborhood": "Fenerbahçe",
      "address": "Bağdat Caddesi No:45",
      "ada_no": "123",
      "parcel_no": "45",
      "area_m2": "520.00",
      "floor_count": 4,
      "zoning_status": "residential",
      "taks": "0.30",
      "kaks": "1.50",
      "description": "Kentsel dönüşüme uygun arsa...",
      "is_featured": true,
      "view_count": 34,
      "expires_at": "2026-12-31T23:59:59.000000Z",
      "created_at": "2026-03-01T10:00:00.000000Z",
      "is_unlocked": true,
      "contact": {
        "locked": false,
        "name": "Mehmet Arı",
        "phone": "+905321234567",
        "email": "mehmet@example.com"
      },
      "owner": { "id": 8, "name": "Mehmet Arı", "type": "land_owner" },
      "photos": [ { "id": 3, "url": "https://..." } ],
      "documents": [ { "id": 1, "name": "tapu.pdf", "url": "https://..." } ]
    }
  }
}
```

---

### POST `/listings/{id}/unlock`
**Auth gerekir** | Sadece `contractor` ve `agent`

İlan iletişim bilgilerini açar. İlk kez açılıyorsa **1 kontör harcar**. Daha önce açıldıysa **ücretsizdir**.

**Request:** Body boş gönderilebilir `{}`

**Response (200):**
```json
{
  "message": "1 kontör harcandı, iletişim bilgileri açıldı.",
  "data": {
    "already_unlocked": false,
    "credits_spent": 1,
    "contact": {
      "locked": false,
      "name": "Mehmet Arı",
      "phone": "+905321234567",
      "email": "mehmet@example.com"
    }
  }
}
```

Eğer zaten açıksa:
```json
{
  "message": "İlan zaten açık.",
  "data": { "already_unlocked": true, "credits_spent": 0, "contact": { ... } }
}
```

**Hata (422):**
- Yetersiz bakiye: `"Yetersiz kontör bakiyesi."`
- İlan aktif değil: `"Bu ilan aktif değil."`
- `land_owner` kullanmaya çalışırsa `403`

---

## 3. BENİM İLANLARIM

`land_owner` ve `agent` tipindeki kullanıcılara ait ilan yönetimi.

---

### GET `/my/listings`
**Auth gerekir**

Kullanıcının kendi ilanları. Tüm statüsler dahil (`pending`, `active`, `passive` vb.)

**Response (200):**
```json
{
  "data": {
    "listings": [
      {
        "id": 5,
        "type": "land",
        "status": "active",
        "province": "İstanbul",
        "district": "Ümraniye",
        "neighborhood": "Alemdağ",
        "ada_no": "88",
        "parcel_no": "12",
        "area_m2": "300.00",
        "is_featured": false,
        "view_count": 7,
        "expires_at": null,
        "created_at": "2026-02-15T09:30:00.000000Z"
      }
    ],
    "meta": { "current_page": 1, "last_page": 1, "total": 3 }
  }
}
```

---

### POST `/my/listings`
**Auth gerekir** | `multipart/form-data` | Sadece `land_owner` ve `agent`

Yeni ilan oluşturur. **Status otomatik `pending` olur** (admin onayı gerekir).

**Request:**
```json
{
  "type": "urban_renewal",
  "province": "İstanbul",
  "district": "Kadıköy",
  "neighborhood": "Fenerbahçe",
  "address": "Bağdat Caddesi No:45",
  "ada_no": "123",
  "parcel_no": "45",
  "area_m2": 520,
  "floor_count": 4,
  "zoning_status": "residential",
  "taks": 0.30,
  "kaks": 1.50,
  "description": "Kentsel dönüşüme uygun...",
  "expires_at": "2026-12-31"
}
```

Dosya yüklemek için `multipart/form-data` kullan:
- `documents[]` — PDF, JPG, PNG (max 10MB, çoklu)
- `photos[]` — JPG, PNG, WebP (max 5MB, çoklu)

**Response (201):**
```json
{
  "message": "İlan oluşturuldu, onay bekliyor.",
  "data": { "listing": { "id": 15, "status": "pending", ... } }
}
```

---

### GET `/my/listings/{id}`
**Auth gerekir**

Kendi ilanının detayı (belgeler ve fotoğraflar dahil). Başkasının ilanına erişmeye çalışırsa `403`.

---

### POST `/my/listings/{id}`
**Auth gerekir** | `multipart/form-data`

> **Not:** Laravel PUT ile dosya yüklemeyi desteklemez. Bu yüzden `POST` kullan, form verisine `_method=PUT` ekle (veya body'e JSON olarak koy).

Kısmî güncelleme desteklenir (`sometimes` validasyon). Sadece gönderilen alanlar güncellenir.

**Ek alanlar (mevcut dosyaları silmek için):**
- `remove_documents[]` — silinecek media ID'leri (integer array)
- `remove_photos[]` — silinecek media ID'leri (integer array)

---

### DELETE `/my/listings/{id}`
**Auth gerekir**

İlanı ve tüm medyalarını siler.

**Response (200):** `{ "message": "İlan silindi." }`

---

### GET `/my/listings/{id}/views`
**Auth gerekir**

İlanı kimlerin açtığını listeler. Sayfa başı 20 kayıt.

**Response (200):**
```json
{
  "data": {
    "views": [
      {
        "id": 3,
        "contractor": { "id": 7, "name": "Murat Yıldız" },
        "credits_spent": 1,
        "viewed_at": "2026-03-10T14:25:00.000000Z"
      }
    ],
    "total": 7
  }
}
```

---

## 4. İSTATİSTİKLER

### GET `/my/stats`
**Auth gerekir**

Kullanıcı tipine göre özet istatistikler. `agent` her iki bloğu da alır.

**Response (200) — `land_owner`:**
```json
{
  "data": {
    "stats": {
      "as_owner": {
        "total_listings": 3,
        "active_listings": 2,
        "pending_listings": 1,
        "total_views": 120
      }
    }
  }
}
```

**Response (200) — `contractor`:**
```json
{
  "data": {
    "stats": {
      "as_contractor": {
        "credit_balance": 15,
        "unlocked_listings": 47,
        "total_credits_spent": 47
      }
    }
  }
}
```

**Response (200) — `agent` (her ikisi birden):**
```json
{
  "data": {
    "stats": {
      "as_owner": { "total_listings": 2, "active_listings": 1, "pending_listings": 1, "total_views": 15 },
      "as_contractor": { "credit_balance": 8, "unlocked_listings": 12, "total_credits_spent": 12 }
    }
  }
}
```

---

## 5. PROFİL (Contractor / Agent)

Sadece `contractor` ve `agent` için. `land_owner` bu endpoint'lere erişirse `403` döner.

---

### GET `/profile`
**Auth gerekir**

```json
{
  "data": {
    "profile": {
      "company_name": "Yıldız İnşaat A.Ş.",
      "authorized_name": "Murat Yıldız",
      "company_phone": "+902125554444",
      "company_email": "info@yildizinsaat.com",
      "company_address": "Kadıköy, İstanbul",
      "credit_balance": 15,
      "certificate_status": "approved",
      "certificate_number": "KD-2019-04821",
      "working_neighborhoods": [
        { "province": "İstanbul", "district": "Kadıköy", "neighborhood": "Fenerbahçe" },
        { "province": "İstanbul", "district": "Kadıköy", "neighborhood": "Caferağa" }
      ],
      "certificate_file": {
        "name": "yetki-belgesi.pdf",
        "url": "https://..."
      }
    }
  }
}
```

`certificate_status` değerleri: `none` | `pending` | `approved` | `rejected`

---

### PUT `/profile`
**Auth gerekir**

Kısmî güncelleme — sadece gönderilen alanlar değişir.

**Request:**
```json
{
  "company_name": "Yeni İnşaat Ltd.",
  "company_phone": "+902125556666",
  "company_address": "Beşiktaş, İstanbul"
}
```

---

### POST `/profile/certificate`
**Auth gerekir** | `multipart/form-data`

Yetki belgesi yükler. Mevcut belge silinir, yenisi yüklenir ve `certificate_status` otomatik `pending` yapılır.

**Request:** `certificate` — PDF, JPG, PNG, max 10MB

**Response (200):** `{ "message": "Yetki belgesi yüklendi, onay bekleniyor." }`

---

## 6. ÇALIŞMA BÖLGELERİ

---

### GET `/profile/neighborhoods`
**Auth gerekir** | Sadece `contractor` / `agent`

```json
{
  "data": {
    "neighborhoods": [
      { "province": "İstanbul", "district": "Kadıköy", "neighborhood": "Fenerbahçe" }
    ]
  }
}
```

---

### POST `/profile/neighborhoods`
**Auth gerekir** | Sadece `contractor` / `agent`

**10 kontör harcar.** Aynı mahalle zaten ekliyse `422` döner.

**Request:**
```json
{
  "province": "İstanbul",
  "district": "Üsküdar",
  "neighborhood": "Acıbadem"
}
```

**Response (201):**
```json
{
  "message": "Mahalle eklendi.",
  "data": {
    "neighborhoods": [ ... ],
    "credits_spent": 10
  }
}
```

**Hata (422):** `"Yeni mahalle eklemek için 10 kontör gerekiyor. Mevcut bakiye: 5"`

---

### DELETE `/profile/neighborhoods/{index}`
**Auth gerekir**

`{index}` — dizideki 0-tabanlı sıra numarası. (`GET /profile/neighborhoods` listesindeki sıraya göre)

**Response (200):** `{ "message": "Mahalle kaldırıldı." }`

---

## 7. KONTÖR

---

### GET `/credits/balance`
**Auth gerekir**

```json
{ "data": { "balance": 15 } }
```

---

### GET `/credits/transactions`
**Auth gerekir**

Sayfa başı 20 kayıt.

```json
{
  "data": {
    "transactions": [
      {
        "id": 45,
        "type": "purchase",
        "amount": 25,
        "balance_after": 40,
        "description": "25 kontör satın alındı",
        "listing": null,
        "created_at": "2026-03-10T10:00:00.000000Z"
      },
      {
        "id": 44,
        "type": "spend",
        "amount": -1,
        "balance_after": 15,
        "description": "İlan görüntüleme #12",
        "listing": { "id": 12, "province": "İstanbul", "district": "Kadıköy" },
        "created_at": "2026-03-09T14:30:00.000000Z"
      }
    ],
    "meta": { "current_page": 1, "last_page": 3, "total": 42 }
  }
}
```

`type` değerleri: `purchase` (satın alma) | `spend` (harcama) | `refund` (iade)
`amount` pozitif = eklendi, negatif = harcandı

---

### POST `/credits/purchase`
**Auth gerekir**

İyzico ödeme formu başlatır. Dönen `payment_page_url`'i WebView'da aç; ödeme tamamlanınca iyzico `/api/v1/payments/iyzico/callback`'e POST atar ve kontörler otomatik eklenir.

**Request:**
```json
{ "credit_package_id": 2 }
```

**Response (201):**
```json
{
  "message": "Ödeme başlatıldı.",
  "data": {
    "payment_id": 17,
    "token": "iyzico_token_abc123",
    "payment_page_url": "https://sandbox-api.iyzipay.com/...",
    "checkout_form_content": "<script>...</script>"
  }
}
```

**Kullanım:**
1. `payment_page_url` → WebView'da aç
2. Kullanıcı ödemeyi tamamlar
3. İyzico callback'i atar → sunucu kontörleri ekler
4. Uygulama, ödeme sonrası `GET /credits/balance` çekerek bakiyeyi günceller

---

## 8. KONTÖR PAKETLERİ

### GET `/credit-packages`
**Public — Auth gerekmez**

```json
{
  "data": {
    "packages": [
      { "id": 1, "name": "10 Kontör", "credits": 10, "price": 49.90, "currency": "TRY", "price_per_credit": 4.99 },
      { "id": 2, "name": "25 Kontör", "credits": 25, "price": 99.90, "currency": "TRY", "price_per_credit": 3.99 },
      { "id": 3, "name": "50 Kontör", "credits": 50, "price": 179.90, "currency": "TRY", "price_per_credit": 3.59 },
      { "id": 4, "name": "100 Kontör", "credits": 100, "price": 299.90, "currency": "TRY", "price_per_credit": 2.99 }
    ]
  }
}
```

---

## 9. MÜTEAHHİT / DANIŞMAN PROFİL (Public)

### GET `/contractors/{user_id}`
**Auth gerekir**

Başka bir müteahhit veya emlak danışmanının public profili.

**Response (200):**
```json
{
  "data": {
    "contractor": {
      "id": 7,
      "name": "Murat Yıldız",
      "type": "contractor",
      "company_name": "Yıldız İnşaat A.Ş.",
      "authorized_name": "Murat Yıldız",
      "company_phone": "+902125554444",
      "company_email": "info@yildizinsaat.com",
      "company_address": "Kadıköy, İstanbul",
      "certificate_status": "approved",
      "certificate_number": "KD-2019-04821",
      "certificate_file": { "url": "https://..." },
      "stats": {
        "active_listings": 3,
        "review_count": 12,
        "avg_rating": 4.3
      },
      "reviews": [
        {
          "id": 8,
          "rating": 5,
          "comment": "Çok profesyonel bir ekip...",
          "reviewer": { "id": 3, "name": "Ahmet Yılmaz" },
          "created_at": "2026-02-14T10:00:00.000000Z"
        }
      ]
    }
  }
}
```

`reviews` → son 10 onaylı yorum (tam liste için `GET /contractors/{id}/reviews`)

**Hata:** User `contractor` veya `agent` değilse `404`

---

### GET `/contractors/{user_id}/reviews`
**Auth gerekir**

Sayfalı yorum listesi.

```json
{
  "data": {
    "reviews": [ { "id": 8, "rating": 5, "comment": "...", "reviewer": {...}, "created_at": "..." } ],
    "avg_rating": 4.3,
    "meta": { "current_page": 1, "last_page": 2, "total": 12 }
  }
}
```

---

## 10. DEĞERLENDİRME

### POST `/reviews`
**Auth gerekir**

Bir müteahhit veya emlak danışmanı için yorum gönderir.

**Kurallar:**
- Aynı kullanıcıyı ikinci kez değerlendiremezsin (`422`)
- Yorum `status: pending` olarak kaydedilir, admin onaylayana kadar görünmez

**Request:**
```json
{
  "reviewed_id": 7,
  "listing_id": 12,
  "rating": 5,
  "comment": "Çok profesyonel bir ekip, zamanında teslim ettiler."
}
```

`listing_id` opsiyonel. `comment` opsiyonel, sadece `rating` zorunlu.

**Response (201):**
```json
{
  "message": "Değerlendirme gönderildi, onay bekleniyor.",
  "data": { "review": { "id": 15, "status": "pending" } }
}
```

---

## 11. KONUM HİYERARŞİSİ

### GET `/locations/provinces`
**Public**

```json
{ "data": { "provinces": [ { "id": 1, "name": "Adana", "code": "01" } ] } }
```

---

### GET `/locations/districts?province_id={id}`
**Public**

```json
{ "data": { "districts": [ { "id": 34, "province_id": 1, "name": "Seyhan" } ] } }
```

---

### GET `/locations/neighborhoods?district_id={id}`
**Public**

```json
{ "data": { "neighborhoods": [ { "id": 101, "district_id": 34, "name": "Çakmak" } ] } }
```

---

## 12. ÖDEME CALLBACK

### POST `/payments/iyzico/callback`
**CSRF muaf** — İyzico tarafından sunucuya POST atılır, mobil uygulama bu endpoint'i çağırmaz.

Ödeme başarılıysa:
- `payments` kaydı `succeeded` yapılır
- Kullanıcının kontör bakiyesi artırılır (`credit_transactions` kaydı oluşur)

---

## İş Akışları

### Kayıt ve Giriş
```
POST /auth/register → token al
GET /auth/me       → kullanıcı bilgilerini yükle
```

### Contractor: İlan Açma
```
GET /listings                    → liste görüntüle (contact: { locked: true })
GET /listings/{id}               → detay (is_unlocked: false)
POST /listings/{id}/unlock       → 1 kontör harcar, contact bilgisi döner
GET /listings/{id}               → tekrar bakınca ücretsiz (is_unlocked: true)
```

### Contractor: Kontör Satın Alma
```
GET /credit-packages              → paket listesi
POST /credits/purchase            → payment_page_url döner
[WebView: ödeme tamamlanır]
GET /credits/balance              → güncel bakiyeyi çek
```

### Land Owner: İlan Oluşturma
```
GET /locations/provinces          → il listesi
GET /locations/districts?province_id=34  → ilçe listesi
POST /my/listings (multipart)     → ilan oluştur (status: pending)
GET /my/stats                     → istatistikleri güncelle
```

### Contractor: Mahalle Ekleme
```
GET /credits/balance              → bakiye 10+ mı kontrol et
POST /profile/neighborhoods       → 10 kontör harcar, mahalle eklenir
```

---

## Sık Yapılan Hatalar

| Hata | Çözüm |
|------|-------|
| `401 Unauthenticated` | Token eksik veya geçersiz. Yeniden login. |
| `403` unlock yaparken | `land_owner` unlock yapamaz, sadece `contractor` / `agent` |
| `422` unlock yaparken | Kontör bakiyesi 0 — önce satın al |
| `422` mahalle eklerken | Bakiye < 10 veya mahalle zaten ekli |
| `422` yorum gönderirken | Aynı kişiyi daha önce değerlendirdin |
| Dosya yükleme çalışmıyor | `Content-Type: multipart/form-data` kullan, `application/json` değil |
| PUT ile dosya yükleme | `POST` kullan + `_method=PUT` parametresi ekle |
