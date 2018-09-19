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
    'ERR_ADD_RECORD' => 'Bu takıma kullanıcı eklemek için bir kayıt numarası belirtmelisiniz.',
    'ERR_DUP_NAME' => 'Takım İsmi zaten kullanılmakta, lütfen başka bir tane seçiniz.',
    'ERR_DELETE_RECORD' => 'Bu takımı silmek için kayıt numarasını belirtmelisiniz.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Hata. Seçilen takım  <b>({0})</b> . Lütfen başka bir takım seçin.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Hata. Silinmemiş Özel bir takımı olan kullanıcıyı silemezsiniz.',
    'LBL_DESCRIPTION' => 'Tanım:',
    'LBL_GLOBAL_TEAM_DESC' => 'Global Olarak Görünür',
    'LBL_INVITEE' => 'Takım Üyeleri',
    'LBL_LIST_DEPARTMENT' => 'Departman',
    'LBL_LIST_DESCRIPTION' => 'Tanım',
    'LBL_LIST_FORM_TITLE' => 'Takım Listesi',
    'LBL_LIST_NAME' => 'İsim',
    'LBL_FIRST_NAME' => 'İsim:',
    'LBL_LAST_NAME' => 'Soyisim:',
    'LBL_LIST_REPORTS_TO' => 'Rapor Edilen Kişi:',
    'LBL_LIST_TITLE' => 'Başlık',
    'LBL_MODULE_NAME' => 'Takımlar',
    'LBL_MODULE_NAME_SINGULAR' => 'Takım',
    'LBL_MODULE_TITLE' => 'Takımlar: Ana',
    'LBL_NAME' => 'Takım İsmi:',
    'LBL_NAME_2' => 'Takım İsmi(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Birincil Takım İsmi',
    'LBL_NEW_FORM_TITLE' => 'Yeni Takım',
    'LBL_PRIVATE' => 'Özel',
    'LBL_PRIVATE_TEAM_FOR' => 'Bunun için özel takım:',
    'LBL_SEARCH_FORM_TITLE' => 'Takım Arama',
    'LBL_TEAM_MEMBERS' => 'Takım Üyeleri',
    'LBL_TEAM' => 'Takımlar:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Kullanıcılar',
    'LBL_USERS' => 'Kullanıcılar',
    'LBL_REASSIGN_TEAM_TITLE' => 'Şu takım(lar) için atanmış kayıtlar bulunmaktadır: <b>{0}</b><br>Takım(ları)ı silmeden önce bu kayıtları yeni takıma tekrar atamalısınız. Yenileme için kullanılacak takım seçin.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'T',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Tekrar Belirle',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Tekrar Belirle',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Yeni takımı kullanmak için etkilenen kayıtları güncelleyerek devam edilsin mi?',
    'LBL_REASSIGN_TABLE_INFO' => '{0} tablosu yenileniyor',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'İşlem başarıyla tamamlandı.',
    'LNK_LIST_TEAM' => 'Takımlar',
    'LNK_LIST_TEAMNOTICE' => 'Takım Bildirimleri',
    'LNK_NEW_TEAM' => 'Takım Oluştur',
    'LNK_NEW_TEAM_NOTICE' => 'Takım Bildirimi Oluştur',
    'NTC_DELETE_CONFIRMATION' => 'Bu kaydı silmek istediğinizden emin misiniz?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Bu kullanıcının üyeliğini kaldırmak istediğinizden emin misiniz?',
    'LBL_EDITLAYOUT' => 'Yerleşimi Değiştir' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Takım Bazlı İzinler',
    'LBL_TBA_CONFIGURATION_DESC' => 'Takım erişimini etkinleştirin ve modüller erişimi yönetin.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Takım bazlı izinleri etkinleştir',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Etkinleştirmek için modül seçin',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Takım bazlı izinlerin etkinleştirilmesi size, Rol Yönetimi vasıtasıyla ayrı modüller için ekip ve kullanıcılara belirli erişim hakları atamanıza imkan verecektir.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Bir modül için takım bazlı izinlerin devre dışı bırakılması bu modül için takım bazlı izinlerle ilişkili tüm verileri eski haline
 döndürür, örneğin özelliği kullanan tüm İşlem Tanımları veya İşlemler dahil. Bu, o modül için "Sahip ve Seçili takım" seçeneğini kullanan tüm Rolleri ve o modüldeki kayıtlar için tüm takım bazlı izinler verilerini içerir.
 Ayrıca herhangi bir modül için takım bazlı izinleri devre dışı bıraktıktan sonra sistem önbelleğinizi temizlemek için Hızlı Onarım
 ve Yeniden Oluşturma aracını kullanmanızı öneririz.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Uyarı</strong> Bir modül için takım bazlı izinlerin devre dışı bırakılması bu modül için takım bazlı izinlerle ilişkili tüm verileri eski haline
 döndürür, örneğin özelliği kullanan tüm İşlem Tanımları veya İşlemler dahil. Bu, o modül için "Sahip ve Seçili takım" seçeneğini kullanan tüm Rolleri ve o modüldeki kayıtlar için tüm takım bazlı izinler verilerini içerir.
 Ayrıca herhangi bir modül için takım bazlı izinleri devre dışı bıraktıktan sonra sistem önbelleğinizi temizlemek için Hızlı Onarım
 ve Yeniden Oluşturma aracını kullanmanızı öneririz.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Bir modül için takım bazlı izinlerin devre dışı bırakılması bu modül için takım bazlı izinlerle ilişkili tüm verileri eski haline
 döndürür, örneğin özelliği kullanan tüm İşlem Tanımları veya İşlemler dahil. Bu, o modül için "Sahip ve Seçili takım" seçeneğini kullanan tüm Rolleri ve o modüldeki kayıtlar için tüm takım bazlı izinler verilerini içerir.
 Ayrıca herhangi bir modül için takım bazlı izinleri devre dışı bıraktıktan sonra sistem önbelleğinizi temizlemek için Hızlı Onarım
 ve Yeniden Oluşturma aracını kullanmanızı öneririz. Hızlı Onarım ve Yeniden Oluşturma aracına erişim izniniz yoksa Onarım menüsüne erişimi bulunan bir yöneticiyle iletişime geçin.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Uyarı:</strong> Bir modül için takım bazlı izinlerin devre dışı bırakılması bu modül için takım bazlı izinlerle ilişkili tüm verileri eski haline
 döndürür, örneğin özelliği kullanan tüm İşlem Tanımları veya İşlemler dahil. Bu, o modül için "Sahip ve Seçili takım" seçeneğini kullanan tüm Rolleri ve o modüldeki kayıtlar için tüm takım bazlı izinler verilerini içerir.
 Ayrıca herhangi bir modül için takım bazlı izinleri devre dışı bıraktıktan sonra sistem önbelleğinizi temizlemek için Hızlı Onarım
 ve Yeniden Oluşturma aracını kullanmanızı öneririz. Hızlı Onarım ve Yeniden Oluşturma aracına erişim izniniz yoksa Onarım menüsüne erişimi bulunan bir yöneticiyle iletişime geçin.
STR
,
);
