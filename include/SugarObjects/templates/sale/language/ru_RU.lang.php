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
  'LBL_MODULE_NAME' => 'Сделки',
  'LBL_MODULE_TITLE' => 'Сделки: Главная',
  'LBL_SEARCH_FORM_TITLE' => 'Найти сделку',
  'LBL_VIEW_FORM_TITLE' => 'Обзор сделки',
  'LBL_LIST_FORM_TITLE' => 'Список сделок',
  'LBL_SALE_NAME' => 'Сделка:',
  'LBL_SALE' => 'Сделка:',
  'LBL_NAME' => 'Сделка',
  'LBL_LIST_SALE_NAME' => 'Название',
  'LBL_LIST_ACCOUNT_NAME' => 'Контрагент',
  'LBL_LIST_AMOUNT' => 'Сумма',
  'LBL_LIST_DATE_CLOSED' => 'Дата закрытия',
  'LBL_LIST_SALE_STAGE' => 'Стадия продажи',
  'LBL_ACCOUNT_ID'=>'Контрагент',
  'LBL_TEAM_ID' =>'Команда',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Сделка - обновление валюты',
  'UPDATE_DOLLARAMOUNTS' => 'Обновить суммы в долларах США',
  'UPDATE_VERIFY' => 'Проверить суммы',
  'UPDATE_VERIFY_TXT' => 'Проверьте, что суммы в сделках имеют правильные значения, используются только цифры (0-9) и знак разряда (.)',
  'UPDATE_FIX' => 'Исправление сумм',
  'UPDATE_FIX_TXT' => 'Попытки исправить неверные суммы, посредством создания правильного разделителя из текущей суммы. Любое изменение суммы будет сохранено в виде резервной копии в поле БД amount_backup. Если Вы получили уведомление об ошибке, не повторяйте этот шаг без восстановления данных из резервной копии, в противном случае в архив будут перезаписаны новые неверные данные.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Обновление сумм в долларах США для сделок, основанное на текущих установках курса обмена валют. Эта величина используется для расчета графиков и списков просмотра валютных сумм.',
  'UPDATE_CREATE_CURRENCY' => 'Создание новой валюты:',
  'UPDATE_VERIFY_FAIL' => 'Неудачная проверка записи:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Текущая сумма:',
  'UPDATE_VERIFY_FIX' => 'Запуск проверки данных',
  'UPDATE_INCLUDE_CLOSE' => 'Включить закрытые записи',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Новая сумма:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Новая валюта:',
  'UPDATE_DONE' => 'Готово',
  'UPDATE_BUG_COUNT' => 'Количество найденных ошибок и попыток их решения:',
  'UPDATE_BUGFOUND_COUNT' => 'Найдены ошибки:',
  'UPDATE_COUNT' => 'Обновлённые записи:',
  'UPDATE_RESTORE_COUNT' => 'Суммы в записях восстановлены:',
  'UPDATE_RESTORE' => 'Восстановление сумм',
  'UPDATE_RESTORE_TXT' => 'Восстановление сумм из резервной копии, созданной во время исправления ошибок.',
  'UPDATE_FAIL' => 'Не обновлено -',
  'UPDATE_NULL_VALUE' => 'Сумма NULL установлена на 0 -',
  'UPDATE_MERGE' => 'Объединить валюты',
  'UPDATE_MERGE_TXT' => 'Объединение многих валют в одну. Если имеется много записей валют для одной и той же валюты, то объедините их вместе. Это также объединит данные валюты  для всех остальных модулей.',
  'LBL_ACCOUNT_NAME' => 'Контрагент:',
  'LBL_AMOUNT' => 'Сумма сделки:',
  'LBL_AMOUNT_USDOLLAR' => 'Сумма, доллары США:',
  'LBL_CURRENCY' => 'Валюта:',
  'LBL_DATE_CLOSED' => 'Ожидаемая дата закрытия:',
  'LBL_TYPE' => 'Тип:',
  'LBL_CAMPAIGN' => 'Маркетинговая кампания:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Предварительные контакты',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Проекты',  
  'LBL_NEXT_STEP' => 'Следующий шаг:',
  'LBL_LEAD_SOURCE' => 'Источник получения предварительного контакта:',
  'LBL_SALES_STAGE' => 'Стадия продаж:',
  'LBL_PROBABILITY' => 'Вероятность (%):',
  'LBL_DESCRIPTION' => 'Описание:',
  'LBL_DUPLICATE' => 'Возможно дублирующая сделка',
  'MSG_DUPLICATE' => 'Запись, которую Вы создаете, возможно, дублирует уже имеющуюся запись. Похожие сделки показаны ниже. Нажмите кнопку "Сохранить"  для продолжения создания новой сделки или кнопку "Отмена" для возврата в модуль без создания сделки.',
  'LBL_NEW_FORM_TITLE' => 'Новая сделка',
  'LNK_NEW_SALE' => 'Новая сделка',
  'LNK_SALE_LIST' => 'Сделка',
  'ERR_DELETE_RECORD' => 'Вы должны указать номер записи перед удалением сделки.',
  'LBL_TOP_SALES' => 'Мои основные открытые сделки',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Вы действительно хотите удалить этот контакт из сделки?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Вы действительно хотите удалить эту сделку из проекта?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Мероприятия',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'История',
    'LBL_RAW_AMOUNT'=>'Сырой объем',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакты',
	'LBL_ASSIGNED_TO_NAME' => 'Ответственный (-ая):',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Ответственный (-ая)',
  'LBL_MY_CLOSED_SALES' => 'Мои закрытые сделки',
  'LBL_TOTAL_SALES' => 'Все сделки',
  'LBL_CLOSED_WON_SALES' => 'Успешно закрытые сделки',
  'LBL_ASSIGNED_TO_ID' =>'Ответственный (-ая)',
  'LBL_CREATED_ID'=>'Создано пользователем',
  'LBL_MODIFIED_ID'=>'Изменено пользователем',
  'LBL_MODIFIED_NAME'=>'Изменено',
  'LBL_SALE_INFORMATION'=>'Информация по сделке',
  'LBL_CURRENCY_ID'=>'Валюта',
  'LBL_CURRENCY_NAME'=>'Валюта',
  'LBL_CURRENCY_SYMBOL'=>'Символ валюты',
  'LBL_EDIT_BUTTON' => 'Правка',
  'LBL_REMOVE' => 'Удалить',
  'LBL_CURRENCY_RATE' => 'Валютный курс',

);

