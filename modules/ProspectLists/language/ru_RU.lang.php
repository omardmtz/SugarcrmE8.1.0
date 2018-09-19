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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Информационная панель списка целевых списков',

  'LBL_MODULE_NAME' => 'Целевые списки',
  'LBL_MODULE_NAME_SINGULAR' => 'Целевой список',
  'LBL_MODULE_ID'   => 'Целевые списки',
  'LBL_MODULE_TITLE' => 'Целевые списки: Главная',
  'LBL_SEARCH_FORM_TITLE' => 'Поиск целевых списков',
  'LBL_LIST_FORM_TITLE' => 'Целевые списки',
  'LBL_PROSPECT_LIST_NAME' => 'Целевой список:',
  'LBL_NAME' => 'Название',
  'LBL_ENTRIES' => 'Количество адресов',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Целевой список',
  'LBL_LIST_ENTRIES' => 'Количество адресов',
  'LBL_LIST_DESCRIPTION' => 'Описание',
  'LBL_LIST_TYPE_NO' => 'Тип',
  'LBL_LIST_END_DATE' => 'Дата окончания',
  'LBL_DATE_ENTERED' => 'Дата создания',
  'LBL_MARKETING_ID' => 'Id кампании',
  'LBL_DATE_MODIFIED' => 'Дата изменения',
  'LBL_MODIFIED' => 'Изменено',
  'LBL_CREATED' => 'Создано',
  'LBL_TEAM' => 'Команда',
  'LBL_ASSIGNED_TO' => 'Ответственный (-ая)',
  'LBL_DESCRIPTION' => 'Описание',
  'LNK_NEW_CAMPAIGN' => 'Создать маркетинговую кампанию',
  'LNK_CAMPAIGN_LIST' => 'Маркетинговые кампании',
  'LNK_NEW_PROSPECT_LIST' => 'Новый целевой список',
  'LNK_PROSPECT_LIST_LIST' => 'Целевые списки',
  'LBL_MODIFIED_BY' => 'Автор изменений',
  'LBL_CREATED_BY' => 'Создано',
  'LBL_DATE_CREATED' => 'Дата создания',
  'LBL_DATE_LAST_MODIFIED' => 'Дата изменения',
  'LNK_NEW_PROSPECT' => 'Создать адресата',
  'LNK_PROSPECT_LIST' => 'Адресаты',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Целевые списки',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакты',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Предварительные контакты',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Адресаты',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Клиенты',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Маркетинговые кампании',
  'LBL_COPY_PREFIX' =>'Копия',
  'LBL_USERS_SUBPANEL_TITLE' =>'Пользователи',
  'LBL_TYPE' => 'Тип',
  'LBL_LIST_TYPE' => 'Тип',
  'LBL_LIST_TYPE_LIST_NAME'=>'Тип',
  'LBL_NEW_FORM_TITLE'=>'Новый целевой список',
  'LBL_MARKETING_NAME'=>'Название кампании',
  'LBL_MARKETING_MESSAGE'=>'Сообщение E-mail-рассылки',
  'LBL_DOMAIN_NAME'=>'Доменное имя',
  'LBL_DOMAIN'=>'Нет e-mail-сообщений для домена',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Название',
	'LBL_MORE_DETAIL' => 'Детальнее' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} содержит список контактов и контрагентов, которых Вы хотите включить или исключить из массовой {{campaigns_singular_module}}. {{plural_module_name}} могут содержать любое количество и любую группу Целевых контактов, {{contacts_module}}, {{leads_module}}, Пользователей, и {{accounts_module}}. Целевые контакты могут быть сгруппированы в {{module_name}} согласно набору предопределенных критериев, таких как возраст, географическое месторасположение или привычки. {{plural_module_name}} используются в массовых e-mail кампаниях {{campaigns_module}}, которые могут быть настроены в модуле {{campaigns_module}}.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} содержит список контактов и контрагентов, которых Вы хотите включить в маркетинговую кампанию или исключить из нее {{campaigns_singular_module}}.

- Чтобы редактировать поля этой записи, кликните на самом поле или нажмите кнопку Редактировать.
- Чтобы просмотреть или изменить ссылки, ведущие к другим записям, на субпанеле, включая  {{campaigns_singular_module}}  получателей, переключите левую нижнюю панель на "Просмотр данных".
- Чтобы оставлять и просматривать пользовательские комментарии и изменять историю в рамках одной записи в {{activitystream_singular_module}}, переключите левую нижнюю панель на "Лента активностей". 
- Чтобы подписаться или добавить в Избранное эту запись, используйте иконку справа от записи.
- Дополнительные действия доступны в выпадающем меню Действий справа от кнопки Редактировать.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} содержит список контактов и контрагентов, которых Вы хотите включить в маркетинговую кампанию или исключить из нее {{campaigns_singular_module}}.

Чтобы создать {{module_name}}:
1. Укажите необходимые значения полей.
 - Поля, отмеченные как "Обязательные", должны быть заполнены перед сохранением.
 - Нажмите "Показать больше", чтобы отобразить дополнительные поля при необходимости.
2. Нажмите "Сохранить", чтобы завершить создание новой записи и вернуться на предыдущую страницу.
3. После сохранения на субпанелях, доступных в режиме просмотра целевой записи, Вы можете добавить пользователей {{campaigns_singular_module}}.',
);
