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
  'LBL_TASKS_LIST_DASHBOARD' => 'Електронно табло със списък на задачите',

  'LBL_MODULE_NAME' => 'Задачи',
  'LBL_MODULE_NAME_SINGULAR' => 'Задача',
  'LBL_TASK' => 'Задачи',
  'LBL_MODULE_TITLE' => 'Задачи',
  'LBL_SEARCH_FORM_TITLE' => 'Търсене в модул "Задачи"',
  'LBL_LIST_FORM_TITLE' => 'Списък със задачи',
  'LBL_NEW_FORM_TITLE' => 'Създаване на задача',
  'LBL_NEW_FORM_SUBJECT' => 'Относно:',
  'LBL_NEW_FORM_DUE_DATE' => 'Крайна дата:',
  'LBL_NEW_FORM_DUE_TIME' => 'Краен час:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Затвори',
  'LBL_LIST_SUBJECT' => 'Относно',
  'LBL_LIST_CONTACT' => 'Контакт',
  'LBL_LIST_PRIORITY' => 'Степен на важност',
  'LBL_LIST_RELATED_TO' => 'Свързано с',
  'LBL_LIST_DUE_DATE' => 'Падежна дата',
  'LBL_LIST_DUE_TIME' => 'Краен час',
  'LBL_SUBJECT' => 'Относно:',
  'LBL_STATUS' => 'Статус:',
  'LBL_DUE_DATE' => 'Крайна дата:',
  'LBL_DUE_TIME' => 'Краен час:',
  'LBL_PRIORITY' => 'Степен на важност:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Крайна дата и час:',
  'LBL_START_DATE_AND_TIME' => 'Начална дата и час:',
  'LBL_START_DATE' => 'Начална дата:',
  'LBL_LIST_START_DATE' => 'Начална дата',
  'LBL_START_TIME' => 'Начален час:',
  'LBL_LIST_START_TIME' => 'Начален час',
  'DATE_FORMAT' => '(гггг-мм-дд)',
  'LBL_NONE' => 'няма',
  'LBL_CONTACT' => 'Контакт:',
  'LBL_EMAIL_ADDRESS' => 'Електронна поща:',
  'LBL_PHONE' => 'Телефон:',
  'LBL_EMAIL' => 'Електронна поща:',
  'LBL_DESCRIPTION_INFORMATION' => 'Допълнителна информация',
  'LBL_DESCRIPTION' => 'Описание:',
  'LBL_NAME' => 'Име:',
  'LBL_CONTACT_NAME' => 'Контакт ',
  'LBL_LIST_COMPLETE' => 'Приключена:',
  'LBL_LIST_STATUS' => 'Статус',
  'LBL_DATE_DUE_FLAG' => 'Без крайна дата',
  'LBL_DATE_START_FLAG' => 'Без начална дата',
  'ERR_DELETE_RECORD' => 'Трябва да въведете номер на записа, за да изтриете този контакт.',
  'ERR_INVALID_HOUR' => 'Моля, въведете час между 0 и 24',
  'LBL_DEFAULT_PRIORITY' => 'Средна',
  'LBL_LIST_MY_TASKS' => 'Моите текущи задачи',
  'LNK_NEW_TASK' => 'Създаване на задача',
  'LNK_TASK_LIST' => 'Списък със задачи',
  'LNK_IMPORT_TASKS' => 'Импортиране на задачи',
  'LBL_CONTACT_FIRST_NAME'=>'Име на контакта',
  'LBL_CONTACT_LAST_NAME'=>'Фамилия на контакта',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Отговорник',
  'LBL_ASSIGNED_TO_NAME'=>'Отговорник:',
  'LBL_LIST_DATE_MODIFIED' => 'Модифицирано на',
  'LBL_CONTACT_ID' => 'Контакт:',
  'LBL_PARENT_ID' => 'Родителско ID:',
  'LBL_CONTACT_PHONE' => 'Телефон за контакт:',
  'LBL_PARENT_NAME' => 'Родителски тип:',
  'LBL_ACTIVITIES_REPORTS' => 'Справка за дейности',
  'LBL_EDITLAYOUT' => 'Редактиране на подредби' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Задача',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Бележки',
  'LBL_REVENUELINEITEMS' => 'Приходни позиции',
  //For export labels
  'LBL_DATE_DUE' => 'Крайна дата',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Отговорник',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Отговорник',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Модифицирано от (ID)',
  'LBL_EXPORT_CREATED_BY' => 'Създадено от (ID)',
  'LBL_EXPORT_PARENT_TYPE' => 'Свързан с модул',
  'LBL_EXPORT_PARENT_ID' => 'Свързано с',
  'LBL_TASK_CLOSE_SUCCESS' => 'Задачата е приключено успешно.',
  'LBL_ASSIGNED_USER' => 'Отговорник',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Бележки',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модулът {{plural_module_name}} съдържа записи за действия, които трябва да бъдат приключени от съответните отговорници преди зададена крайна дата. Отделните {{plural_module_name}} могат да бъдат свързвани със записи в почти всички останали модули в системата, както и да бъдат асоциирани с отделен {{contacts_singular_module}}. Съществуват различни начини да създадете {{plural_module_name}} в SugarCRM, като за целта можете да използвате опциите на модула {{plural_module_name}}, да дублирате съществуващ запис, да импортирате {{plural_module_name}} и други. След като дадена {{module_name}} е въведена в системата, можете да разгледате и редактирате информацията за нея през изгледа „Детайли за записа“. В зависимост от данните въведени за конкретна {{module_name}}, тя може да бъде управлявана и през модул Календар в SugarCRM. Всяка {{module_name}} може да бъде свързана с други записи в Sugar, в това число {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} и много други.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Модулът {{plural_module_name}} съдържа записи за действия, които трябва да бъдат приключени от съответните отговорници преди зададена крайна дата.

- Редактирайте текущия запис като натиснете конкретно поле или използвате бутона „Редактирай“.
- Разгледайте или модифицирайте връзките с други записи в системата, като за целта визуализирате панела „Данни“.
- Поставете коментари към текущия запис и разгледайте история на извършените промени свързани с него, като преминете в панела „Хронология“.
- Следвайте или харесайте текущия запис като използвате иконите, намиращи се в дясно от името му.
- Можете да извършвате допълнителни действия със записа като използвате падащото меню в дясно на бутона „Редактирай“.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Модулът {{plural_module_name}} съдържа записи за действия, които трябва да бъдат приключени от съответните отговорници преди зададена крайна дата.

За да създадете нов запис в модул {{module_name}}:
1. Въведете стойности в отделните полета.
 - Изисква се полетата, маркирани като "Задължителни", да имат зададена стойност преди да се пристъпи към съхраняване на записа.
 - Натиснете върху "Покажи повече", за да бъдат визуализирани допълнителни полета ако е необходимо.
2. Натиснете "Съхрани", за да запазите новия запис и да се върнете на предходната страница.',

);
