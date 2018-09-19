<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$mod_strings = array(
    'LBL_MODULE_NAME' => 'İş Sırası',
    'LBL_MODULE_NAME_SINGULAR' => 'İş Sırası',
    'LBL_MODULE_TITLE' => 'İş Sırası: Ana Sayfa',
    'LBL_MODULE_ID' => 'İş Sırası',
    'LBL_TARGET_ACTION' => 'Aksiyon',
    'LBL_FALLIBLE' => 'Hatalı Olabilir',
    'LBL_RERUN' => 'Yeniden Çalıştır',
    'LBL_INTERFACE' => 'Arayüz',
    'LINK_SCHEDULERSJOBS_LIST' => 'İş Sırasını Göster',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'Yapılandırma',
    'LBL_CONFIG_PAGE' => 'İş Sırası Ayarları',
    'LBL_JOB_CANCEL_BUTTON' => 'İptal',
    'LBL_JOB_PAUSE_BUTTON' => 'Duraklat',
    'LBL_JOB_RESUME_BUTTON' => 'Yeniden Başla',
    'LBL_JOB_RERUN_BUTTON' => 'Yeniden Sıra Oluştur',
    'LBL_LIST_NAME' => 'İsim',
    'LBL_LIST_ASSIGNED_USER' => 'Talep Eden',
    'LBL_LIST_STATUS' => 'Durum',
    'LBL_LIST_RESOLUTION' => 'Çözünürlük',
    'LBL_NAME' => 'Görev İsmi',
    'LBL_EXECUTE_TIME' => 'Çalıştırılma Zamanı',
    'LBL_SCHEDULER_ID' => 'Planlayıcı',
    'LBL_STATUS' => 'İş Durumu',
    'LBL_RESOLUTION' => 'Sonuç',
    'LBL_MESSAGE' => 'Mesajlar',
    'LBL_DATA' => 'İş Verisi',
    'LBL_REQUEUE' => 'Hata durumunda tekrar dene',
    'LBL_RETRY_COUNT' => 'Maksimum deneme sayısı',
    'LBL_FAIL_COUNT' => 'Başarısızlıklar',
    'LBL_INTERVAL' => 'Denemeler arasındaki minimum aralık',
    'LBL_CLIENT' => 'Sahip olan istemci',
    'LBL_PERCENT' => 'Tamamlanma Yüzdesi',
    'LBL_JOB_GROUP' => 'İş grubu',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'Çözünürlük Sıraya Sokuldu',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'Kısmi Çözünürlük',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'Tam Çözünürlük',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'Çözünürlük Başarısız',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'Çözünürlük İptal Edildi',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'Çözünürlük Çalışıyor',
    // Errors
    'ERR_CALL' => "Fonksiyonu çağrılamıyor: %s",
    'ERR_CURL' => "CURL Yok - URL işlerini çalıştıramazsınız",
    'ERR_FAILED' => "Beklenmedik bir hata oluştu, PHP log&#39;larını ve sugarcrm.log&#39;unu kontrol ediniz",
    'ERR_PHP' => "%s [%d]: %s  içinde  %s  satırında %d",
    'ERR_NOUSER' => "İş için belirtilen Kullanıcı ID&#39;si yok",
    'ERR_NOSUCHUSER' => "Kullanıcı ID %s bulunamadı",
    'ERR_JOBTYPE' => "Bilinmeyen iş türü: %s",
    'ERR_TIMEOUT' => "Zaman aşımından dolayı hata oluştu",
    'ERR_JOB_FAILED_VERBOSE' => 'İş %1$s (%2$s) CRON çalıştırılmasında başarısız',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'Şu kimliğe sahip çekirdek yüklenemiyor: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => '%s rotasının işleyicisi bulunamıyor',
    'ERR_CONFIG_MISSING_EXTENSION' => 'Bu sıra için uzantı yüklü değil',
    'ERR_CONFIG_EMPTY_FIELDS' => 'Bazı alanlar boş',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'İş Sırası Ayarları',
    'LBL_CONFIG_MAIN_SECTION' => 'Ana Yapılandırma',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman Yapılandırması',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP Yapılandırması',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs Yapılandırması',
    'LBL_CONFIG_SERVERS_TITLE' => 'İş Sırası Yapılandırma Yardımı',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>An Yapılandırma Bölümü.</b></p>
<ul>
    <li>Çalıştırıcı:
    <ul>
    <li><i>Standard</i> - işçiler için sadece bir süreç kullanın.</li>
    <li><i>Paralel</i> - işçiler için birkaç süreç kullanın.</li>
    </ul>
    </li>
    <li>Adaptör:
    <ul>
    <li><i>Varsayılan Sıra</i> - Bu, hiç mesaj sırası olmadan sadece Sugar'ın Veri Tabanını kullanacak.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Hizmeti Amazon.com
    tarafından sunulan dağıtılmış bir sıra mesajlaşma servisidir.
    İnternet üzerinden bir iletişim yolu olarak mesajların web vasıtasıyla programla gönderilmesini destekler.</li>
    <li><i>RabbitMQ</i> - Gelişmiş Mesaj Sıralama Protokolünü (AMQP) uygulayan açık kaynak mesaj acentesidir (bazen mesaj odaklı aracı yazılım olarak da bilinir).</li>
    <li><i>Gearman</i> - birden fazla bilgisayara uygun bilgisayar görevlerini dağıtmak için tasarlanmış açık kaynaklı bir yazılım çerçevesidir, böylece büyük görevler daha hızlı şekilde yapılabilir.</li>
    <li><i>Anında</i> - Varsayılan sıraya benzer ancak görevleri eklenmelerinden hemen sonra yürütür.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS yapılandırma Yardımı',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS Yapılandırma Bölümü.</b></p>
<ul>
    <li>Erişim Anahtarı Kimliği: <i>Amozon SQS için erişim anahtarı kimliğinizi girin</i></li>
    <li>Gizli Erişim Anahtarı: <i>Amazon SQS için gizli erişim anahtarınızı girin</i></li>
    <li>Bölge: <i>Amazon SQS sunucusunun bölgesini girin</i></li>
    <li>Sıra Adı: <i>Amazon SQS sunucusunun sıra adını girin</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP Yapılandırma Yardımı',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP Yapılandırma Bölümü.</b></p>
<ul>
    <li>Sunucu UR'si: <i>Mesaj sırası sunucu URL'sini girin.</i></li>
    <li>Oturum Açma: <i>RabbitMQ için oturum açma bilgilerinizi girin</i></li>
    <li>Parola: <i>RabbitMQ için parolanızı girin</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman Yapılandırma Yardımı',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman Yapılandırma Bölümü.</b></p>
<ul>
    <li>Sunucu URL'si: <i>Mesaj sırası sunucu URL'sini girin.</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'Bağdaştırıcı',
    'LBL_CONFIG_QUEUE_MANAGER' => 'Çalıştırıcı',
    'LBL_SERVER_URL' => 'Sunucu URL&#39;si',
    'LBL_LOGIN' => 'Oturum Aç',
    'LBL_ACCESS_KEY' => 'Erişim Anahtarı Kimliği',
    'LBL_REGION' => 'Bölge',
    'LBL_ACCESS_KEY_SECRET' => 'Gizli Erişim Anahtarı',
    'LBL_QUEUE_NAME' => 'Bağdaştırıcı Adı',
);
