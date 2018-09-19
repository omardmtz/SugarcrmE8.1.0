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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Інформаційна панель списку угод',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Інформаційна панель записів угод',

    'LBL_MODULE_NAME' => 'Угоди',
    'LBL_MODULE_NAME_SINGULAR' => 'Угода',
    'LBL_MODULE_TITLE' => 'Угоди: Головна',
    'LBL_SEARCH_FORM_TITLE' => 'Пошук угод',
    'LBL_VIEW_FORM_TITLE' => 'Переглянути угоду',
    'LBL_LIST_FORM_TITLE' => 'Список угод',
    'LBL_OPPORTUNITY_NAME' => 'Назва угоди',
    'LBL_OPPORTUNITY' => 'Угода:',
    'LBL_NAME' => 'Назва угоди',
    'LBL_INVITEE' => 'Контакти',
    'LBL_CURRENCIES' => 'Валюти',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Назва',
    'LBL_LIST_ACCOUNT_NAME' => 'Контрагент',
    'LBL_LIST_DATE_CLOSED' => 'Очікувана дата закриття',
    'LBL_LIST_AMOUNT' => 'Ймовірний сценарій',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Підсумкова сума',
    'LBL_ACCOUNT_ID' => 'Контрагент (ID)',
    'LBL_CURRENCY_RATE' => 'Валютний курс',
    'LBL_CURRENCY_ID' => 'Валюта (ID)',
    'LBL_CURRENCY_NAME' => 'Назва валюти',
    'LBL_CURRENCY_SYMBOL' => 'Символ валюти',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Угода - Оновлення валюти',
    'UPDATE_DOLLARAMOUNTS' => 'Оновлення суми в доларах США',
    'UPDATE_VERIFY' => 'Перевірити суми',
    'UPDATE_VERIFY_TXT' => 'Перевірте, що суми в угодах мають правильні значення, використовуються тільки цифри (0-9) і знак розряду (.)',
    'UPDATE_FIX' => 'Редагувати суми',
    'UPDATE_FIX_TXT' => 'Спроби виправити невірні суми, за допомогою створення правильного роздільника з поточної суми. Будь-яка зміна суми буде збережена у вигляді резервної копії в полі БД amount_backup. Якщо Ви отримали повідомлення про помилку, не повторюйте цей крок без відновлення даних з резервної копії, в іншому випадку в архів будуть перезаписані нові невірні дані.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Оновлення сум у доларах США для угод, засноване на поточних ставка курсу обміну валют. Ця величина використовується для розрахунку графіків і списків перегляду валютних сум.',
    'UPDATE_CREATE_CURRENCY' => 'Створення нової валюти:',
    'UPDATE_VERIFY_FAIL' => 'Невдала перевірка запису:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Поточна сума:',
    'UPDATE_VERIFY_FIX' => 'Запуск перевірки даних',
    'UPDATE_INCLUDE_CLOSE' => 'Включити закриті записи',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Нова сума',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Нова валюта:',
    'UPDATE_DONE' => 'Виконано',
    'UPDATE_BUG_COUNT' => 'Кількість знайдених помилок і спроб їх вирішення:',
    'UPDATE_BUGFOUND_COUNT' => 'Знайдені помилки:',
    'UPDATE_COUNT' => 'Оновлені записи:',
    'UPDATE_RESTORE_COUNT' => 'Суми в записах відновлені:',
    'UPDATE_RESTORE' => 'Відновлення сум',
    'UPDATE_RESTORE_TXT' => 'Відновлення сум з резервної копії, створеної під час виправлення помилок.',
    'UPDATE_FAIL' => 'Не вдалося оновити -',
    'UPDATE_NULL_VALUE' => 'Сума NULL встановлена на 0 -',
    'UPDATE_MERGE' => 'Об&#39;єднати валюти',
    'UPDATE_MERGE_TXT' => 'Об&#39;єднання кількох валют в одну. Якщо є багато записів валют для однієї і тієї ж валюти, то об&#39;єднайте їх разом. Це також об&#39;єднає дані валюти для всіх інших модулів.',
    'LBL_ACCOUNT_NAME' => 'Назва контрагента:',
    'LBL_CURRENCY' => 'Валюта:',
    'LBL_DATE_CLOSED' => 'Очікувана дата закриття',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Очікувана дата закриття Тимчасова мітка',
    'LBL_TYPE' => 'Тип:',
    'LBL_CAMPAIGN' => 'Маркетингова кампанія:',
    'LBL_NEXT_STEP' => 'Наступний крок:',
    'LBL_LEAD_SOURCE' => 'Джерело інтереса',
    'LBL_SALES_STAGE' => 'Стадія продажу',
    'LBL_SALES_STATUS' => 'Статус',
    'LBL_PROBABILITY' => 'Імовірність (%)',
    'LBL_DESCRIPTION' => 'Опис',
    'LBL_DUPLICATE' => 'Можливо, ця угода є дублікатом',
    'MSG_DUPLICATE' => 'Створювана Вами Комерційна пропозиція, можливо, дублює вже наявний запис. Схожі комерційні пропозиції показані нижче. Виберіть зі списку або натисніть кнопку "Зберегти" для продовження створення нової комерційної пропозиції.',
    'LBL_NEW_FORM_TITLE' => 'Створити угоду',
    'LNK_NEW_OPPORTUNITY' => 'Створити угоду',
    'LNK_CREATE' => 'Створити контакт',
    'LNK_OPPORTUNITY_LIST' => 'Переглянути угоди',
    'ERR_DELETE_RECORD' => 'Необхідно вказати номер запису, щоб видалити угоду.',
    'LBL_TOP_OPPORTUNITIES' => 'Мої основні відкриті угоди',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Ви дійсно бажаєте видалити цей контакт з угоди?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Ви дійсно бажаєте видалити цю угоду з проекту?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Угоди',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Активності',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Історія',
    'LBL_RAW_AMOUNT' => 'Сира сума',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Інтереси',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакти',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Документи',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Проекти',
    'LBL_ASSIGNED_TO_NAME' => 'Відповідальний (-а)',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Відповідальний (-а)',
    'LBL_LIST_SALES_STAGE' => 'Стадія продажу',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Мої закриті угоди',
    'LBL_TOTAL_OPPORTUNITIES' => 'Всі угоди',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Успішно закриті угоди',
    'LBL_ASSIGNED_TO_ID' => 'Відповідальний (-а):',
    'LBL_CREATED_ID' => 'Створено (ID)',
    'LBL_MODIFIED_ID' => 'Змінено (ID)',
    'LBL_MODIFIED_NAME' => 'Змінено користувачем',
    'LBL_CREATED_USER' => 'Створенено користувачем',
    'LBL_MODIFIED_USER' => 'Змінено користувачем',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Угода маркетингової кампанії',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Проекти',
    'LABEL_PANEL_ASSIGNMENT' => 'Призначення відповідального',
    'LNK_IMPORT_OPPORTUNITIES' => 'Імпорт угод',
    'LBL_EDITLAYOUT' => 'Редагувати розташування' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Маркетингова кампанія (ID)',
    'LBL_OPPORTUNITY_TYPE' => 'Тип угоди',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Відповідальний користувач',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Відповідальний (-а) (ID)',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Змінено (ID)',
    'LBL_EXPORT_CREATED_BY' => 'Створено (ID)',
    'LBL_EXPORT_NAME' => 'Назва',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Email-повідомлення пов&#39;язаних контактів',
    'LBL_FILENAME' => 'Вкладення',
    'LBL_PRIMARY_QUOTE_ID' => 'Початкова цінова пропозиція',
    'LBL_CONTRACTS' => 'Контракти',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Контракти',
    'LBL_PRODUCTS' => 'Продукти комерційної пропозиції',
    'LBL_RLI' => 'Доходи за продукти',
    'LNK_OPPORTUNITY_REPORTS' => 'Переглянути звіти по угодах',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Комерційні пропозиції',
    'LBL_TEAM_ID' => 'Команда (ID)',
    'LBL_TIMEPERIODS' => 'Проміжки часу',
    'LBL_TIMEPERIOD_ID' => 'Проміжок часу (ID)',
    'LBL_COMMITTED' => 'Підтверджено',
    'LBL_FORECAST' => 'Включити в прогноз',
    'LBL_COMMIT_STAGE' => 'Підтвердити стадію',
    'LBL_COMMIT_STAGE_FORECAST' => 'Прогноз',
    'LBL_WORKSHEET' => 'Таблиця',

    'TPL_RLI_CREATE' => 'Продажа повинна бути прив&#39;язана до продукту.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Створити дохід за продукт',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Продукти комерційної пропозиції',
    'LBL_RLI_SUBPANEL_TITLE' => 'Доходи за продукти',

    'LBL_TOTAL_RLIS' => '# Сумарного доходу по продуктам',
    'LBL_CLOSED_RLIS' => '# Закритого доходу по продуктам',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Ви не можете видалити продажі, які містять закритий дохід по продукту',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Одна або більше обраних записів містять закритий дохід по продуктах і не можуть бути вилучен',
    'LBL_INCLUDED_RLIS' => '# of Included Revenue Line Items',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Комерційні пропозиції',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Структура угоди',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Значення Доходів з продажу вирахувані для Продажів',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Воронка всього',

    'LBL_OPPORTUNITY_ROLE'=>'Роль угоди',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Примітки',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Натиснувши кнопку Підтвердити, Ви зітрете всі дані Прогнозів і зміните представлення Ваших продажів. Якщо це не те, що Ви хотіли, натисніть кнопку Скасувати, щоб повернутися до попередніх налаштувань.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Після натискання кнопки "Підтвердити" ВСІ дані прогнозів буде видалено, а огляд ваших угод буде змінено. '
        .'Крім того, буде вимкнуто ВСІ визначення процесів із цільовим модулем доходів за продукти. '
        .'Якщо це не планувалося, натисніть кнопку "Скасувати", щоб повернутися до попередніх настройок.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Якщо всі записи Доходів з продажу закриті і, принаймні, одна запис була успішна,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'якщо стадія продажу Угоди встановлена в значення "Успішно закрита"',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Якщо всі записи Доходів з продажу знаходяться на стадії продажу "Втрачено",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'етап продажу Угоди встановлений в значення "Втрачено"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Якщо будь-який запис Доходу з продажу все ще відкритий,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'Продаж буде відмічено з більш раннім етапом продажу.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Після того як ви ініціюєте ці зміни, сумування Виручки буде створено у фоновому режимі. Коли примітки стануть повними і доступними, буде надіслано повідомлення на адресу електронної пошти у Вашому профілі користувача. Якщо ваш екземпляр налаштований на {{forecasts_module}}, Sugar  також відправить вам повідомлення, коли ваші {{module_name}} записи синхронізуються з модулем {{forecasts_module}} і сануть доступними для нових {{forecasts_module}}. Будь ласка, зверніть увагу, що екземпляр має бути налаштований для відправки електронної пошти за допомогою Admin > Email Settings для того, щоб повідомлення можна було відправити.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Після того як ви ініціюєте ці зміни, доходи по окремих позиціях запису будуть створюватися для кожного існуючого {{module_name}} у фоновому режимі. Коли  статті доходу стануть заповненими та доступними, буде надіслано повідомлення на адресу електронної пошти у Вашому профілі користувача. Будь ласка, зверніть увагу, що екземпляр має бути налаштований для відправки електронної пошти за допомогоюAdmin > Email Settings для того,  щоб повідомлення можна було відправити.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модуль {{plural_module_name}} дозволяє відстежувати окремі продажі від початку до кінця. Кожен запис {{module_name}} є потенційним продажем і містить відповідні дані про продаж, а також відноситься до інших важливих записів, як-от {{quotes_module}}, {{contacts_module}} тощо. Кожен модуль {{module_name}} проходить кілька стадій продажу, доки його не буде позначено як «Успішно закритий» або «Втрачений». Модуль {{plural_module_name}} може бути ефективнішим, якщо використовувати модуль {{forecasts_singular_module}} системи Sugar для розуміння та прогнозування тенденцій продажів, а також зосередження роботи на досягненні квот продажу.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Модуль {{plural_module_name}} дозволяє відстежувати окремі продажі та позиції, які відносяться до цих продажів, від початку до кінця. Кожен запис {{module_name}} є потенційним продажем і містить відповідні дані про продаж, а також відноситься до інших важливих записів, як-от {{quotes_module}}, {{contacts_module}} тощо. 

