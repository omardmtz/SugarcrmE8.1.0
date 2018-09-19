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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'Süreç İş Akışı Görevleri',
'LBL_OOTB_REPORTS'		=> 'Rapor Üretimi Planlanmış Görevleri Çalıştırın',
'LBL_OOTB_IE'			=> 'Gelen Posta kutularını Kontrol et',
'LBL_OOTB_BOUNCE'		=> 'Gecelik Çalışan Geri Dönen Kampanya E-Postaları',
'LBL_OOTB_CAMPAIGN'		=> 'Gecelik Çalışan Kitlesel E-Posta Kampanyaları',
'LBL_OOTB_PRUNE'		=> 'Ayın 1 inde Veritabanında temizlik yap',
'LBL_OOTB_TRACKER'		=> 'Takipçi Tabloları Temizle',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Eski Kayıt Listesini Silin',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Geçici dosyaları sil',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Hata bulma aracı dosyalarını sil',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Geçici PDF dosyalarını sil',
'LBL_UPDATE_TRACKER_SESSIONS' => 'takipçi_oturumlar tablosunu güncelleştir',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'E-posta Hatırlatma Bildirimlerini çalıştır',
'LBL_OOTB_CLEANUP_QUEUE' => 'İş Kuyruğunu Temizle',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Gelecek Zaman Aralıkları Oluştur',
'LBL_OOTB_HEARTBEAT' => 'Sugar Çalışırlık Sinyali',
'LBL_OOTB_KBCONTENT_UPDATE' => 'KBContent makalelerini güncelleyin.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Onaylanan makaleleri yayınla ve KB Makalelerinin süresini sonlandır.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Programlanmış İşi',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Normalleştirilmemiş Takım Güvenliği Verisini Yeniden Oluştur',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Sıklık Derecesi:',
'LBL_LIST_LIST_ORDER' => 'Planlayıcılar:',
'LBL_LIST_NAME' => 'Planlayıcı:',
'LBL_LIST_RANGE' => 'Aralık:',
'LBL_LIST_REMOVE' => 'Kaldır:',
'LBL_LIST_STATUS' => 'Durum:',
'LBL_LIST_TITLE' => 'Planlayıcı Listesi:',
'LBL_LIST_EXECUTE_TIME' => 'Burada Çalışacak:',
// human readable:
'LBL_SUN'		=> 'Pazar',
'LBL_MON'		=> 'Pazartesi',
'LBL_TUE'		=> 'Salı',
'LBL_WED'		=> 'Çarşamba',
'LBL_THU'		=> 'Perşembe',
'LBL_FRI'		=> 'Cuma',
'LBL_SAT'		=> 'Cumartesi',
'LBL_ALL'		=> 'Her gün',
'LBL_EVERY_DAY'	=> 'Her gün',
'LBL_AT_THE'	=> 'Burada',
'LBL_EVERY'		=> 'Her',
'LBL_FROM'		=> 'Kimden',
'LBL_ON_THE'	=> 'Üstünde',
'LBL_RANGE'		=> 'kime',
'LBL_AT' 		=> 'burada',
'LBL_IN'		=> 'içinde',
'LBL_AND'		=> 've',
'LBL_MINUTES'	=> 'dakika',
'LBL_HOUR'		=> 'saat',
'LBL_HOUR_SING'	=> 'saat',
'LBL_MONTH'		=> 'ay',
'LBL_OFTEN'		=> 'Olabildiğince sık.',
'LBL_MIN_MARK'	=> 'dakika işareti',


