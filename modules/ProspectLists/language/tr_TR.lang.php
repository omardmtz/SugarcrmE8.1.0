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

$mod_strings = array (
  // Dashboard Names
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Hedef Listeleri Panosu',

  'LBL_MODULE_NAME' => 'Hedef Listeler',
  'LBL_MODULE_NAME_SINGULAR' => 'Hedef Liste',
  'LBL_MODULE_ID'   => 'Hedef Listeler',
  'LBL_MODULE_TITLE' => 'Hedef Listeleri: Ana Sayfa',
  'LBL_SEARCH_FORM_TITLE' => 'Hedef Listeleri Arama',
  'LBL_LIST_FORM_TITLE' => 'Hedef Listeler',
  'LBL_PROSPECT_LIST_NAME' => 'Hedef Listesi:',
  'LBL_NAME' => 'İsim',
  'LBL_ENTRIES' => 'Toplam Kayıtlar',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Hedef Liste',
  'LBL_LIST_ENTRIES' => 'Listedeki Hedefler',
  'LBL_LIST_DESCRIPTION' => 'Tanım',
  'LBL_LIST_TYPE_NO' => 'Tipi',
  'LBL_LIST_END_DATE' => 'Bitiş Tarihi',
  'LBL_DATE_ENTERED' => 'Oluşturulma Tarihi',
  'LBL_MARKETING_ID' => 'Pazarlama ID',
  'LBL_DATE_MODIFIED' => 'Değiştirilme Tarihi',
  'LBL_MODIFIED' => 'Değiştiren',
  'LBL_CREATED' => 'Oluşturan',
  'LBL_TEAM' => 'Takım',
  'LBL_ASSIGNED_TO' => 'Atanan Kişi',
  'LBL_DESCRIPTION' => 'Tanım',
  'LNK_NEW_CAMPAIGN' => 'Kampanya Oluştur',
  'LNK_CAMPAIGN_LIST' => 'Kampanyalar',
  'LNK_NEW_PROSPECT_LIST' => 'Hedef Liste Oluştur',
  'LNK_PROSPECT_LIST_LIST' => 'Hedef Listeler',
  'LBL_MODIFIED_BY' => 'Değiştiren',
  'LBL_CREATED_BY' => 'Oluşturan',
  'LBL_DATE_CREATED' => 'Oluşturulma Tarihi',
  'LBL_DATE_LAST_MODIFIED' => 'Değiştirilme Tarihi',
  'LNK_NEW_PROSPECT' => 'Hedef Oluştur',
  'LNK_PROSPECT_LIST' => 'Hedefler',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Hedef Listeler',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaklar',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Potansiyeller',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Hedefler',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Müşteriler',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Kampanyalar',
  'LBL_COPY_PREFIX' =>'Kopyası',
  'LBL_USERS_SUBPANEL_TITLE' =>'Kullanıcılar',
  'LBL_TYPE' => 'Tipi',
  'LBL_LIST_TYPE' => 'Tipi',
  'LBL_LIST_TYPE_LIST_NAME'=>'Tipi',
  'LBL_NEW_FORM_TITLE'=>'Yeni Hedef Listesi',
  'LBL_MARKETING_NAME'=>'Pazarlama İsmi',
  'LBL_MARKETING_MESSAGE'=>'E-Posta Pazarlama Mesajı',
  'LBL_DOMAIN_NAME'=>'Alan İsmi',
  'LBL_DOMAIN'=>'Bu Alana E-Posta yok',
  'LBL_LIST_PROSPECTLIST_NAME'=>'İsim',
	'LBL_MORE_DETAIL' => 'Daha Fazla Detay' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}}, {{campaigns_singular_module}} toplu pazarlama kampanyalarında dahil edeceğiniz veya hariç tutacağınız bireyleri veya organizasyonları içermektedir. {{plural_module_name}} herhangi bir sayıda Hedefler, {{contacts_module}}, {{leads_module}}, Kullanıcılar ve {{accounts_module}} kombinasyonunu içerir. Hedefler bir {{module_name}} altında daha önce belirlenmiş kriterlere göre bir araya getirilebilir. Kriterlere örnek olarak yaş grubu, coğrafi lokasyon, harcama eğilimleri gösterilebilir. {{plural_module_name}}, toplu e-posta pazarlama {{campaigns_module}} içinde kullanılmaktadır ve {{campaigns_module}} modulü ile tanımlanmaktadır.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}}, {{campaigns_singular_module}} toplu pazarlama kampanyalarında dahil edeceğiniz veya hariç tutacağınız bireyleri veya organizasyonları içermektedir. 

- Kaydın alanlarını, herhangi bir alana veya "Değiştir" tuşuna tıklayarak değiştirin.
- Alt solda yer alan paneli "Kayıt Görünümü" olarak değiştirerek, {{campaigns_singular_module}} katılımcıları dahil olmak üzere, diğer kayıtlar ile olan bağlantıları görün veya değiştirin.
- Alt solda yer alan paneli "Aktivite Akışı" olarak değiştirerek, kullanıcı yorumları görün, oluşturun ve {{activitystream_singular_module}} kaydındaki değişiklik tarihçesini görün.
- Kaydın isminin sağındaki ikonlara tıklayarak kaydı izleyiniz veya favori olarak işaretleyiniz.
- Daha fazla aksiyon, Değiştir tuşunun sağındaki Aksiyonlar seçimli menüde yer almaktadır.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}}, toplu pazarlama kampanyasına dahil etmek veya dışarıda bırakmak istediğiniz bireyler ve organizasyonları içerir {{campaigns_singular_module}}.

{{module_name}} kaydı oluşturmak için:
1. Alanlar için değerleri istendiği gibi girin.
- "Zorunlu" olarak işaretlenmiş alanların kayıt öncesinde girilmesi gerekmektedir. 
- "Daha fazlasını göster" seçeneği ile, gerektiğinde ek alanları görünür kılın. 
2. "Kaydet" tuşuna basarak kayıt işlemini tamamlayın ve önceki sayfaya dönün.
3. Kayıt sonrasında, hedefin kayıt görüntüleme ekranındaki alt panelleri kullanarak, {{campaigns_singular_module}} katılımcılarına ekleyin.',
);
