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
    'LBL_MODULE_NAME' => 'Veri Gizliliği',
    'LBL_MODULE_NAME_SINGULAR' => 'Veri Gizliliği',
    'LBL_NUMBER' => 'Numara',
    'LBL_TYPE' => 'Tip',
    'LBL_SOURCE' => 'Kaynak',
    'LBL_REQUESTED_BY' => 'Talep Eden',
    'LBL_DATE_OPENED' => 'Açılma Tarihi',
    'LBL_DATE_DUE' => 'Son Bitirme Tarihi',
    'LBL_DATE_CLOSED' => 'Kapanma Tarihi',
    'LBL_BUSINESS_PURPOSE' => 'Şunun İçin İç Amaçlı Onaylı:',
    'LBL_LIST_NUMBER' => 'Numara',
    'LBL_LIST_SUBJECT' => 'Konu',
    'LBL_LIST_PRIORITY' => 'Öncelik',
    'LBL_LIST_STATUS' => 'Durum',
    'LBL_LIST_TYPE' => 'Tip',
    'LBL_LIST_SOURCE' => 'Kaynak',
    'LBL_LIST_REQUESTED_BY' => 'Talep Eden',
    'LBL_LIST_DATE_DUE' => 'Son Bitirme Tarihi',
    'LBL_LIST_DATE_CLOSED' => 'Kapanma Tarihi',
    'LBL_LIST_DATE_MODIFIED' => 'Değiştirilme Tarihi',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Değiştiren',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Atanan Kullanıcı',
    'LBL_SHOW_MORE' => 'Daha Fazla Veri Gizliliği Aktivitesi Göster',
    'LNK_DATAPRIVACY_LIST' => 'Veri Gizliliği Aktivitelerini Göster',
    'LNK_NEW_DATAPRIVACY' => 'Veri Gizliliği Aktivitesi Oluştur',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Potansiyeller',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaklar',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Hedefler',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Hesaplar',
    'LBL_LISTVIEW_FILTER_ALL' => 'Tüm Veri Gizliliği Aktiviteleri',
    'LBL_ASSIGNED_TO_ME' => 'Veri Gizliliği Aktivitelerim',
    'LBL_SEARCH_AND_SELECT' => 'Veri Gizliliği Aktivitelerini Arayın ve Seçin',
    'TPL_SEARCH_AND_ADD' => 'Veri Gizliliği Aktivitelerini Arayın ve Ekleyin',
    'LBL_WARNING_ERASE_CONFIRM' => '{0} alanı kalıcı olarak silmek üzeresiniz. Silme işlemi tamamlandıktan sonra bu verileri geri getirme seçeneği yoktur. Devam etmek istediğinizden emin misiniz?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Silme işlemi için {0} alana sahipsiniz. Onaylamak silme işlemini iptal edecek, tüm verileri koruyacak ve bu isteği reddedildi olarak işaretleyecek. Devam etmek istediğinizden emin misiniz?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Bu istediği tamamlandı olarak işaretlemek üzeresiniz. Bu, durumu kalıcı şekilde Tamamlandı olarak ayarlayacak ve tekrar açılamayacak. Devam etmek istediğinizden emin misiniz?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Bu isteği reddedildi olarak işaretlemek üzeresiniz. Bu, durumu kalıcı şekilde Reddedildi olarak ayarlayacak ve tekrar açılamayacak. Devam etmek istediğinizden emin misiniz?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Veri gizliliği aktivitesini başarılı şekilde oluşturdunuz <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Reject',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Tamamla',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Sil ve Tamamla',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Alt paneller vasıtasıyla seçilen alanları sil',
    'LBL_COUNT_FIELDS_MARKED' => 'Silinecek Olarak İşaretlenen Alanlar',
    'LBL_NO_RECORDS_MARKED' => 'Silinecek Alanlar veya Kayıtlar işaretlenmedi.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Veri Gizliliği Kaydı Panosu',

    // list view
    'LBL_HELP_RECORDS' => 'Veri Gizliliği modülü, şirketinizin gizlilik prosedürlerinizi desteklemek için izin verme ve reddetme istekleri dahil gizlilik aktivitelerini takip eder. İzni takip etmek veya bir gizlilik isteği hakkında işlem yapmak için bir kişinin kaydıyla (ör. bir kontak) bağlantılı veri gizliliği kayıtları oluşturun.',
    // record view
    'LBL_HELP_RECORD' => 'Veri Gizliliği modülü, şirketinizin gizlilik prosedürlerinizi desteklemek için izin verme ve reddetme istekleri dahil gizlilik aktivitelerini takip eder. İzni takip etmek veya bir gizlilik isteği hakkında işlem yapmak için bir kişinin kaydıyla (ör. bir kontak) bağlantılı veri gizliliği kayıtları oluşturun. Gereken eylem tamamlandığında Veri Gizliliği Yöneticisi rolündeki kullanıcılar, durumu güncellemek için "Tamamla" veya "Reddet"e tıklayabilir.

Silme istekleri için aşağıdaki alt panellerde listelenen kişinin kayıtlarının her biri için "Silmek İçin İşaretle"yi seçin. İstenen tüm alanlar seçildiğinde "Sil ve Tamamla"ya tıklamak alanların değerlerini kalıcı olarak kaldıracak ve veri gizliliği kaydını tamamlandı olarak işaretleyecek.',
);
