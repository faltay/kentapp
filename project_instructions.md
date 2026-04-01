1. Laravel ile oluşturalacak bir qr menu saası
2. sistem çok dilli olacak yani hem admin panelinde hemde önyüzde kullanıcı dil değiştirebilecek. ancak dil değiştiğinde ön yüzdeki url yapısıda dile uygun olmalı.
mesela 
qrmenu.com/blog/blog-article-title ingilizce iken
qrmenu.com/tr/yazılar/blog-yazisi-basligi türkçe olacak

3. dil seçeneği geniş olacak. istediğim gibi genişletebilmeliyim. sadece qr menu dili için değil. sistem global bir proje olduğu için bu projeye hizmet vereceğim her ülkenin dilini ekleyebilmeliyim

4. üyelik yetkilendirmeye ihtiyac olacak. panelde herhangi bir kullanıcının hangi işlemleri yapacağı belirlenebilmeli.
Rol sistemi şöyle olacak
Sistem Rolleri - Değiştirilemez temel roller
Özel Roller - Kullanıcılar istediğini oluşturabilir
Esneklik - Her restoran kendi rollerini tanımlayabilir
Güvenlik - Sistem rolleri korunmuş
Çok Dilli - Rol açıklamaları çevrilebilir

5. farklı üyelik planları olacak. ücretsiz, standart, pro gibi. herbirinin limitleri farklı olacak. mesela standart üye 1 restoran 1 şube 20 ürün girebiliyorken, pro üye 1 restoran 5 şube toplamda 150 ürün girebiliyor olacak.

6. Ödeme sistemi için stripe ve iyzico olacak yurtiçi müşteriler iyzico yurtdışı müşteriler stripedan ödeme yapacak

7. iki tane admin paneli olacak. admin ve restoran için. 
- Admindeki özellikler
Üye listelemesi
restoran listemelesi
üyelik planı listeleme ve yeni üyelik planı ekleme
ödemeleri listeleme
sayfa listeleme, ekleme ve düzenleme
blog yazısı listeleme, ekleme ve düzenleme
dil ekleme, silme
- Restoran özellikleri
restoran bilgilerini güncelleme
şube listeleme, ekleme, düzenleme,
kullanıcı listeleme, ekleme, düzenleme. (kullanıcı yetkilendirmesi burada yapılacak. mesela kullanıcı bir şubedeki menüleri düzenleyebilsin ama yeni şube ekleyemesin gibi)
menui listeleme,ekleme,düzenleme
masa listeleme,ekleme,düzenleme
üyelik planını görüntüleme, plan değiştirme
faturaları görme. kendi üyelik ödemesi için kesilen faturaları.

8. Multitenant olmayacak

9. Restoranlar ellerindeki menüleri resim dosyası veya pdf formatında sisteme yüklediğinde o menudeki ürünleri fiyatlarıyla beraber sisteme girecek bir özellik istiyorum. ama bu basit bir ocr mantığı olmayacak. muhtemelen bir yapay zeka apisi kullanacağız. çünkü mesela yüklenilen menude çorbalar kategorisi altında mercimek çorbası var. sistem bunu otomatik olarak çorbalar kategorisi varsa ve mercimek çorbası ürünü varsa fiyatını güncelleyecek. ürün veya kategori yoksa kategoriyi oluşturup altına ürün ve fiyatını girecek. aynı zamanda menuleri otomatik başka dillere çevirebilecek yapay zeka. bunun için openai gpt kullanılacak