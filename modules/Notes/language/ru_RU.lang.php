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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Информационная панель списка заметок',

    'ERR_DELETE_RECORD' => 'Вам следует указать номер записи перед удалением контрагента.',
    'LBL_ACCOUNT_ID' => 'Контрагент:',
    'LBL_CASE_ID' => 'Обращение:',
    'LBL_CLOSE' => 'Закрыть:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID Контакта:',
    'LBL_CONTACT_NAME' => 'Контакт:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Заметки',
    'LBL_DESCRIPTION' => 'Раздел для заметок',
    'LBL_EMAIL_ADDRESS' => 'Адрес электронной почты:',
    'LBL_EMAIL_ATTACHMENT' => 'Вложение',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Вложение электронной почты для',
    'LBL_FILE_MIME_TYPE' => 'Тип MIME',
    'LBL_FILE_EXTENSION' => 'Расширение файла',
    'LBL_FILE_SOURCE' => 'Источник файла',
    'LBL_FILE_SIZE' => 'Размер файла',
    'LBL_FILE_URL' => 'Адрес файла',
    'LBL_FILENAME' => 'Вложение:',
    'LBL_LEAD_ID' => 'Предварительный контакт:',
    'LBL_LIST_CONTACT_NAME' => 'Контакт',
    'LBL_LIST_DATE_MODIFIED' => 'Последнее изменение',
    'LBL_LIST_FILENAME' => 'Вложение',
    'LBL_LIST_FORM_TITLE' => 'Список заметок',
    'LBL_LIST_RELATED_TO' => 'Относится к:',
    'LBL_LIST_SUBJECT' => 'Тема',
    'LBL_LIST_STATUS' => 'Статус',
    'LBL_LIST_CONTACT' => 'Контакт',
    'LBL_MODULE_NAME' => 'Заметки',
    'LBL_MODULE_NAME_SINGULAR' => 'Заметка',
    'LBL_MODULE_TITLE' => 'Заметки: Главная',
    'LBL_NEW_FORM_TITLE' => 'Новая заметка или вложение',
    'LBL_NEW_FORM_BTN' => 'Добавить заметку',
    'LBL_NOTE_STATUS' => 'Заметка',
    'LBL_NOTE_SUBJECT' => 'Тема заметки',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Вложения',
    'LBL_NOTE' => 'Заметка:',
    'LBL_OPPORTUNITY_ID' => 'ID Сделки:',
    'LBL_PARENT_ID' => 'Код изначальной записи:',
    'LBL_PARENT_TYPE' => 'Тип изначальной записи',
    'LBL_EMAIL_TYPE' => 'Тип электронной почты',
    'LBL_EMAIL_ID' => 'Идентификатор электронной почты',
    'LBL_PHONE' => 'Телефон:',
    'LBL_PORTAL_FLAG' => 'Отобразить в портале?',
    'LBL_EMBED_FLAG' => 'Вставить в Е-mail?',
    'LBL_PRODUCT_ID' => 'Продукт:',
    'LBL_QUOTE_ID' => 'Коммерческое предложение:',
    'LBL_RELATED_TO' => 'Относится к:',
    'LBL_SEARCH_FORM_TITLE' => 'Найти заметку',
    'LBL_STATUS' => 'Статус',
    'LBL_SUBJECT' => 'Тема:',
    'LNK_IMPORT_NOTES' => 'Импорт заметок',
    'LNK_NEW_NOTE' => 'Новая заметка или вложение',
    'LNK_NOTE_LIST' => 'Заметки',
    'LBL_MEMBER_OF' => 'Состоит в:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Ответственный (-ая)',
    'LBL_OC_FILE_NOTICE' => 'Пожалуйста, войдите в систему для просмотра файла',
    'LBL_REMOVING_ATTACHMENT' => 'Удаление вложения...',
    'ERR_REMOVING_ATTACHMENT' => 'Ошибка: не удалось удалить вложение...',
    'LBL_CREATED_BY' => 'Автор',
    'LBL_MODIFIED_BY' => 'Автор изменений',
    'LBL_SEND_ANYWAYS' => 'У этого e-mail-сообщения нет темы. Отправить/сохранить все равно?',
    'LBL_LIST_EDIT_BUTTON' => 'Правка',
    'LBL_ACTIVITIES_REPORTS' => 'Отчеты по мероприятиям',
    'LBL_PANEL_DETAILS' => 'Подробности',
    'LBL_NOTE_INFORMATION' => 'Обзор заметки',
    'LBL_MY_NOTES_DASHLETNAME' => 'Мои заметки',
    'LBL_EDITLAYOUT' => 'Правка расположения' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Имя',
    'LBL_LAST_NAME' => 'Фамилия',
    'LBL_EXPORT_PARENT_TYPE' => 'Относится к модулю',
    'LBL_EXPORT_PARENT_ID' => 'Относится к ID',
    'LBL_DATE_ENTERED' => 'Дата создания',
    'LBL_DATE_MODIFIED' => 'Дата изменения',
    'LBL_DELETED' => 'Удалено',
    'LBL_REVENUELINEITEMS' => 'Позиции продаж',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модуль {{plural_module_name}} содержит отдельные {{plural_module_name}}, которые содержат текст или вложение, относящиеся к конкретной записи. Записи {{module_name}} могут быть связаны с одной записью во многих модулях через поле "flex relate field" и с одной записью {{contacts_singular_module}}. Запись {{plural_module_name}} может содержать общий текст о записи или даже вложение, относящееся к записи. Существуют различные способы создания {{plural_module_name}} в Sugar, используя модуль {{plural_module_name}}, импорт {{plural_module_name}}, через субпанели История и т. п. После создания записи {{module_name}} можно просматривать и редактировать информацию, относящуюся к модулю {{module_name}}, в режиме просмотра записи {{plural_module_name}}. Каждая запись {{module_name}} может быть связана с другими записями Sugar, такими как {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} и многими другими.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} модуль включает контакты {{plural_module_name}}, которые содержат текст или вложение, относящееся к связанной записи.

- Чтобы редактировать поля данной записи, нажмите на самом поле или кликните по кнопке Редактировать.
- Чтобы просмотреть или изменить ссылки, ведущие к другим записям, на субпанеле, переключите левую нижнюю панель на "Просмотр данных".
- Чтобы оставлять и просматривать пользовательские комментарии, а также изменять историю в рамках одной записи в {{activitystream_singular_module}}, переключите левую нижнюю панель на "Лента активностей".
- Чтобы подписаться или добавить в Избранное данную запись, используйте иконки справа от записи.
- Дополнительные действия доступны в выпадающем меню Действий справа от кнопки Редактировать.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Чтобы создать {{module_name}}:
1. Укажите необходимые значения полей.
 - Поля, отмеченные как "Обязательные", должны быть заполнены перед сохранением.
 - Нажмите "Показать больше", чтобы отобразить дополнительные поля при необходимости.
2. Нажмите "Сохранить", чтобы завершить создание новой записи и вернуться на предыдущую страницу.',
);
