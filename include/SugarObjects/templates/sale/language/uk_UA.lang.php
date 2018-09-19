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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'Продаж',
  'LBL_MODULE_TITLE' => 'Продажі: Головна',
  'LBL_SEARCH_FORM_TITLE' => 'Пошук продажів',
  'LBL_VIEW_FORM_TITLE' => 'Переглянути продажі',
  'LBL_LIST_FORM_TITLE' => 'Список продажів',
  'LBL_SALE_NAME' => 'Назва продажі:',
  'LBL_SALE' => 'Продаж:',
  'LBL_NAME' => 'Назва продажу',
  'LBL_LIST_SALE_NAME' => 'Назва',
  'LBL_LIST_ACCOUNT_NAME' => 'Контрагент',
  'LBL_LIST_AMOUNT' => 'Сума',
  'LBL_LIST_DATE_CLOSED' => 'Закрити',
  'LBL_LIST_SALE_STAGE' => 'Стадія продажу',
  'LBL_ACCOUNT_ID'=>'Контрагент (ID)',
  'LBL_TEAM_ID' =>'Команда (ID)',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Продаж - Оновлення валюти',
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
  'LBL_AMOUNT' => 'Сума:',
  'LBL_AMOUNT_USDOLLAR' => 'Сума:',
  'LBL_CURRENCY' => 'Валюта:',
  'LBL_DATE_CLOSED' => 'Очікувана дата закриття',
  'LBL_TYPE' => 'Тип:',
  'LBL_CAMPAIGN' => 'Маркетингова кампанія:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Інтереси',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Проекти',  
  'LBL_NEXT_STEP' => 'Наступний крок:',
  'LBL_LEAD_SOURCE' => 'Джерело попереднього контакту:',
  'LBL_SALES_STAGE' => 'Стадія продажу:',
  'LBL_PROBABILITY' => 'Імовірність (%):',
  'LBL_DESCRIPTION' => 'Опис:',
  'LBL_DUPLICATE' => 'Можливо, цей продаж є дублікатом',
  'MSG_DUPLICATE' => 'Створювана Вами Комерційна пропозиція, можливо, дублює вже наявний запис. Схожі комерційні пропозиції показані нижче. Виберіть зі списку або натисніть кнопку "Зберегти" для продовження створення нової комерційної пропозиції або "Скасувати" щоб повернутися в модуль без створення продажу',
  'LBL_NEW_FORM_TITLE' => 'Створити продаж',
  'LNK_NEW_SALE' => 'Створити продаж',
  'LNK_SALE_LIST' => 'Продаж',
  'ERR_DELETE_RECORD' => 'Необхідно вказати номер запису, щоб видалити продаж.',
  'LBL_TOP_SALES' => 'Мої основні відкриті угоди',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Ви дійсно бажаєте видалити цей контакт з продажу?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Ви дійсно бажаєте видалити цей продаж з проекту?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Активності',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Історія',
    'LBL_RAW_AMOUNT'=>'Сира сума',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакти',
	'LBL_ASSIGNED_TO_NAME' => 'Користувач:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Відповідальний (-а)',
  'LBL_MY_CLOSED_SALES' => 'Мої закриті продажі',
  'LBL_TOTAL_SALES' => 'Всі угоди',
  'LBL_CLOSED_WON_SALES' => 'Успішно закриті угоди',
  'LBL_ASSIGNED_TO_ID' =>'Відповідальний (-а) (ID)',
  'LBL_CREATED_ID'=>'Створено (ID)',
  'LBL_MODIFIED_ID'=>'Змінено (ID)',
  'LBL_MODIFIED_NAME'=>'Змінено користувачем',
  'LBL_SALE_INFORMATION'=>'Інформація по продажу',
  'LBL_CURRENCY_ID'=>'Валюта (ID)',
  'LBL_CURRENCY_NAME'=>'Назва валюти',
  'LBL_CURRENCY_SYMBOL'=>'Символ валюти',
  'LBL_EDIT_BUTTON' => 'Редагувати',
  'LBL_REMOVE' => 'Видалити',
  'LBL_CURRENCY_RATE' => 'Валютний курс',

);

