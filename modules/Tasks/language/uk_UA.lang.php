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
  'LBL_TASKS_LIST_DASHBOARD' => 'Інформаційна панель списку задач',

  'LBL_MODULE_NAME' => 'Задачі',
  'LBL_MODULE_NAME_SINGULAR' => 'Задача',
  'LBL_TASK' => 'Задачі:',
  'LBL_MODULE_TITLE' => 'Задачі: Головна',
  'LBL_SEARCH_FORM_TITLE' => 'Пошук задач',
  'LBL_LIST_FORM_TITLE' => 'Список задач',
  'LBL_NEW_FORM_TITLE' => 'Створити задачу',
  'LBL_NEW_FORM_SUBJECT' => 'Тема:',
  'LBL_NEW_FORM_DUE_DATE' => 'Дата завершення:',
  'LBL_NEW_FORM_DUE_TIME' => 'Час завершення:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Закрити',
  'LBL_LIST_SUBJECT' => 'Тема',
  'LBL_LIST_CONTACT' => 'Контакт',
  'LBL_LIST_PRIORITY' => 'Пріоритет',
  'LBL_LIST_RELATED_TO' => 'Пов&#39;язаний з',
  'LBL_LIST_DUE_DATE' => 'Дата завершення',
  'LBL_LIST_DUE_TIME' => 'Час завершення',
  'LBL_SUBJECT' => 'Тема:',
  'LBL_STATUS' => 'Статус:',
  'LBL_DUE_DATE' => 'Дата завершення:',
  'LBL_DUE_TIME' => 'Час завершення:',
  'LBL_PRIORITY' => 'Пріоритет:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Дата та час завершення:',
  'LBL_START_DATE_AND_TIME' => 'Дата та час початку:',
  'LBL_START_DATE' => 'Дата початку',
  'LBL_LIST_START_DATE' => 'Дата початку',
  'LBL_START_TIME' => 'Час початку:',
  'LBL_LIST_START_TIME' => 'Час початку',
  'DATE_FORMAT' => '(рррр-мм-дд)',
  'LBL_NONE' => 'Не визначено',
  'LBL_CONTACT' => 'Контакт:',
  'LBL_EMAIL_ADDRESS' => 'Адреса Email:',
  'LBL_PHONE' => 'Телефон:',
  'LBL_EMAIL' => 'Адреса Email:',
  'LBL_DESCRIPTION_INFORMATION' => 'Опис інформації',
  'LBL_DESCRIPTION' => 'Опис:',
  'LBL_NAME' => 'Назва:',
  'LBL_CONTACT_NAME' => 'Контактна особа',
  'LBL_LIST_COMPLETE' => 'Завершено:',
  'LBL_LIST_STATUS' => 'Статус',
  'LBL_DATE_DUE_FLAG' => 'Немає дати завершення',
  'LBL_DATE_START_FLAG' => 'Немає дати початку',
  'ERR_DELETE_RECORD' => 'Необхідно вказати номер запису перед видаленням.',
  'ERR_INVALID_HOUR' => 'Будь ласка, введіть коректне значення годин від 0 до 24',
  'LBL_DEFAULT_PRIORITY' => 'Середній',
  'LBL_LIST_MY_TASKS' => 'Мої відкриті задачі',
  'LNK_NEW_TASK' => 'Створити задачу',
  'LNK_TASK_LIST' => 'Переглянути задачі',
  'LNK_IMPORT_TASKS' => 'Імпорт задач',
  'LBL_CONTACT_FIRST_NAME'=>'Ім&#39;я контакту',
  'LBL_CONTACT_LAST_NAME'=>'Прізвище контакту',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Відповідальний (-а)',
  'LBL_ASSIGNED_TO_NAME'=>'Відповідальний (-а)',
  'LBL_LIST_DATE_MODIFIED' => 'Дата змінення',
  'LBL_CONTACT_ID' => 'Контакт (ID):',
  'LBL_PARENT_ID' => 'ID батьківського запису:',
  'LBL_CONTACT_PHONE' => 'Телефон контакту:',
  'LBL_PARENT_NAME' => 'Тип батьківського запису:',
  'LBL_ACTIVITIES_REPORTS' => 'Звіт за активностями',
  'LBL_EDITLAYOUT' => 'Редагувати розташування' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Огляд',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Примітки',
  'LBL_REVENUELINEITEMS' => 'Доходи за продукти',
  //For export labels
  'LBL_DATE_DUE' => 'Дата завершення:',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Відповідальний користувач',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Відповідальний (-а) (ID)',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Змінено (ID)',
  'LBL_EXPORT_CREATED_BY' => 'Створено (ID)',
  'LBL_EXPORT_PARENT_TYPE' => 'Пов&#39;язаний з модулем',
  'LBL_EXPORT_PARENT_ID' => 'ID батьківського запису',
  'LBL_TASK_CLOSE_SUCCESS' => 'Задача успішно закрита.',
  'LBL_ASSIGNED_USER' => 'Відповідальний (-а)',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Примітки',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модуль {{plural_module_name}} містить перелік дій та активностей різного типу, які потрібно виконати. Записи модуля {{module_name}} можуть бути пов&#39;язані з одним записом у багатьох модулях через поле "flex relate", а також можуть бути пов&#39;язані з одним модулем {{contacts_singular_module}}. Існує безліч способів створення {{plural_module_name}} в Sugar, використовуючи модуль {{plural_module_name}}, дублювання, імпорт даних модуля {{plural_module_name}} і т. д. Після створення запису {{module_name}}можна переглядати та редагувати інформацію, що стосується запису {{module_name}}, в режимі перегляду запису {{plural_module_name}}. Залежно від даних запису {{module_name}} також можна переглядати та редагувати інформацію запису {{module_name}} за допомогою модуля Календар. Кожен запис {{module_name}} може бути пов&#39;язаний з іншими записами Sugar, такими як {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} і багатьма іншими.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} модуль використовується, щоб відстежувати і керувати помилками, пов&#39;язаними з продуктом або послугою, про які повідомляють ваші клієнти.

- Щоб редагувати поля даного запису, натисніть на самому полі або клікніть на кнопку Редагувати.
- Щоб переглянути або змінити посилання, що ведуть до інших записів, на субпанеле перемкніть ліву нижню панель на "Перегляд даних".
- Щоб залишати і переглядати коментарі користувача, а також змінювати історію в рамках одного запису в {{activitystream_singular_module}}, переведіть ліву нижню панель на "Стручку активності".
- Щоб підписатися або додати у Вибране даний запис, використовуйте іконки праворуч від запису.
- Додаткові дії доступні у випадаючому меню Кнопка Дій знаходиться праворуч від кнопки Редагувати.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Модуль {{plural_module_name}} містить перелік змінюваних дій, планів чи інших активностей, які потрібно виконати.

Щоб створити {{module_name}}, виконайте такі дії:
1. Вкажіть потрібні значення полів.
 - Перед збереженням потрібно заповнити поля з позначкою "Обов&#39;язково".
 - Щоб переглянути додаткові поля, натисніть "Показати більше".
2. Натисніть кнопку "Зберегти", щоб завершити створення нового запису й повернутися до попередньої сторінки.',

);
