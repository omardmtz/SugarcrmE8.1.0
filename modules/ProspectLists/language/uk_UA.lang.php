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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Інформаційна панель списку списків цільових аудиторій споживачів',

  'LBL_MODULE_NAME' => 'Списки цільових аудиторій споживачів',
  'LBL_MODULE_NAME_SINGULAR' => 'Список цільових аудиторій споживачів',
  'LBL_MODULE_ID'   => 'Списки цільових аудиторій споживачів',
  'LBL_MODULE_TITLE' => 'Списки цільових аудиторій споживачів: Головна',
  'LBL_SEARCH_FORM_TITLE' => 'Пошук списків цільових аудиторій споживачів',
  'LBL_LIST_FORM_TITLE' => 'Списки цільових аудиторій споживачів',
  'LBL_PROSPECT_LIST_NAME' => 'Список цільових аудиторій споживачів:',
  'LBL_NAME' => 'Назва',
  'LBL_ENTRIES' => 'Всього записів',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Список цільових аудиторій споживачів',
  'LBL_LIST_ENTRIES' => 'Кількість адрес',
  'LBL_LIST_DESCRIPTION' => 'Опис',
  'LBL_LIST_TYPE_NO' => 'Тип',
  'LBL_LIST_END_DATE' => 'Дата завершення',
  'LBL_DATE_ENTERED' => 'Дата створення',
  'LBL_MARKETING_ID' => 'Кампанія (Id)',
  'LBL_DATE_MODIFIED' => 'Дата змінення',
  'LBL_MODIFIED' => 'Змінено',
  'LBL_CREATED' => 'Створено',
  'LBL_TEAM' => 'Команда',
  'LBL_ASSIGNED_TO' => 'Відповідальний (-а)',
  'LBL_DESCRIPTION' => 'Опис',
  'LNK_NEW_CAMPAIGN' => 'Створити маркетингову кампанію',
  'LNK_CAMPAIGN_LIST' => 'Маркетингові кампанії',
  'LNK_NEW_PROSPECT_LIST' => 'Створити список цільових аудиторій споживачів',
  'LNK_PROSPECT_LIST_LIST' => 'Переглянути списки цільових аудиторій споживачів',
  'LBL_MODIFIED_BY' => 'Змінено',
  'LBL_CREATED_BY' => 'Створено',
  'LBL_DATE_CREATED' => 'Дата створення',
  'LBL_DATE_LAST_MODIFIED' => 'Дата зміни',
  'LNK_NEW_PROSPECT' => 'Створити цільову аудиторію споживачів',
  'LNK_PROSPECT_LIST' => 'Цільові аудиторії споживачів',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Списки цільових аудиторій споживачів',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакти',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Інтереси',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Цільові аудиторії споживачів',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Контрагенти',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Маркетингові кампанії',
  'LBL_COPY_PREFIX' =>'Копія',
  'LBL_USERS_SUBPANEL_TITLE' =>'Користувачі',
  'LBL_TYPE' => 'Тип',
  'LBL_LIST_TYPE' => 'Тип',
  'LBL_LIST_TYPE_LIST_NAME'=>'Тип',
  'LBL_NEW_FORM_TITLE'=>'Новий список цільових аудиторій споживачів',
  'LBL_MARKETING_NAME'=>'Назва кампанії',
  'LBL_MARKETING_MESSAGE'=>'Повідомлення E-пошта-розсилки',
  'LBL_DOMAIN_NAME'=>'Доменне ім&#39;я',
  'LBL_DOMAIN'=>'Немає email-повідомлень для домену',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Назва',
	'LBL_MORE_DETAIL' => 'Детальніше' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} містить список контактів і контрагентів, яких Ви хочете включити в маркетингову кампанію або виключити з неї {{campaigns_singular_module}}. {{plural_module_name}} можуть містити будь-яку кількість і будь-яку групу Цільових контактів, {{contacts_module}}, {{leads_module}}, користувачів, і {{accounts_module}}. Цільові контакти можуть бути згруповані в {{module_name}} згідно набору зумовлених критеріїв, таких як вік, географічне місце розташування або звички. {{plural_module_name}} використовуються в масових e-mail кампаніях {{campaigns_module}}, які можуть бути налаштовані в {{campaigns_module}} модулі.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} містить список контактів і контрагентів, яких Ви хочете включити в маркетингову кампанію або виключити з неї {{campaigns_singular_module}}.

- Щоб редагувати поля цього запису, клікніть на самому полі або натисніть кнопку Редагувати.
- Щоб переглянути або змінити посилання, що ведуть до інших записів, на субпанелі, включаючи {{campaigns_singular_module}} одержувачів, переведіть ліву нижню панель на "Перегляд даних".
- Щоб залишати і переглядати коментарі користувачів і змінювати історію в рамках одного запису в {{activitystream_singular_module}}, переведіть ліву нижню панель на "Стрічка активностей".
- Щоб підписатися або додати у Вибране цей запис, використовуйте іконку праворуч від запису.
- Додаткові дії доступні у випадаючому меню Дій праворуч від кнопки редагування.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Модуль {{module_name}} містить список контактів і контрагентів, яких ви хочете включити в маркетингову кампанію або виключити з неї {{campaigns_singular_module}}.

Щоб створити {{module_name}}, виконайте такі дії:
1. Вкажіть потрібні значення полів.
  - Перед збереженням потрібно заповнити поля з позначкою "Обов&#39;язково".
  - Щоб переглянути додаткові поля, натисніть "Показати більше".
2. Натисніть "Зберегти", щоб завершити створення нового запису й повернутися до попередньої сторінки.
3. Після збереження, використовуйте субпанелі, доступні в режимі перегляду цільового запису, щоб додавати одержувачів {{campaigns_singular_module}}.',
);
