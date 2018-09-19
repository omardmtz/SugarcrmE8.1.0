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
    'LBL_NOTES_LIST_DASHBOARD' => 'Електронно табло със списък на бележките',

    'ERR_DELETE_RECORD' => 'Трябва да определите номер, за да изтриете този запис.',
    'LBL_ACCOUNT_ID' => 'Идентификатор на организацията:',
    'LBL_CASE_ID' => 'Казус:',
    'LBL_CLOSE' => 'Затвори:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Контакт:',
    'LBL_CONTACT_NAME' => 'Контакт:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Бележки',
    'LBL_DESCRIPTION' => 'Описание',
    'LBL_EMAIL_ADDRESS' => 'Електронна поща:',
    'LBL_EMAIL_ATTACHMENT' => 'Приложения',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Прикачен файл към писмото за',
    'LBL_FILE_MIME_TYPE' => 'Mime тип',
    'LBL_FILE_EXTENSION' => 'Разширение на файла',
    'LBL_FILE_SOURCE' => 'Източник на файла',
    'LBL_FILE_SIZE' => 'Размер на файла',
    'LBL_FILE_URL' => 'Адрес на файла (URL)',
    'LBL_FILENAME' => 'Приложение:',
    'LBL_LEAD_ID' => 'Потенциален клиент:',
    'LBL_LIST_CONTACT_NAME' => 'Контакт',
    'LBL_LIST_DATE_MODIFIED' => 'Последно модифициран',
    'LBL_LIST_FILENAME' => 'Приложение',
    'LBL_LIST_FORM_TITLE' => 'Списък с бележки',
    'LBL_LIST_RELATED_TO' => 'Свързано с',
    'LBL_LIST_SUBJECT' => 'Относно',
    'LBL_LIST_STATUS' => 'Статус',
    'LBL_LIST_CONTACT' => 'Контакт',
    'LBL_MODULE_NAME' => 'Бележки',
    'LBL_MODULE_NAME_SINGULAR' => 'Бележка',
    'LBL_MODULE_TITLE' => 'Бележки:',
    'LBL_NEW_FORM_TITLE' => 'Създаване на бележка',
    'LBL_NEW_FORM_BTN' => 'Добави бележка',
    'LBL_NOTE_STATUS' => 'Описание',
    'LBL_NOTE_SUBJECT' => 'Относно:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Приложения',
    'LBL_NOTE' => 'Бележки:',
    'LBL_OPPORTUNITY_ID' => 'Възможност:',
    'LBL_PARENT_ID' => 'Родителско ID:',
    'LBL_PARENT_TYPE' => 'Parent тип',
    'LBL_EMAIL_TYPE' => 'Тип на електронната поща',
    'LBL_EMAIL_ID' => 'Идентификатор на електронната поща',
    'LBL_PHONE' => 'Телефон:',
    'LBL_PORTAL_FLAG' => 'Визуализиране в портала?',
    'LBL_EMBED_FLAG' => 'Прикрепи към електронно писмо?',
    'LBL_PRODUCT_ID' => 'ID на офериран продукт:',
    'LBL_QUOTE_ID' => 'Оферта:',
    'LBL_RELATED_TO' => 'Свързано с:',
    'LBL_SEARCH_FORM_TITLE' => 'Търсене в модул "Бележки"',
    'LBL_STATUS' => 'Статус',
    'LBL_SUBJECT' => 'Относно:',
    'LNK_IMPORT_NOTES' => 'Импортиране на бележки',
    'LNK_NEW_NOTE' => 'Създаване на бележка',
    'LNK_NOTE_LIST' => 'Списък с бележки',
    'LBL_MEMBER_OF' => 'Член на:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Отговорник',
    'LBL_OC_FILE_NOTICE' => 'Моля, осъществете връзка със сървъра, за да видите файла',
    'LBL_REMOVING_ATTACHMENT' => 'Изтриване на приложението...',
    'ERR_REMOVING_ATTACHMENT' => 'Изтриване на приложението е неуспешно...',
    'LBL_CREATED_BY' => 'Създадено от',
    'LBL_MODIFIED_BY' => 'Модифицирано от',
    'LBL_SEND_ANYWAYS' => 'Това писмо няма дефинирана тема.  Изпрати и запази?',
    'LBL_LIST_EDIT_BUTTON' => 'Редактирай',
    'LBL_ACTIVITIES_REPORTS' => 'Справка за моите Дейности',
    'LBL_PANEL_DETAILS' => 'Допълнителна информация',
    'LBL_NOTE_INFORMATION' => 'Бележки',
    'LBL_MY_NOTES_DASHLETNAME' => 'Моите бележки',
    'LBL_EDITLAYOUT' => 'Редактиране на подредби' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Име',
    'LBL_LAST_NAME' => 'Фамилия',
    'LBL_EXPORT_PARENT_TYPE' => 'Свързан с модул',
    'LBL_EXPORT_PARENT_ID' => 'Свързано с',
    'LBL_DATE_ENTERED' => 'Създадено на',
    'LBL_DATE_MODIFIED' => 'Модифицирано на',
    'LBL_DELETED' => 'Изтрити',
    'LBL_REVENUELINEITEMS' => 'Приходни позиции',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модулът {{plural_module_name}} съхранява текстова информация и прикачени файлове, които са свързани с конкретен запис в системата. Съществуват различни начини да създадете {{plural_module_name}} в SugarCRM, като за целта можете да използвате опциите на модула {{plural_module_name}}, да импортирате  {{plural_module_name}} през панела „История“ и други. След като дадена {{module_name}} е въведена в системата, можете да разгледате и редактирате информацията за нея през изгледа „Детайли за записа“. Всяка {{module_name}} може да бъде свързана с други записи в Sugar, в това число {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} и много други.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Модулът {{plural_module_name}} съхранява текстова информация и прикачени файлове, които са свързани с конкретен запис в системата.

- Редактирайте текущия запис като натиснете конкретно поле или използвате бутона „Редактирай“.
- Разгледайте или модифицирайте връзките с други записи в системата, като за целта визуализирате панела „Данни“.
- Поставете коментари към текущия запис и разгледайте история на извършените промени свързани с него, като преминете в панела „Хронология“.
- Следвайте или харесайте текущия запис като използвате иконите, намиращи се в дясно от името му.
- Можете да извършвате допълнителни действия със записа като използвате падащото меню в дясно на бутона „Редактирай“.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'За да създадете нов запис в модул {{module_name}}:
1. Въведете стойности в отделните полета.
- Изисква се полетата, маркирани като "Задължителни", да имат зададена стойност преди да се пристъпи към съхраняване на записа.
- Натиснете върху "Покажи повече", за да бъдат визуализирани допълнителни полета ако е необходимо.
2. Натиснете "Съхрани", за да запазите новия запис и да се върнете към предишната страница.',
);
