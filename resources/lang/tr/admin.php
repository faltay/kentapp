<?php

return [

    // ─── admin/impersonate ──────────────────────────────────────────────────────
    'impersonate' => [
        'login_as'      => 'Olarak Giriş Yap',
        'stop'          => 'Oturumu Sonlandır',
        'banner'        => ':name olarak giriş yapıldı. (Yönetici: :admin)',
        'cannot_self'   => 'Kendiniz olarak giriş yapamazsınız.',
        'cannot_admin'  => 'Süper yönetici olarak giriş yapılamaz.',
    ],

    // ─── admin/dashboard.php ──────────────────────────────────────────────────
    'dashboard' => [
        'recent_listings'      => 'Son İlanlar',
        'recent_transactions'  => 'Son Kontör İşlemleri',
        'awaiting_approval'    => 'Onay bekliyor',
        'awaiting_moderation'  => 'Moderasyon bekliyor',
        'no_pending'           => 'Bekleyen yok',
        'no_listings_yet'      => 'Henüz ilan yok.',
        'no_transactions_yet'  => 'Henüz işlem yok.',
        'total_users'          => 'Toplam Kullanıcı',
        'total_restaurants'    => 'Toplam Restoran',
        'active_subscriptions' => 'Aktif Abonelikler',
        'total_revenue'        => 'Toplam Gelir',
        'paid_plans'           => 'Ücretli planlar',

        'monthly_revenue'      => 'Aylık Gelir',
        'revenue'              => 'Gelir',

        'plan_distribution'    => 'Plan Dağılımı',

        'recent_payments'      => 'Son Ödemeler',
        'recent_restaurants'   => 'Son Restoranlar',

        'restaurant'           => 'Restoran',
        'amount'               => 'Tutar',
        'date'                 => 'Tarih',
        'plan'                 => 'Plan',

        'no_data'              => 'Henüz veri yok.',

        'active_subscriptions_label' => 'Aktif abonelik',
        'subscription_rate'  => 'Abonelik oranı',
        'registered_users'   => 'Kayıtlı kullanıcı',
        'total_collected'    => 'Toplam tahsilat',
        'monthly'            => 'Aylık',
        'yearly'             => 'Yıllık',
        'active_subs_chart'  => 'Aktif abonelikler',
        'popular_features'   => 'Popüler Özellikler',
        'usage_rate'         => 'Restoranlarda kullanım oranı',
        'qr_menu'            => 'QR Menü',
        'restaurants'        => 'restoran',
        'table_management'   => 'Masa Yönetimi',
        'tables_qr_codes'    => 'Masalar & QR kodlar',
        'ai_menu'            => 'AI Menü',
        'auto_content'       => 'Otomatik içerik oluşturma',
        'subscription_mgmt'  => 'Abonelik Yönetimi',
        'total'              => 'Toplam',
    ],

    // ─── admin/users.php ──────────────────────────────────────────────────────
    'users' => [
        'title'                => 'Kullanıcılar',
        'create'               => 'Kullanıcı Oluştur',
        'edit'                 => 'Kullanıcı Düzenle',
        'created_successfully' => 'Kullanıcı başarıyla oluşturuldu.',
        'updated_successfully' => 'Kullanıcı başarıyla güncellendi.',
        'deleted_successfully' => 'Kullanıcı başarıyla silindi.',
        'creation_failed'      => 'Kullanıcı oluşturulamadı.',
        'update_failed'        => 'Kullanıcı güncellenemedi.',
        'deletion_failed'      => 'Kullanıcı silinemedi.',
        'confirm_delete'       => 'Bu kullanıcıyı silmek istediğinizden emin misiniz?',

        'suspended'            => 'Askıya Alındı',

        'form' => [
            'user_info'          => 'Kullanıcı Bilgileri',
            'name'               => 'Ad Soyad',
            'email'              => 'E-posta Adresi',
            'password'           => 'Şifre',
            'password_hint'      => 'Mevcut şifreyi korumak için boş bırakın.',
            'role'               => 'Rol',
            'type'               => 'Hesap Türü',
            'type_land_owner'    => 'Arsa Sahibi',
            'type_contractor'    => 'Müteahhit',
            'type_agent'         => 'Emlak Danışmanı',
            'status'             => 'Durum',
            'phone'              => 'Telefon',
            'is_active'          => 'Aktif',
            'is_suspended'       => 'Kullanıcıyı Askıya Al',
            'is_suspended_hint'  => 'Askıya alınan kullanıcılar panele erişemez.',
        ],

        'roles' => [
            'super_admin'         => 'Süper Yönetici',
            'verified_contractor' => 'Onaylı Müteahhit',
            'land_owner'          => 'Arsa Sahibi',
        ],

        'validation' => [
            'email_unique' => 'Bu e-posta adresi zaten kullanılıyor.',
        ],
    ],

    // ─── admin/agents.php ─────────────────────────────────────────────────────
    'agents' => [
        'title'                => 'Emlak Danışmanları',
        'create'               => 'Yeni Danışman',
        'edit'                 => 'Danışman Düzenle',
        'created_successfully' => 'Emlak danışmanı başarıyla oluşturuldu.',
        'creation_failed'      => 'Emlak danışmanı oluşturulamadı.',
        'updated_successfully' => 'Emlak danışmanı başarıyla güncellendi.',
        'update_failed'        => 'Emlak danışmanı güncellenemedi.',
        'deleted_successfully' => 'Emlak danışmanı başarıyla silindi.',
        'deletion_failed'      => 'Emlak danışmanı silinemedi.',
        'confirm_delete'       => 'Bu emlak danışmanını silmek istediğinizden emin misiniz?',
        'no_profile'           => 'Profil bilgisi bulunamadı.',
        'credit_balance'       => 'Kontör Bakiyesi',
        'recent_views'         => 'Son Görüntülenen İlanlar',

        'form' => [
            'company_info'             => 'Firma Bilgileri',
            'company_name'             => 'Firma Adı',
            'authorized_name'          => 'Yetkili Kişi',
            'company_phone'            => 'Firma Telefonu',
            'company_email'            => 'Firma E-postası',
            'company_address'          => 'Firma Adresi',
            'working_neighborhoods'    => 'Çalışma Bölgeleri',
            'no_areas'                 => 'Henüz çalışma bölgesi eklenmedi.',
            'area_placeholder'         => 'İl, ilçe veya mahalle yazın...',
            'area_hint'                => 'Yazdıkça öneriler çıkar. Listede yoksa Enter ile ekleyebilirsiniz.',
            'area_add_custom'          => 'Ekle',
            'area_no_results'          => 'Sonuç bulunamadı',
            'certificate_status'       => 'Belge Durumu',
            'certificate_number'       => 'Belge Numarası',
            'certificate_file'         => 'Yetki Belgesi',
            'certificate_drop'         => 'Dosyayı buraya sürükleyin veya tıklayın',
            'certificate_hint'         => 'PDF, JPG veya PNG — maks. 10 MB',
        ],

        'certificate' => [
            'none'     => 'Belge Yok',
            'pending'  => 'Beklemede',
            'approved' => 'Onaylı',
            'rejected' => 'Reddedildi',
        ],
    ],

    // ─── admin/land_owners.php ────────────────────────────────────────────────
    'land_owners' => [
        'title'                => 'Arsa Sahipleri',
        'create'               => 'Yeni Arsa Sahibi',
        'edit'                 => 'Arsa Sahibi Düzenle',
        'created_successfully' => 'Arsa sahibi başarıyla oluşturuldu.',
        'creation_failed'      => 'Arsa sahibi oluşturulamadı.',
        'updated_successfully' => 'Arsa sahibi başarıyla güncellendi.',
        'update_failed'        => 'Arsa sahibi güncellenemedi.',
        'deleted_successfully' => 'Arsa sahibi başarıyla silindi.',
        'deletion_failed'      => 'Arsa sahibi silinemedi.',
        'confirm_delete'       => 'Bu arsa sahibini silmek istediğinizden emin misiniz?',
        'listing_count'        => 'İlan',

        'form' => [
            'tc_number' => 'TC Kimlik No',
        ],
    ],

    // ─── admin/contractors.php ────────────────────────────────────────────────
    'contractors' => [
        'title'                => 'Müteahhitler',
        'create'               => 'Yeni Müteahhit',
        'edit'                 => 'Müteahhit Düzenle',
        'created_successfully' => 'Müteahhit başarıyla oluşturuldu.',
        'creation_failed'      => 'Müteahhit oluşturulamadı.',
        'updated_successfully' => 'Müteahhit başarıyla güncellendi.',
        'update_failed'        => 'Müteahhit güncellenemedi.',
        'deleted_successfully' => 'Müteahhit başarıyla silindi.',
        'deletion_failed'      => 'Müteahhit silinemedi.',
        'confirm_delete'       => 'Bu müteahhiti silmek istediğinizden emin misiniz?',
        'no_profile'           => 'Profil bilgisi bulunamadı.',
        'credit_balance'       => 'Kontör Bakiyesi',
        'recent_views'         => 'Son Görüntülenen İlanlar',

        'form' => [
            'company_info'        => 'Firma Bilgileri',
            'company_name'        => 'Firma Adı',
            'authorized_name'     => 'Yetkili Kişi',
            'company_phone'       => 'Firma Telefonu',
            'company_email'       => 'Firma E-postası',
            'company_address'          => 'Firma Adresi',
            'working_neighborhoods'    => 'Çalışma Bölgeleri',
            'no_areas'                 => 'Henüz çalışma bölgesi eklenmedi.',
            'area_placeholder'         => 'İl, ilçe veya mahalle yazın...',
            'area_hint'                => 'Yazdıkça öneriler çıkar. Listede yoksa Enter ile ekleyebilirsiniz.',
            'area_add_custom'          => 'Ekle',
            'area_no_results'          => 'Sonuç bulunamadı',
            'certificate_status'  => 'Belge Durumu',
            'certificate_number'  => 'Belge Numarası',
            'certificate_file'    => 'Yetki Belgesi',
            'certificate_drop'    => 'Dosyayı buraya sürükleyin veya tıklayın',
            'certificate_hint'    => 'PDF, JPG veya PNG — maks. 10 MB',
        ],

        'certificate' => [
            'none'     => 'Belge Yok',
            'pending'  => 'Beklemede',
            'approved' => 'Onaylı',
            'rejected' => 'Reddedildi',
        ],
    ],

    // ─── admin/restaurants.php ────────────────────────────────────────────────
    'restaurants' => [
        'title'                => 'Restoranlar',
        'create'               => 'Yeni Restoran',
        'show'                 => 'Restoran Detayı',
        'edit'                 => 'Restoran Düzenle',
        'created_successfully' => 'Restoran başarıyla oluşturuldu.',
        'creation_failed'      => 'Restoran oluşturulamadı.',
        'updated_successfully' => 'Restoran başarıyla güncellendi.',
        'update_failed'        => 'Restoran güncellenemedi.',
        'deleted_successfully' => 'Restoran başarıyla silindi.',
        'deletion_failed'      => 'Restoran silinemedi.',
        'confirm_delete'       => 'Bu restoranı silmek istediğinizden emin misiniz? Tüm veriler kalıcı olarak silinecektir.',
        'suspended'            => 'Askıya Alındı',
        'no_plan'              => 'Plan Yok',

        'form' => [
            'owner'                   => 'Sahip',
            'owner_section'              => 'Sahip Hesabı',
            'select_owner'               => 'Sahip Hesabı',
            'select_owner_placeholder'   => 'Sahip ara...',
            'owner_must_be_owner'        => 'Seçilen kullanıcı bir sahip hesabı olmalıdır.',
            'owner_name'                 => 'Ad Soyad',
            'owner_email'                => 'E-posta Adresi',
            'owner_password'             => 'Şifre',
            'owner_password_confirm'     => 'Şifre Tekrar',
            'restaurant_section'      => 'Restoran Bilgileri',
            'contact_section'         => 'İletişim & Konum',
            'name'                    => 'Ad',
            'description'             => 'Açıklama',
            'email'                   => 'Restoran E-postası',
            'phone'                   => 'Telefon',
            'address'                 => 'Adres',
            'city'                    => 'Şehir',
            'country'                 => 'Ülke',
            'currency'                => 'Para Birimi',
            'timezone'                => 'Saat Dilimi',
            'subscription_plan'       => 'Abonelik Planı',
            'no_plan'                 => '— Plan Yok (Ücretsiz) —',
            'is_active'               => 'Aktif',
            'is_suspended'            => 'Askıya Al',
            'is_suspended_hint'       => 'Askıya alınan restoranlar, restoran sahipleri tarafından erişilemez.',
            'languages'               => 'Menü Dilleri',
            'languages_hint'          => 'Genel menüde sunulacak diller. Menünün gösterileceği tüm dilleri seçin.',
            'languages_limit'         => 'Planınız en fazla :max dil seçimine izin veriyor.',
        ],

        'table' => [
            'restaurant' => 'Restoran',
            'owner'      => 'Sahip',
            'plan'       => 'Plan',
        ],
    ],

    // ─── admin/listings.php ───────────────────────────────────────────────────
    'listings' => [
        'title'   => 'İlanlar',
        'create'  => 'İlan Oluştur',
        'edit'    => 'İlan Düzenle',
        'show'    => 'İlan Detayı',
        'approve' => 'Onayla',
        'reject'  => 'Reddet',
        'passive' => 'Pasif Et',
        'set_featured'    => 'Vitrine Al',
        'remove_featured' => 'Vitrinden Çıkar',
        'featured'        => 'Vitrin',
        'owner_info'      => 'İlan Sahibi',
        'stats'           => 'İstatistikler',
        'total_views'     => 'Görüntülenme',
        'total_reviews'   => 'Değerlendirme',

        'type'             => 'İlan Türü',
        'type_urban_renewal' => 'Kentsel Dönüşüm',
        'type_land'          => 'Arsa',

        'status_pending'  => 'Beklemede',
        'status_active'   => 'Aktif',
        'status_rejected' => 'Reddedildi',
        'status_passive'  => 'Pasif',
        'status_draft'    => 'Taslak',

        'zoning_residential' => 'Konut',
        'zoning_commercial'  => 'Ticari',
        'zoning_mixed'       => 'Karma',
        'zoning_unplanned'   => 'İmarsız',

        'agreement_kat_karsiligi'      => 'Kat Karşılığı',
        'agreement_para_karsiligi'     => 'Para Karşılığı',
        'agreement_karma_para_kat'     => 'Karma (Para + Kat)',
        'agreement_hasilat_paylasimli' => 'Hasılat Paylaşımlı',
        'agreement_yap_islet_devret'   => 'Yap-İşlet-Devret',
        'agreement_kismi_satis_kat'    => 'Kısmi Satış + Kat',

        'created_successfully'   => 'İlan başarıyla oluşturuldu.',
        'updated_successfully'   => 'İlan başarıyla güncellendi.',
        'approved_successfully'  => 'İlan onaylandı.',
        'rejected_successfully'  => 'İlan reddedildi.',
        'passived_successfully'  => 'İlan pasif edildi.',
        'deleted_successfully'   => 'İlan silindi.',
        'featured_enabled'       => 'İlan vitrine alındı.',
        'featured_disabled'      => 'İlan vitrinden çıkarıldı.',
        'creation_failed'        => 'İlan oluşturulamadı.',
        'update_failed'          => 'İlan güncellenemedi.',
        'approval_failed'        => 'İlan onaylanamadı.',
        'rejection_failed'       => 'İlan reddedilemedi.',
        'passive_failed'         => 'İlan pasif edilemedi.',
        'deletion_failed'        => 'İlan silinemedi.',
        'featured_failed'        => 'Vitrin durumu değiştirilemedi.',

        'confirm_approve' => 'Bu ilanı onaylamak istiyor musunuz?',
        'confirm_reject'  => 'Bu ilanı reddetmek istiyor musunuz?',
        'confirm_passive' => 'Bu ilanı pasif etmek istiyor musunuz?',

        'table' => [
            'owner'    => 'İlan Sahibi',
            'location' => 'Konum',
            'type'     => 'Tür',
        ],

        'form' => [
            'listing_info'   => 'İlan Bilgileri',
            'owner'          => 'İlan Sahibi',
            'owner_placeholder' => 'Arsa sahibi veya emlak danışmanı seçin...',
            'type'           => 'İlan Türü',
            'province'       => 'İl',
            'district'       => 'İlçe',
            'neighborhood'   => 'Mahalle',
            'address'        => 'Adres',
            'ada_no'         => 'Ada No',
            'parcel_no'      => 'Parsel No',
            'pafta'          => 'Pafta',
            'gabari'         => 'Gabari',
            'agreement_model' => 'Anlaşma Modeli',
            'area_m2'        => 'Alan (m²)',
            'floor_count'    => 'Kat Adedi',
            'zoning_status'  => 'İmar Durumu',
            'taks'           => 'TAKS',
            'kaks'           => 'KAKS',
            'description'    => 'Açıklama',
            'is_featured'    => 'Vitrin İlan',
            'is_featured_hint' => 'Müteahhit ana sayfasında öne çıkar.',
            'view_count'     => 'Görüntülenme',
            'expires_at'     => 'Bitiş Tarihi',
            'documents'            => 'Tapu Belgesi',
            'documents_drop'       => 'PDF, JPG veya PNG dosyalarını sürükleyip bırakın',
            'documents_hint'       => 'veya seçmek için tıklayın — maks. 10 MB',
            'photos'               => 'Fotoğraflar',
            'photos_drop'          => 'Fotoğrafları sürükleyip bırakın',
            'photos_hint'          => 'veya seçmek için tıklayın — JPG, PNG, WebP — maks. 5 MB',
            'photos_existing_hint' => 'Silmek istediğiniz fotoğrafları işaretleyin.',
            'add_more'             => 'Dosya Ekle',
            'select_district'      => 'Önce il seçin',
            'select_neighborhood'  => 'Önce ilçe seçin',
        ],
    ],

    // ─── admin/contractor_certificates.php ────────────────────────────────────
    'contractor_certificates' => [
        'title'   => 'Yetki Belgeleri',
        'approve' => 'Onayla',
        'reject'  => 'Reddet',
        'approve_title' => 'Belge Onaylama',
        'certificate_number' => 'Belge Numarası',
        'certificate_number_placeholder' => 'Yetki belgesi numarasını girin...',

        'status_none'     => 'Belge Yok',
        'status_pending'  => 'Beklemede',
        'status_approved' => 'Onaylı',
        'status_rejected' => 'Reddedildi',

        'approved_successfully' => 'Yetki belgesi onaylandı.',
        'rejected_successfully' => 'Yetki belgesi reddedildi.',
        'approval_failed'       => 'Onaylama işlemi başarısız.',
        'rejection_failed'      => 'Reddetme işlemi başarısız.',

        'confirm_reject' => 'Bu yetki belgesini reddetmek istiyor musunuz?',

        'table' => [
            'contractor' => 'Müteahhit',
            'email'      => 'E-posta',
            'company'    => 'Firma',
        ],
    ],

    // ─── admin/credit_packages.php ────────────────────────────────────────────
    'credit_packages' => [
        'title'  => 'Kontör Paketleri',
        'create' => 'Yeni Paket',
        'edit'   => 'Paket Düzenle',

        'created_successfully' => 'Kontör paketi oluşturuldu.',
        'updated_successfully' => 'Kontör paketi güncellendi.',
        'deleted_successfully' => 'Kontör paketi silindi.',
        'creation_failed'      => 'Kontör paketi oluşturulamadı.',
        'update_failed'        => 'Kontör paketi güncellenemedi.',
        'deletion_failed'      => 'Kontör paketi silinemedi.',
        'confirm_delete'       => 'Bu kontör paketini silmek istediğinizden emin misiniz?',

        'table' => [
            'name'    => 'Paket Adı',
            'credits' => 'Kontör',
            'price'   => 'Fiyat',
        ],

        'form' => [
            'package_info' => 'Paket Bilgileri',
            'name'         => 'Paket Adı',
            'name_placeholder' => 'örn. 10 Kontör, 50 Kontör...',
            'credits'      => 'Kontör Miktarı',
            'price'        => 'Fiyat',
            'currency'     => 'Para Birimi',
            'sort_order'   => 'Sıralama',
            'is_active'    => 'Aktif',
        ],
    ],

    // ─── admin/credit_transactions.php ────────────────────────────────────────
    'credit_transactions' => [
        'title'  => 'Kontör İşlemleri',
        'assign' => 'Kontör Ekle',
        'type'   => 'İşlem Türü',

        'assigned_successfully' => 'Kontör başarıyla eklendi.',
        'assignment_failed'     => 'Kontör eklenemedi.',

        'type_purchase' => 'Satın Alma',
        'type_spend'    => 'Harcama',
        'type_refund'   => 'İade',

        'table' => [
            'contractor'    => 'Kullanıcı',
            'listing'       => 'İlan',
            'type'          => 'Tür',
            'amount'        => 'Miktar',
            'balance_after' => 'Sonraki Bakiye',
            'description'   => 'Açıklama',
        ],

        'form' => [
            'user'                   => 'Kullanıcı',
            'user_placeholder'       => 'Müteahhit veya danışman ara...',
            'user_no_results'        => 'Kullanıcı bulunamadı',
            'type'                   => 'İşlem Türü',
            'amount'                 => 'Kontör Miktarı',
            'current_balance'        => 'Mevcut Bakiye',
            'description_placeholder' => 'İşlem açıklaması (opsiyonel)',
        ],

        'assign_btn' => 'Kontör Ekle',
    ],

    // ─── admin/reviews.php ────────────────────────────────────────────────────
    'reviews' => [
        'title'   => 'Değerlendirmeler',
        'approve' => 'Onayla',
        'reject'  => 'Reddet',

        'status_pending'  => 'Beklemede',
        'status_approved' => 'Onaylı',
        'status_rejected' => 'Reddedildi',

        'approved_successfully' => 'Değerlendirme onaylandı.',
        'rejected_successfully' => 'Değerlendirme reddedildi.',
        'deleted_successfully'  => 'Değerlendirme silindi.',
        'approval_failed'       => 'Onaylama işlemi başarısız.',
        'rejection_failed'      => 'Reddetme işlemi başarısız.',
        'deletion_failed'       => 'Silme işlemi başarısız.',

        'table' => [
            'reviewer' => 'Yorum Yapan',
            'reviewed' => 'Değerlendirilen',
            'rating'   => 'Puan',
            'comment'  => 'Yorum',
        ],
    ],

    // ─── admin/plans.php ──────────────────────────────────────────────────────
    'plans' => [
        'title'                => 'Abonelik Planları',
        'create'               => 'Plan Oluştur',
        'edit'                 => 'Plan Düzenle',
        'created_successfully' => 'Plan başarıyla oluşturuldu.',
        'updated_successfully' => 'Plan başarıyla güncellendi.',
        'deleted_successfully' => 'Plan başarıyla silindi.',
        'creation_failed'      => 'Plan oluşturulamadı.',
        'update_failed'        => 'Plan güncellenemedi.',
        'deletion_failed'      => 'Plan silinemedi.',
        'confirm_delete'       => 'Bu planı silmek istediğinizden emin misiniz?',
        'free'                 => 'Ücretsiz',

        'form' => [
            'name'              => 'Plan Adı',
            'slug'              => 'Slug',
            'slug_hint'         => 'URL dostu tanımlayıcı (örn. ucretsiz, standart, pro)',
            'description'       => 'Açıklama',
            'currency'          => 'Para Birimi',
            'price_monthly'     => 'Aylık',
            'price_yearly_short' => 'Yıllık',
            'add_currency'      => 'Para Birimi Ekle',
            'prices_hint'       => 'Her para birimi için fiyat belirleyin. Ücretsiz planlar için boş bırakın.',
            'limits'            => 'Plan Limitleri',
            'max_restaurants' => 'Maksimum Restoran',
            'max_branches'    => 'Maksimum Şube',
            'max_menu_items'  => 'Maksimum Menü Ürünü',
            'max_tables'      => 'Maksimum Masa',
            'max_languages'   => 'Maksimum Dil',
            'unlimited_hint'  => 'Sınırsız için -1 girin.',
            'features'        => 'Özellikler',
            'features_hint'   => 'Fiyatlandırma sayfasında gösterilecek özellikler.',
            'add_feature'        => 'Özellik Ekle',
            'feature_placeholder' => 'örn. QR Kod Oluşturma',
            'is_active'       => 'Aktif',
            'is_featured'     => 'Öne Çıkan (fiyatlandırma sayfasında vurgula)',
            'sort_order'      => 'Sıralama',
        ],

        'table' => [
            'plan'        => 'Plan',
            'price'       => 'Ücret',
            'limits'      => 'Limitler',
            'restaurants' => 'Restoran',
            'branches'    => 'Şube',
            'menu_items'  => 'Menü Ürünü',
            'tables'      => 'Masa',
            'languages'   => 'Dil',
            'unlimited'   => 'Sınırsız',
            'month'       => 'ay',
            'year'        => 'yıl',
        ],

        'validation' => [
            'slug_unique' => 'Bu slug zaten kullanılıyor.',
        ],
    ],

    // ─── admin/blog_categories.php ──────────────────────────────────────────────
    'blog_categories' => [
        'title'    => 'Blog Kategorileri',
        'subtitle' => 'Blog yazı kategorilerini yönetin.',

        'create'     => 'Yeni Kategori',
        'edit'       => 'Kategori Düzenle',
        'delete'     => 'Kategori Sil',
        'activate'   => 'Aktif Et',
        'deactivate' => 'Pasif Et',

        'form' => [
            'info_section'     => 'Kategori Bilgileri',
            'slug_section'     => 'SEO & URL',
            'settings_section' => 'Ayarlar',
            'name'             => 'Ad',
            'description'      => 'Açıklama',
            'slug'                  => 'Slug (URL)',
            'slug_hint'             => 'Boş bırakılırsa addan otomatik oluşturulur.',
            'meta_description'      => 'Meta Açıklama',
            'meta_description_hint' => 'Arama motorları için SEO açıklaması (maks. 160 karakter).',
            'sort_order'            => 'Sıralama',
            'is_active'             => 'Aktif',
        ],

        'table' => [
            'id'           => '#',
            'name'         => 'Ad',
            'translations' => 'Diller',
            'slug'         => 'Slug',
            'posts_count'  => 'Yazılar',
            'sort_order'   => 'Sıra',
            'status'       => 'Durum',
            'actions'      => 'İşlemler',
        ],

        'created_successfully'     => 'Kategori başarıyla oluşturuldu.',
        'updated_successfully'     => 'Kategori başarıyla güncellendi.',
        'deleted_successfully'     => 'Kategori başarıyla silindi.',
        'activated_successfully'   => 'Kategori aktif edildi.',
        'deactivated_successfully' => 'Kategori pasif edildi.',
        'reordered_successfully'   => 'Kategoriler yeniden sıralandı.',
        'creation_failed'          => 'Kategori oluşturulamadı.',
        'update_failed'            => 'Kategori güncellenemedi.',
        'deletion_failed'          => 'Kategori silinemedi.',
        'reorder_failed'           => 'Kategoriler yeniden sıralanamadı.',
        'confirm_delete'           => 'Bu kategoriyi silmek istediğinizden emin misiniz? Bu kategorideki yazılar kategorisiz kalacaktır.',
    ],

    // ─── admin/posts.php ──────────────────────────────────────────────────────
    'posts' => [
        'title'    => 'Blog Yazıları',
        'subtitle' => 'Blog yazılarını ve makaleleri yönetin.',

        'create'    => 'Yeni Yazı',
        'edit'      => 'Yazıyı Düzenle',
        'delete'    => 'Yazıyı Sil',
        'publish'   => 'Yayınla',
        'unpublish' => 'Taslağa Al',

        'status_published' => 'Yayında',
        'status_draft'     => 'Taslak',

        'form' => [
            'content_section' => 'Yazı İçeriği',
            'seo_section'     => 'SEO & URL',
            'publish_section' => 'Yayın Ayarları',
            'title'           => 'Başlık',
            'title_hint'      => 'Her dil için yazı başlığını girin.',
            'slug'            => 'Slug (URL)',
            'slug_hint'       => 'Boş bırakılırsa başlıktan otomatik oluşturulur.',
            'excerpt'         => 'Özet',
            'excerpt_hint'    => 'Listelerde gösterilen kısa açıklama (maks. 500 karakter).',
            'content'         => 'İçerik',
            'category'        => 'Kategori',
            'no_category'     => '— Kategori Yok —',
            'meta_description'      => 'Meta Açıklama',
            'meta_description_hint' => 'Arama motorları için SEO açıklaması (maks. 160 karakter).',
            'image_section'         => 'Blog Görseli',
            'image_drop'            => 'Görseli sürükleyin veya tıklayarak seçin',
            'image_hint'            => 'JPEG, PNG veya WebP. Maks. 2 MB.',
            'is_published'          => 'Yayında',
            'published_at'          => 'Yayın Tarihi',
        ],

        'table' => [
            'id'           => '#',
            'title'        => 'Başlık',
            'category'     => 'Kategori',
            'author'       => 'Yazar',
            'status'       => 'Durum',
            'published_at'  => 'Yayın Tarihi',
            'actions'       => 'İşlemler',
            'translations'  => 'Çeviriler',
        ],

        'created_successfully'     => 'Yazı başarıyla oluşturuldu.',
        'updated_successfully'     => 'Yazı başarıyla güncellendi.',
        'deleted_successfully'     => 'Yazı başarıyla silindi.',
        'published_successfully'   => 'Yazı yayınlandı.',
        'unpublished_successfully' => 'Yazı taslağa alındı.',
        'creation_failed'          => 'Yazı oluşturulamadı.',
        'update_failed'            => 'Yazı güncellenemedi.',
        'deletion_failed'          => 'Yazı silinemedi.',
        'confirm_delete'           => 'Bu yazıyı silmek istediğinizden emin misiniz?',
        'no_posts'                 => 'Henüz yazı bulunmuyor.',
    ],

    // ─── admin/pages.php ──────────────────────────────────────────────────────
    'pages' => [
        'title'    => 'Sayfalar',
        'subtitle' => 'Statik sayfaları yönetin (Hakkımızda, İletişim, Gizlilik vb.).',

        'create' => 'Yeni Sayfa',
        'edit'   => 'Sayfayı Düzenle',
        'delete' => 'Sayfayı Sil',

        'status_published' => 'Yayında',
        'status_draft'     => 'Taslak',

        'form' => [
            'content_section'       => 'Sayfa İçeriği',
            'seo_section'           => 'SEO & URL',
            'settings_section'      => 'Ayarlar',
            'title'                 => 'Başlık',
            'title_hint'            => 'Her dil için sayfa başlığını girin.',
            'slug'                  => 'Slug (URL)',
            'slug_hint'             => 'Boş bırakılırsa başlıktan otomatik oluşturulur.',
            'content'               => 'İçerik',
            'meta_description'      => 'Meta Açıklama',
            'meta_description_hint' => 'Arama motorları için SEO açıklaması (maks. 160 karakter).',
            'is_published'          => 'Yayında',
            'is_homepage'           => 'Ana Sayfa Olarak Ayarla',
            'sort_order'            => 'Sıralama',
        ],

        'table' => [
            'id'           => '#',
            'title'        => 'Başlık',
            'translations' => 'Diller',
            'status'       => 'Durum',
            'sort_order'   => 'Sıra',
            'actions'      => 'İşlemler',
        ],

        'created_successfully' => 'Sayfa başarıyla oluşturuldu.',
        'updated_successfully' => 'Sayfa başarıyla güncellendi.',
        'deleted_successfully' => 'Sayfa başarıyla silindi.',
        'creation_failed'      => 'Sayfa oluşturulamadı.',
        'update_failed'        => 'Sayfa güncellenemedi.',
        'deletion_failed'      => 'Sayfa silinemedi.',
        'confirm_delete'       => 'Bu sayfayı silmek istediğinizden emin misiniz?',
        'no_pages'             => 'Henüz sayfa bulunmuyor.',
    ],

    // ─── admin/languages.php ────────────────────────────────────────────────────
    'languages' => [
        'title'    => 'Diller',
        'subtitle' => 'Sistem dillerini yönetin.',

        'create' => 'Yeni Dil',
        'edit'   => 'Dil Düzenle',
        'delete' => 'Dil Sil',

        'default' => 'Varsayılan',

        'form' => [
            'info_section'     => 'Dil Bilgileri',
            'settings_section' => 'Ayarlar',
            'code'             => 'Kod',
            'code_hint'        => 'ISO 639-1 iki harfli kod (örn. en, tr, de).',
            'name'             => 'İsim',
            'name_hint'        => 'Dil adı İngilizce (örn. English, Turkish).',
            'native'           => 'Yerel İsim',
            'native_hint'      => 'Kendi dilindeki ismi (örn. Türkçe).',
            'flag'             => 'Bayrak Emoji',
            'direction'        => 'Yazı Yönü',
            'direction_ltr'    => 'Soldan Sağa (LTR)',
            'direction_rtl'    => 'Sağdan Sola (RTL)',
            'sort_order'       => 'Sıralama',
            'is_active'        => 'Aktif',
            'is_default'       => 'Varsayılan Dil',
            'is_default_hint'  => 'Varsayılan dil, birincil yedek dil olarak kullanılır.',
        ],

        'table' => [
            'code'      => 'Kod',
            'name'      => 'Dil',
            'direction' => 'Yön',
            'default'   => 'Varsayılan',
            'status'    => 'Durum',
            'actions'   => 'İşlemler',
        ],

        'created_successfully'     => 'Dil başarıyla oluşturuldu.',
        'updated_successfully'     => 'Dil başarıyla güncellendi.',
        'deleted_successfully'     => 'Dil başarıyla silindi.',
        'activated_successfully'   => 'Dil aktif edildi.',
        'deactivated_successfully' => 'Dil pasif edildi.',
        'creation_failed'          => 'Dil oluşturulamadı.',
        'update_failed'            => 'Dil güncellenemedi.',
        'deletion_failed'          => 'Dil silinemedi.',
        'confirm_delete'           => 'Bu dili silmek istediğinizden emin misiniz? Mevcut çeviriler SİLİNMEZ.',
    ],

    // ─── admin/branches.php ─────────────────────────────────────────────────────
    'branches' => [
        'title'                => 'Şubeler',
        'create'               => 'Yeni Şube',
        'edit'                 => 'Şube Düzenle',
        'created_successfully' => 'Şube başarıyla oluşturuldu.',
        'updated_successfully' => 'Şube başarıyla güncellendi.',
        'deleted_successfully' => 'Şube başarıyla silindi.',
        'creation_failed'      => 'Şube oluşturulamadı.',
        'update_failed'        => 'Şube güncellenemedi.',
        'deletion_failed'      => 'Şube silinemedi.',
        'confirm_delete'       => 'Bu şubeyi silmek istediğinizden emin misiniz?',
        'main'                 => 'Ana Şube',

        'form' => [
            'branch_section'               => 'Şube Bilgileri',
            'contact_section'              => 'İletişim & Konum',
            'name'                         => 'Şube Adı',
            'email'                        => 'E-posta',
            'phone'                        => 'Telefon',
            'address'                      => 'Adres',
            'city'                         => 'Şehir',
            'sort_order'                   => 'Sıralama',
            'is_active'                    => 'Aktif',
            'is_main'                      => 'Ana Şube',
            'is_main_hint'                 => 'Her restoran için yalnızca bir şube ana şube olabilir.',
            'restaurant'                   => 'Restoran',
            'select_restaurant'            => 'Restoran',
            'select_restaurant_placeholder' => 'Restoran ara...',
            'select_restaurant_first'       => 'Mevcut dilleri görmek için lütfen önce bir restoran seçin.',
            'languages'                     => 'Menü Dilleri',
            'languages_placeholder'         => 'Dil seçin',
            'languages_hint'                => 'İlk seçilen dil şube adı için zorunludur.',
            'select_language_first'         => 'Lütfen en az bir dil seçin.',
        ],

        'table' => [
            'branch'     => 'Şube',
            'restaurant' => 'Restoran',
            'location'   => 'Konum',
        ],
    ],

    // ─── admin/settings.php ─────────────────────────────────────────────────────
    'settings' => [
        'title'                => 'Ayarlar',
        'updated_successfully' => 'Ayarlar başarıyla güncellendi.',
        'update_failed'        => 'Ayarlar güncellenemedi.',

        'form' => [
            'general_section'  => 'Genel Bilgiler',
            'contact_section'  => 'İletişim Bilgileri',
            'social_section'   => 'Sosyal Medya',
            'site_name'        => 'Site Adı',
            'site_description' => 'Site Açıklaması',
            'meta_title'       => 'Meta Başlık',
            'meta_description' => 'Meta Açıklama',
            'contact_email'    => 'İletişim E-postası',
            'contact_phone'    => 'İletişim Telefonu',
            'address'          => 'Adres',
            'facebook'         => 'Facebook',
            'instagram'        => 'Instagram',
            'twitter'          => 'X (Twitter)',
            'youtube'          => 'YouTube',
            'tiktok'           => 'TikTok',
            'logo'             => 'Logo',
            'logo_hint'        => 'JPEG, PNG veya WebP. Maks. 2 MB.',
            'favicon'          => 'Favicon',
            'favicon_hint'     => 'PNG veya ICO. Maks. 512 KB.',
        ],
    ],

    // ─── admin/subscriptions.php ──────────────────────────────────────────────
    'subscriptions' => [
        'title'    => 'Abonelikler',
        'subtitle' => 'Tüm kullanıcı abonelikleri.',

        'status' => [
            'active'    => 'Aktif',
            'trialing'  => 'Deneme',
            'cancelled' => 'İptal Edildi',
            'expired'   => 'Süresi Doldu',
            'past_due'  => 'Gecikmiş',
        ],

        'cycle' => [
            'monthly' => 'Aylık',
            'yearly'  => 'Yıllık',
        ],

        'filters' => [
            'title'        => 'Filtreler',
            'plan'         => 'Plan',
            'status'       => 'Durum',
            'cycle'        => 'Döngü',
            'date_from'    => 'Başlangıç Tarihi',
            'date_to'      => 'Bitiş Tarihi',
            'all_plans'    => 'Tüm Planlar',
            'all_statuses' => 'Tüm Durumlar',
            'all_cycles'   => 'Tüm Döngüler',
        ],

        'table' => [
            'user'       => 'Kullanıcı',
            'plan'       => 'Plan',
            'status'     => 'Durum',
            'cycle'      => 'Döngü',
            'amount'     => 'Tutar',
            'started_at' => 'Başlangıç',
            'ends_at'    => 'Bitiş',
        ],
    ],

    // ─── admin/payments.php ───────────────────────────────────────────────────
    'payments' => [
        'title'    => 'Ödemeler',

        'status' => [
            'pending'            => 'Beklemede',
            'succeeded'          => 'Başarılı',
            'failed'             => 'Başarısız',
            'refunded'           => 'İade Edildi',
            'partially_refunded' => 'Kısmi İade',
        ],

        'provider' => [
            'iyzico'        => 'İyzico',
            'bank_transfer' => 'Havale / EFT',
            'google_pay'    => 'Google Pay',
            'apple_pay'     => 'Apple Pay',
        ],

        'filters' => [
            'title'          => 'Filtreler',
            'provider'       => 'Ödeme Yöntemi',
            'status'         => 'Durum',
            'currency'       => 'Para Birimi',
            'date_from'      => 'Başlangıç Tarihi',
            'date_to'        => 'Bitiş Tarihi',
            'all_providers'  => 'Tüm Yöntemler',
            'all_statuses'   => 'Tüm Durumlar',
            'all_currencies' => 'Tüm Para Birimleri',
        ],

        'table' => [
            'user'     => 'Kullanıcı',
            'package'  => 'Paket',
            'credits'  => 'Kontör',
            'provider' => 'Ödeme Yöntemi',
            'amount'   => 'Tutar',
            'status'   => 'Durum',
            'paid_at'  => 'Ödeme Tarihi',
        ],
    ],

];