- Щоб редагувати поля цього запису, клацніть потрібне поле або натисніть кнопку «Редагувати». 
- Щоб переглянути або змінити посилання на інші записи на субпанелях, переключіть ліву нижню панель на «Перегляд даних». 
- Щоб залишати та переглядати коментарі користувачів, а також журнал змін запису в модулі {{activitystream_singular_module}}, переключіть ліву нижню панель на «Потік активності». 
- Щоб підписатися на цей запис або додати його до вподобань, використовуйте значки праворуч від запису. 
- Додаткові дії доступні в розкривному меню «Дії» праворуч від кнопки «Редагувати».',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Модуль {{plural_module_name}} дозволяє відстежувати окремі продажі та позиції, які відносяться до цих продажів, від початку до кінця. Кожен запис {{module_name}} є потенційним продажем і містить відповідні дані про продаж, а також відноситься до інших важливих записів, як-от {{quotes_module}}, {{contacts_module}} тощо. 

Щоб створити модуль {{module_name}}, виконайте наведені нижче дії.
1. Укажіть потрібні значення полів.
 - Перед збереженням потрібно заповнити поля з позначкою «Обов&#39;язково».
 - Щоб відобразити додаткові поля, натисніть «Показати більше». 
2. Натисніть «Зберегти», щоб завершити створення нового запису та повернутися на попередню сторінку.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Синхронізувати з Marketo&reg;',
    'LBL_MKTO_ID' => 'Marketo Lead (ID)',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Топ 10 продажів',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Відображає першу десятку продажів в круговій діаграмі.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Мої угоди',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Угоди моєї команди",
);
