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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'E-Posta Arşivleme',
    'LBL_SNIP_SUMMARY' => "E-Posta arşivleme, kullanıcıların e-postaları herhangi bir e-posta istemcisi üzerinden, Sugar tarafından sağlanan e-posta adresine göndererek uygulamaya yüklenmesini sağlayan sistemdir. Her Sugar kurulumu, kendisine ait tekil bir e-posta adresi içerir. E-Postaları yüklemek için, kullanıcı e-postayı KIME, BİLGİ veya GİZLİ BİLGİ alanlarını kullanarak bu e-posta adresine gönderir. E-Posta Arşivleme sistemi, e-postayı sugar kurulumuna yükler. Servis, e-postayı bütün ekleri, imajlar ve Takvim olayları ile birlikte yükleyip, e-posta adresi ile eşleştirerek ilgili kayıtları oluşturur. <br><br><br />Örnek: Bir kullanıcı olarak bir müşteriyi görüntülediğimde, Müşteri kaydının e-posta adresine göre eşleşen bütün e-postaları görebileceğim. Ayrıca, Müşteri ile ilişkili Kontakların e-postalarını da görebileceğim.<br><br>Aşağıdaki koşulları kabul edin ve hizmeti başlatmak için Etkinleştir butonuna tıklayın. Hizmet etkinleştiğinde, hizmeti kullanmak için gerekli e-posta adresi görünür hale gelecektir.<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Arşivlen E-Posta servisiyle bağlantı başarısız: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'E-Posta Arşivleme',
    'LBL_DISABLE_SNIP' => 'Etkisizleştirin',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Uygulama Tekil Anahtarı',
    'LBL_SNIP_USER' => 'E-Posta Arşivleyen Kullanıcı',
    'LBL_SNIP_PWD' => 'E-Posta Arşivleme Şifresi',
    'LBL_SNIP_SUGAR_URL' => 'Sugar Kurulum URL adresi',
	'LBL_SNIP_CALLBACK_URL' => 'E-Posta arşivleme servis URL Adresi',
    'LBL_SNIP_USER_DESC' => 'E-Posta Arşivleyen Kullanıcı',
    'LBL_SNIP_KEY_DESC' => 'E-Posta arşivleme OAuth anahtarı. E-Postaları almak amacıyla bu kuruluma erişmek için kullanılır.',
    'LBL_SNIP_STATUS_OK' => 'Etkin',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'Bu Sugar kurulumu başarıyla E-Posta Arşivleme sunucusuna bağlandı.',
    'LBL_SNIP_STATUS_ERROR' => 'Hata',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'Bu kurulumun geçerli bir e-posta arşivleme sunucu lisansı var fakat sunucu aşağıdaki hata mesajını döndürdü:',
    'LBL_SNIP_STATUS_FAIL' => 'E-Posta Arşivleme sunucusuna tanımlama yapılamıyor',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'Arşivlenmiş E-Posta servisi şu anda kullanılamaz. Servis arızalı veya Sugar kurulumu ile bağlantı başarısız oldu.',
    'LBL_SNIP_GENERIC_ERROR' => 'Arşivlenmiş E-Posta servisi şu anda kullanılamaz. Servis arızalı veya Sugar kurulumu ile bağlantı başarısız oldu.',

	'LBL_SNIP_STATUS_RESET' => 'Henüz çalışmadı',
	'LBL_SNIP_STATUS_PROBLEM' => 'Sorun: %s',
    'LBL_SNIP_NEVER' => "Hiç",
    'LBL_SNIP_STATUS_SUMMARY' => "E-Posta Arşivleme hizmetinin durumu:",
    'LBL_SNIP_ACCOUNT' => "Müşteri",
    'LBL_SNIP_STATUS' => "Durum",
    'LBL_SNIP_LAST_SUCCESS' => "Son başarılı çalışma",
    "LBL_SNIP_DESCRIPTION" => "E-Posta arşivleme servisi otomatik bir e-posta arşivleme sistemidir",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "E-Postaları manuel olarak yükleme ve bağlamaya gerek kalmadan, SugarCRM içindeki kontaklarınıza gönderilen veya alınan e-postaların görülmesini sağlar",
    "LBL_SNIP_PURCHASE_SUMMARY" => "E-Posta Arşivlemesini kullanmak için bir SugarCRM lisansı satın almanız gerekmektedir",
    "LBL_SNIP_PURCHASE" => "Satın almak için tıklayınız",
    'LBL_SNIP_EMAIL' => 'E-Posta Arşivleme Adresi',
    'LBL_SNIP_AGREE' => "Yukarıdaki şartları ve <a href=\"http://www.sugarcrm.com/crm/TRUSTe/privacy.html\" target=\"_blank\">gizlilik sözleşmesini</a> kabul ediyorum.",
    'LBL_SNIP_PRIVACY' => 'gizlilik sözleşmesi',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback başarısız',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'E-Posta Arşivleme sunucusu Sugar kurulumun ile bağlantı kuramıyor. Tekrar deneyin veya <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">müşteri desteğine</a> başvurun.',

    'LBL_SNIP_BUTTON_ENABLE' => 'E-Posta Arşivleme Etkinleştir',
    'LBL_SNIP_BUTTON_DISABLE' => 'E-Posta Arşivlemeyi Kapat',
    'LBL_SNIP_BUTTON_RETRY' => 'Yeniden Bağlanmayı Deneyin',
    'LBL_SNIP_ERROR_DISABLING' => 'Arşiv E-posta sunucusuyla iletişim kurmayı denerken bir hata oluştu ve servis devre dışı bırakılamadı',
    'LBL_SNIP_ERROR_ENABLING' => 'Arşivlenmiş E-posta sunucusuyla iletişim kurmayı denerken hata oluştu ve servis etkinleştirilemedi',
    'LBL_CONTACT_SUPPORT' => 'Lütfen tekrar deneyin veya SugarCRM Destek ile iletişime geçin.',
    'LBL_SNIP_SUPPORT' => 'Yardım için SugarCRM Destek birimiyle temasa geçin.',
    'ERROR_BAD_RESULT' => 'Servisten kötü cevap döndü',
	'ERROR_NO_CURL' => 'cURL uzantıları gerekli, ancak etkinleştirilmemiş',
	'ERROR_REQUEST_FAILED' => 'Sunucuya bağlantı kurulamadı',

    'LBL_CANCEL_BUTTON_TITLE' => 'İptal',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'Sistemin e-posta arşivleme servisinin durumu. Bu durum e-posta arşivleme servisi ile sugar kurulumu arasındaki bağlantının başarılı olup olmadığını gösterir.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'E-Posta Arşivleme sisteminde kaydedilmesi için gönderilecek e-posta adresidir.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'Bu URL adresi, E-Posta Arşivleme sunucusuna aittir. E-posta arşivleme sunucusunu etkinleştirmek ve devre dışı bırakmak gibi bütün istekler bu URL üzerinden iletilecektir.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'Bu Sugar Kurulumunun web hizmetleri URL adresidir. E-Posta Arşivlenme sunucusu bu URL üzerinden sunucuya bağlanmaktadır.',
);
