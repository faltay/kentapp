# Ertelenmiş Kararlar

## 1. Çok Restoranlı Owner — Panel Görünümü

**Konu:** Birden fazla restorana sahip bir owner panele girdiğinde ne görmeli?

**Şu anki durum:**
- Panel her zaman tek bir restoranın bağlamında çalışıyor (session tabanlı)
- `session('current_restaurant_id')` hangi restoranın datasının gösterileceğini belirliyor
- Tüm sorgular tek `restaurant_id`'ye filtreli

**Karar verilecek seçenekler:**

### A) Tek Restoran Bağlamı (mevcut tasarım)
- Panel bir restoranın bağlamında çalışır, üst menüden değiştirilir
- Shopify, Square gibi platformların yaklaşımı
- Basit, net, veri karışmaz

### B) Çok Restoran Genel Görünümü
- Dashboard'da tüm restoranların özetini göster (toplam sipariş, menü, masa vb.)
- Ürün ekleme/düzenleme gibi işlemlerde yine tek restoran bağlamı gerekir
- Daha karmaşık ama daha kapsamlı bir deneyim

**İlgili dosyalar:**
- `app/Models/User.php` → `currentRestaurant()`, `ownedRestaurants()`
- `app/Http/Middleware/RestaurantAccess.php`
- `app/Http/Controllers/Restaurant/DashboardController.php` → `switchRestaurant()`
