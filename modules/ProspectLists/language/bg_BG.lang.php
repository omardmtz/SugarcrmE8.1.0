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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Електронно табло със списък на Целеви групи',

  'LBL_MODULE_NAME' => 'Целеви групи',
  'LBL_MODULE_NAME_SINGULAR' => 'Целева група',
  'LBL_MODULE_ID'   => 'Целеви групи',
  'LBL_MODULE_TITLE' => 'Целеви групи',
  'LBL_SEARCH_FORM_TITLE' => 'Търсене в модул "Целеви групи"',
  'LBL_LIST_FORM_TITLE' => 'Списък с целеви групи',
  'LBL_PROSPECT_LIST_NAME' => 'Име',
  'LBL_NAME' => 'Име',
  'LBL_ENTRIES' => 'Брой записи в групата:',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Целева група',
  'LBL_LIST_ENTRIES' => 'Брой записи в групата',
  'LBL_LIST_DESCRIPTION' => 'Описание',
  'LBL_LIST_TYPE_NO' => 'Тип',
  'LBL_LIST_END_DATE' => 'Крайна дата',
  'LBL_DATE_ENTERED' => 'Създадено на',
  'LBL_MARKETING_ID' => 'Послание',
  'LBL_DATE_MODIFIED' => 'Модифицирано на',
  'LBL_MODIFIED' => 'Модифицирано от',
  'LBL_CREATED' => 'Създадено от',
  'LBL_TEAM' => 'Екип',
  'LBL_ASSIGNED_TO' => 'Отговорник',
  'LBL_DESCRIPTION' => 'Описание',
  'LNK_NEW_CAMPAIGN' => 'Създаване на кампания',
  'LNK_CAMPAIGN_LIST' => 'Кампании',
  'LNK_NEW_PROSPECT_LIST' => 'Създаване на целева група',
  'LNK_PROSPECT_LIST_LIST' => 'Списък с целеви групи',
  'LBL_MODIFIED_BY' => 'Модифицирано от',
  'LBL_CREATED_BY' => 'Създадено от',
  'LBL_DATE_CREATED' => 'Създадено на:',
  'LBL_DATE_LAST_MODIFIED' => 'Модифицирано на:',
  'LNK_NEW_PROSPECT' => 'Създаване на целеви клиент',
  'LNK_PROSPECT_LIST' => 'Целеви клиенти',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Целеви групи',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакти',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Потенциални клиенти',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Целеви клиенти',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Организации',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Кампании',
  'LBL_COPY_PREFIX' =>'Копие на',
  'LBL_USERS_SUBPANEL_TITLE' =>'Потребители',
  'LBL_TYPE' => 'Тип',
  'LBL_LIST_TYPE' => 'Тип',
  'LBL_LIST_TYPE_LIST_NAME'=>'Тип',
  'LBL_NEW_FORM_TITLE'=>'Нова целева група',
  'LBL_MARKETING_NAME'=>'Маркетингово послание',
  'LBL_MARKETING_MESSAGE'=>'Изращане на маркетингово съобщение',
  'LBL_DOMAIN_NAME'=>'Име на домейн',
  'LBL_DOMAIN'=>'Блокиран домейн:',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Име',
	'LBL_MORE_DETAIL' => 'Детайли' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Записите в модула {{module_name}} съдържат списъци с организации или лица, които могат да бъдат включвани в маркетинговата комуникация през модул {{campaigns_singular_module}}. Отделните {{plural_module_name}} могат да съдържат комбинация от {{contacts_module}}, {{leads_module}}, Потребители и {{accounts_module}}. Групирането на целевите клиенти може да се извършва в {{module_name}} според предефинирани критерии, в това число възрастова група, географски признак или история на сделките. {{plural_module_name}} се използват в маркетинга с масови имейли {{campaigns_module}}, които могат да бъдат конфигурирани в модула {{campaigns_module}} module.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Записите в модула {{module_name}} съдържат списъци с организации или лица, които могат да бъдат включвани в масовата маркетингова комуникация през модул {{campaigns_singular_module}}.

- Редактирайте текущия запис като натиснете конкретно поле или използвате бутона „Редактирай“.
- Разгледайте или модифицирайте връзките с други записи, включително получателите {{campaigns_singular_module}}, като за целта визуализирате панела „Преглед на данните“.
- Оставете коментар и разгледайте коментарите на други потребители и запишете историята на промените в {{activitystream_singular_module}} като преминете в панела „Хронология“.
- Следвайте или харесайте текущия запис като използвате иконите, намиращи се в дясно от името му.
- Можете да извършвате допълнителни действия със записа като използвате падащото меню в дясно на бутона „Редактирай“.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Записите в модула {{module_name}} съдържат списъци с организации или лица, които могат да бъдат включвани или изключвани от масовата маркетингова комуникация през модул {{campaigns_singular_module}}.

За да създадете нов запис в модул {{module_name}}:
1. Въведете стойности в отделните полета.
 - Изисква се полетата, маркирани като "Задължителни", да имат зададена стойност преди да се пристъпи към съхраняване на записа.
 - Натиснете върху "Покажи повече", за да бъдат визуализирани допълнителни полета ако е необходимо.
2. Натиснете "Съхрани", за да запазите новия запис и да се върнете на предходната страница.
 3. След съхраняване на записа използвайте панелите за Целеви клиенти, Организации, Контакти, Потенциални клиенти и потребители, за да добавите записи {{campaigns_singular_module}} към целевата група.',
);
