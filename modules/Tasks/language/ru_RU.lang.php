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
  'LBL_TASKS_LIST_DASHBOARD' => 'Информационная панель списка задач',

  'LBL_MODULE_NAME' => 'Задачи',
  'LBL_MODULE_NAME_SINGULAR' => 'Задача',
  'LBL_TASK' => 'Задачи:',
  'LBL_MODULE_TITLE' => 'Задачи: Главная',
  'LBL_SEARCH_FORM_TITLE' => 'Найти задачу',
  'LBL_LIST_FORM_TITLE' => 'Список задач',
  'LBL_NEW_FORM_TITLE' => 'Новая задача',
  'LBL_NEW_FORM_SUBJECT' => 'Тема:',
  'LBL_NEW_FORM_DUE_DATE' => 'Дата выполнения:',
  'LBL_NEW_FORM_DUE_TIME' => 'Время выполнения:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Закрыть',
  'LBL_LIST_SUBJECT' => 'Тема',
  'LBL_LIST_CONTACT' => 'Контакт',
  'LBL_LIST_PRIORITY' => 'Приоритет',
  'LBL_LIST_RELATED_TO' => 'Относится к',
  'LBL_LIST_DUE_DATE' => 'Дата выполнения',
  'LBL_LIST_DUE_TIME' => 'Время выполнения:',
  'LBL_SUBJECT' => 'Тема:',
  'LBL_STATUS' => 'Статус:',
  'LBL_DUE_DATE' => 'Дата выполнения',
  'LBL_DUE_TIME' => 'Время выполнения:',
  'LBL_PRIORITY' => 'Приоритет:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Дата и время выполнения:',
  'LBL_START_DATE_AND_TIME' => 'Дата и время начала:',
  'LBL_START_DATE' => 'Дата начала:',
  'LBL_LIST_START_DATE' => 'Дата начала',
  'LBL_START_TIME' => 'Время начала:',
  'LBL_LIST_START_TIME' => 'Время начала',
  'DATE_FORMAT' => '(гггг-мм-дд)',
  'LBL_NONE' => 'Нет',
  'LBL_CONTACT' => 'Контакт:',
  'LBL_EMAIL_ADDRESS' => 'Адрес электронной почты:',
  'LBL_PHONE' => 'Телефон:',
  'LBL_EMAIL' => 'Адрес электронной почты:',
  'LBL_DESCRIPTION_INFORMATION' => 'Описание',
  'LBL_DESCRIPTION' => 'Описание:',
  'LBL_NAME' => 'Название:',
  'LBL_CONTACT_NAME' => 'Контактное лицо:',
  'LBL_LIST_COMPLETE' => 'Завершено:',
  'LBL_LIST_STATUS' => 'Статус',
  'LBL_DATE_DUE_FLAG' => 'Нет даты выполнения',
  'LBL_DATE_START_FLAG' => 'Нет даты начала',
  'ERR_DELETE_RECORD' => 'Вы должны указать номер записи перед удалением контакта.',
  'ERR_INVALID_HOUR' => 'Пожалуйста, введите значение часа между 0 и 24',
  'LBL_DEFAULT_PRIORITY' => 'Средний',
  'LBL_LIST_MY_TASKS' => 'Мои открытые задачи',
  'LNK_NEW_TASK' => 'Новая задача',
  'LNK_TASK_LIST' => 'Задачи',
  'LNK_IMPORT_TASKS' => 'Импорт задач',
  'LBL_CONTACT_FIRST_NAME'=>'Имя контакта',
  'LBL_CONTACT_LAST_NAME'=>'Фамилия контакта',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Ответственный (-ая)',
  'LBL_ASSIGNED_TO_NAME'=>'Ответственный (-ая):',
  'LBL_LIST_DATE_MODIFIED' => 'Дата изменения',
  'LBL_CONTACT_ID' => 'ID Контакта:',
  'LBL_PARENT_ID' => 'Код изначальной записи:',
  'LBL_CONTACT_PHONE' => 'Телефон контакта:',
  'LBL_PARENT_NAME' => 'Тип изначальной записи:',
  'LBL_ACTIVITIES_REPORTS' => 'Отчеты по мероприятиям',
  'LBL_EDITLAYOUT' => 'Правка расположения' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Описание задачи',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Заметки',
  'LBL_REVENUELINEITEMS' => 'Доход по продуктам',
  //For export labels
  'LBL_DATE_DUE' => 'Дата окончания',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Ответственный пользователь',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Ответственный (ID)',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Изменено (ID)',
  'LBL_EXPORT_CREATED_BY' => 'Создано (ID)',
  'LBL_EXPORT_PARENT_TYPE' => 'Относится к модулю',
  'LBL_EXPORT_PARENT_ID' => 'Относится к ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Задача выполнена успешно',
  'LBL_ASSIGNED_USER' => 'За кем ответственный (-ая)',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Заметки',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модуль {{plural_module_name}} содержит перечень действий и активностей разного типа, которые должны быть завершены. Записи модуля {{module_name}} могут быть связаны с одной записью во многих модулях через поле "flex relate", а также могут быть связаны с одним модулем {{contacts_singular_module}}. Существует множество способов создания {{plural_module_name}} в Sugar, используя модуль {{plural_module_name}}, дублирование, импорт {{plural_module_name}} и т. п. После создания записи {{module_name}} можно просматривать и редактировать информацию, касающуюся {{module_name}}, в режиме просмотра записи {{plural_module_name}}. В зависимости от деталей {{module_name}} Вы также сможете просматривать и редактировать информацию {{module_name}} через модуль Календарь. Каждая запись {{module_name}} может быть связана с другими записями Sugar, такими как {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, и многими другими.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} модуль содержит перечень действий и активностей разного типа, которые должны быть завершены- Чтобы редактировать поля данной записи, нажмите на самом поле или кликните по кнопке Редактировать.- Чтобы просмотреть или изменить ссылки, ведущие к другим записям, на субпанеле, переключите левую нижнюю панель на "Просмотр данных".- Чтобы оставлять и просматривать пользовательские комментарии, а также изменять историю в рамках одной записи в {{activitystream_singular_module}}, переключите левую нижнюю панель на "Лента активностей".- Чтобы подписаться или добавить в Избранное данную запись, используйте иконки справа от записи.- Дополнительные действия доступны в выпадающем меню Действий справа от кнопки Редактировать.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Модуль {{plural_module_name}} содержит перечень действий и активностей разного типа, которые должны быть завершены.

Чтобы создать {{module_name}}: 
1. Укажите необходимые значения полей. 
- Поля, отмеченные как "Обязательные", должны быть заполнены перед сохранением. 
- Нажмите "Показать больше", чтобы отобразить дополнительные поля при необходимости.
2. Нажмите "Сохранить", чтобы завершить создание новой записи и вернуться на предыдущую страницу.',

);