// crontabs
'LBL_MINS' => 'dak',
'LBL_HOURS' => 'sa',
'LBL_DAY_OF_MONTH' => 'tarih',
'LBL_MONTHS' => 'ay',
'LBL_DAY_OF_WEEK' => 'gün',
'LBL_CRONTAB_EXAMPLES' => 'Yukarısı standart crontab gösterimini kullanır.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Sunucunun cron tanımlarını çalıştırdığı zaman dilimi (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Lütfen planlayıcının zamanını buna göre belirleyiniz.',
// Labels
'LBL_ALWAYS' => 'Her zaman',
'LBL_CATCH_UP' => 'Vakti geçmiş ise çalıştır',
'LBL_CATCH_UP_WARNING' => 'Eğer bu iş bir saniyeden daha fazla sürecek ise işareti kaldırın.',
'LBL_DATE_TIME_END' => 'Bitiş Tarih&Zamanı',
'LBL_DATE_TIME_START' => 'Başlangıç Tarih&Zamanı',
'LBL_INTERVAL' => 'Sıklık Derecesi',
'LBL_JOB' => 'Görev',
'LBL_JOB_URL' => 'İş URL',
'LBL_LAST_RUN' => 'Son Başarılı Çalışma',
'LBL_MODULE_NAME' => 'Sugar Planlayıcı',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar Planlayıcı',
'LBL_MODULE_TITLE' => 'Planlayıcılar',
'LBL_NAME' => 'Görev İsmi',
'LBL_NEVER' => 'Hiç',
'LBL_NEW_FORM_TITLE' => 'Yeni Plan',
'LBL_PERENNIAL' => 'sürekli',
'LBL_SEARCH_FORM_TITLE' => 'Planlayıcı Arama',
'LBL_SCHEDULER' => 'Planlayıcı:',
'LBL_STATUS' => 'Durum',
'LBL_TIME_FROM' => 'Aktiflik Başlangıcı',
'LBL_TIME_TO' => 'Aktiflik Bitişi',
'LBL_WARN_CURL_TITLE' => 'cURL Uyarısı:',
'LBL_WARN_CURL' => 'Uyarı:',
'LBL_WARN_NO_CURL' => 'Bu sistem PHP modülü içerisine etkinleştirilmiş/derlenmiş cURL kütüphanelerine (--with-curl=/path/to/curl_library) sahip değil. Bu problemin çözümü için, sistem yöneticisi ile temasa geçiniz. Planlayıcı cURL işlevselliği olmadan görevlerini gerçekleştiremez.',
'LBL_BASIC_OPTIONS' => 'Basit Kurulum',
'LBL_ADV_OPTIONS'		=> 'Gelişmiş Seçenekler',
'LBL_TOGGLE_ADV' => 'Gelişmiş Seçenekleri Gösterin',
'LBL_TOGGLE_BASIC' => 'Temel Seçenekleri Göster',
// Links
'LNK_LIST_SCHEDULER' => 'Planlayıcılar',
'LNK_NEW_SCHEDULER' => 'Planlayıcı Oluştur',
'LNK_LIST_SCHEDULED' => 'Planlanmış İşler',
// Messages
'SOCK_GREETING' => "Bu ara yüz SugarCRM Planlayıcı Servisi içindir. [Mevcut daemon komutları: start|restart|shutdown|status] Çıkmak için &#39;quit&#39; yazın. Servisi kapatmak için &#39;shutdown&#39; yazın",
'ERR_DELETE_RECORD' => 'Bu planı silmek için bir kayıt numarası belirtmelisiniz.',
'ERR_CRON_SYNTAX' => 'Geçersiz Cron söz dizimi',
'NTC_DELETE_CONFIRMATION' => 'Bu kaydı silmek istediğinizden emin misiniz?',
'NTC_STATUS' => 'Bu planlayıcının durumunu Planlayıcı açılır-listesinden kaldırmak için, İnaktif olarak belirleyerek',
'NTC_LIST_ORDER' => 'Bu planlayıcının, Planlayıcı açılır-listesinde görünmesini istediğiniz sırayı belirleyin',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Windows Planlayıcı Kurulumu için',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Crontab Kurulumu için',
'LBL_CRON_LINUX_DESC' => 'Not: Sugar Planlayıcısını çalıştırabilmek için şu satırları crontab dosyasına ekleyin:',
'LBL_CRON_WINDOWS_DESC' => 'Not: Sugar Planlayıcısını çalıştırmak için batch dosyası oluşturup, Windows Planlanmış Görevleri kullanın. Batch dosyası şu komutları içermelidir:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Görev Log&#39;u',
'LBL_EXECUTE_TIME'			=> 'Çalıştırılma Zamanı',

//jobstrings
'LBL_REFRESHJOBS' => 'İşleri Yenile',
'LBL_POLLMONITOREDINBOXES' => 'Kontrol Et',
'LBL_PERFORMFULLFTSINDEX' => 'Tam-Metin Arama İndeks Sistemi',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Geçici PDF dosyalarını sil',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Onaylanan makaleleri yayınla ve KB Makalelerinin süresini sonlandır.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch Queue Zamanlayıcısı',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Hata bulma aracı dosyalarını sil',
'LBL_SUGARJOBREMOVETMPFILES' => 'Geçici dosyaları sil',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Normalleştirilmemiş Takım Güvenliği Verisini Yeniden Oluştur',

'LBL_RUNMASSEMAILCAMPAIGN' => 'Gecelik Çalışan Kitlesel E-Posta Kampanyaları',
'LBL_ASYNCMASSUPDATE' => 'Asenkron Toplu Güncelleme Uygulayın',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'Gecelik Çalışan Geri Dönen Kampanya E-Postaları',
'LBL_PRUNEDATABASE' => 'Ayın 1 inde Veritabanında temizlik yap',
'LBL_TRIMTRACKER' => 'Takipçi Tabloları Temizle',
'LBL_PROCESSWORKFLOW' => 'Süreç İş Akışı Görevleri',
'LBL_PROCESSQUEUE' => 'Rapor Üretimi Planlanmış Görevleri Çalıştırın',
'LBL_UPDATETRACKERSESSIONS' => 'Takipçi Oturum Tablolarını Güncelle',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Gelecek Zaman Aralıkları Oluştur',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Çalışırlık Sinyali',
'LBL_SENDEMAILREMINDERS'=> 'E-posta Hatırlatıcısı Göndermeyi çalıştır',
'LBL_CLEANJOBQUEUE' => 'İş Kuyruğunu Temizle',
'LBL_CLEANOLDRECORDLISTS' => 'Eski Kayıt Listesini Temizle',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Programlayıcısı',
);

