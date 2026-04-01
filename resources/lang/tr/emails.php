<?php

return [

    'welcome' => [
        'subject'      => ':app_name\'e Hoş Geldiniz!',
        'heading'      => 'Hoş geldiniz!',
        'greeting'     => 'Merhaba :name,',
        'body'         => 'Hesabınız başarıyla oluşturuldu. Restoran panelinizle neler yapabileceğiniz:',
        'feature_menu' => 'Dijital menünüzü oluşturun ve yönetin',
        'feature_qr'   => 'Masalarınız için QR kod üretin',
        'feature_ai'   => 'Menünüzü AI ile ayrıştırın ve çevirin',
        'cta'          => 'Panele Git',
        'footer_note'  => 'Bu hesabı siz oluşturmadıysanız bu e-postayı görmezden gelebilirsiniz.',
    ],

    'subscription_started' => [
        'subject'  => 'Abonelik Aktive Edildi',
        'heading'  => 'Aboneliğiniz aktif!',
        'greeting' => 'Merhaba :name,',
        'body'     => 'Aboneliğiniz başarıyla aktive edildi. Detaylar:',
        'plan'     => 'Plan',
        'renewal'  => 'Yenileme Tarihi',
        'amount'   => 'Ödenen Tutar',
        'status'   => 'Durum',
        'active'   => 'Aktif',
        'free'     => 'Ücretsiz',
        'cta'      => 'Faturayı Görüntüle',
    ],

    'subscription_cancelled' => [
        'subject'          => 'Abonelik İptal Edildi',
        'heading'          => 'Aboneliğiniz iptal edildi.',
        'greeting'         => 'Merhaba :name,',
        'body'             => 'Aboneliğiniz iptal edildi. Mevcut dönem sonuna kadar erişiminiz devam edecektir.',
        'plan'             => 'Plan',
        'access_until'     => 'Erişim Tarihi',
        'status'           => 'Durum',
        'cancelled'        => 'İptal Edildi',
        'resubscribe_note' => 'İstediğiniz zaman fatura sayfasından yeniden abone olabilirsiniz.',
        'cta'              => 'Planları Görüntüle',
    ],

    'account_created' => [
        'subject'          => 'Hesabınız Oluşturuldu — :app_name',
        'heading'          => 'Hesabınız hazır!',
        'greeting'         => 'Merhaba :name,',
        'body'             => 'Yönetici tarafından sizin için bir hesap oluşturuldu. Aşağıdaki bilgilerle giriş yapabilirsiniz:',
        'email'            => 'E-posta',
        'password'         => 'Şifre',
        'password_warning' => 'Güvenliğiniz için lütfen ilk girişinizden sonra şifrenizi değiştirin.',
        'cta'              => 'Giriş Yap',
        'footer_note'      => 'Bu e-posta :app_name tarafından gönderilmiştir. Bunun bir hata olduğunu düşünüyorsanız lütfen yönetici ile iletişime geçin.',
    ],

    'payment_failed' => [
        'subject'      => 'Ödeme Başarısız',
        'heading'      => 'Ödemeniz işlenemedi.',
        'greeting'     => 'Merhaba :name,',
        'body'         => 'Son ödemeniz işlenemedi. Aboneliğinizin devam etmesi için lütfen ödeme yönteminizi güncelleyin.',
        'restaurant'   => 'Restoran',
        'amount'       => 'Tutar',
        'status'       => 'Durum',
        'failed'       => 'Başarısız',
        'action_note'  => 'Ödemeyi tekrar denemek veya yeni bir plan seçmek için fatura sayfasını ziyaret edin.',
        'cta'          => 'Faturaya Git',
    ],

];
