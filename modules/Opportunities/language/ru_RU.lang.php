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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Информационная панель списка сделок',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Информационная панель записей сделок',

    'LBL_MODULE_NAME' => 'Сделки',
    'LBL_MODULE_NAME_SINGULAR' => 'Сделка',
    'LBL_MODULE_TITLE' => 'Сделки: Главная',
    'LBL_SEARCH_FORM_TITLE' => 'Поиск сделки',
    'LBL_VIEW_FORM_TITLE' => 'Обзор сделки',
    'LBL_LIST_FORM_TITLE' => 'Список сделок',
    'LBL_OPPORTUNITY_NAME' => 'Название сделки:',
    'LBL_OPPORTUNITY' => 'Сделка:',
    'LBL_NAME' => 'Название сделки:',
    'LBL_INVITEE' => 'Контакты',
    'LBL_CURRENCIES' => 'Валюта',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Название',
    'LBL_LIST_ACCOUNT_NAME' => 'Контрагент',
    'LBL_LIST_DATE_CLOSED' => 'Дата закрытия',
    'LBL_LIST_AMOUNT' => 'Вероятность',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Сумма',
    'LBL_ACCOUNT_ID' => 'Контрагент',
    'LBL_CURRENCY_RATE' => 'Валютный курс',
    'LBL_CURRENCY_ID' => 'Валюта',
    'LBL_CURRENCY_NAME' => 'Валюта',
    'LBL_CURRENCY_SYMBOL' => 'Символ валюты',
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
    'LBL_CURRENCY' => 'Валюта:',
    'LBL_DATE_CLOSED' => 'Предполагаемая дата закрытия:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Ожидаемая дата закрытия',
    'LBL_TYPE' => 'Тип:',
    'LBL_CAMPAIGN' => 'Маркетинговая кампания:',
    'LBL_NEXT_STEP' => 'Следующий шаг:',
    'LBL_LEAD_SOURCE' => 'Источник предварительного контакта:',
    'LBL_SALES_STAGE' => 'Стадия продажи:',
    'LBL_SALES_STATUS' => 'Статус',
    'LBL_PROBABILITY' => 'Вероятность (%):',
    'LBL_DESCRIPTION' => 'Описание:',
    'LBL_DUPLICATE' => 'Возможно дублирующая сделка',
    'MSG_DUPLICATE' => 'Запись, которую Вы создаете, возможно, дублирует уже имеющуюся запись. Похожие сделки показаны ниже. Нажмите кнопку "Сохранить"  для продолжения создания новой сделки или кнопку "Отмена" для возврата в модуль без создания сделки.',
    'LBL_NEW_FORM_TITLE' => 'Новая сделка',
    'LNK_NEW_OPPORTUNITY' => 'Новая сделка',
    'LNK_CREATE' => 'Завести сделку',
    'LNK_OPPORTUNITY_LIST' => 'Просмотр сделок',
    'ERR_DELETE_RECORD' => 'Вы должны указать номер записи перед удалением сделки.',
    'LBL_TOP_OPPORTUNITIES' => 'Мои основные открытые сделки',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Вы действительно хотите удалить этот контакт из сделки?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Вы действительно хотите удалить данную сделку из проекта',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Сделки',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Мероприятия',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'История',
    'LBL_RAW_AMOUNT' => 'Сырой объем',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Предварительные контакты',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакты',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Документы',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Проекты',
    'LBL_ASSIGNED_TO_NAME' => 'Ответственный (-ая):',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Ответственный (-ая)',
    'LBL_LIST_SALES_STAGE' => 'Стадия продажи',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Мои закрытые сделки',
    'LBL_TOTAL_OPPORTUNITIES' => 'Все сделки',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Успешно закрытые сделки',
    'LBL_ASSIGNED_TO_ID' => 'Ответственный (-ая):',
    'LBL_CREATED_ID' => 'Создано пользователем',
    'LBL_MODIFIED_ID' => 'Изменено пользователем',
    'LBL_MODIFIED_NAME' => 'Изменено',
    'LBL_CREATED_USER' => 'Создано пользователем',
    'LBL_MODIFIED_USER' => 'Изменено пользователем',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Маркетинговые кампании',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Проекты',
    'LABEL_PANEL_ASSIGNMENT' => 'Назначение ответственного',
    'LNK_IMPORT_OPPORTUNITIES' => 'Импорт сделок',
    'LBL_EDITLAYOUT' => 'Правка расположения' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Маркетинговая кампания (ID)',
    'LBL_OPPORTUNITY_TYPE' => 'Тип сделки',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Ответственный пользователь',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Ответственный (ID)',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Изменено (ID)',
    'LBL_EXPORT_CREATED_BY' => 'Создано (ID)',
    'LBL_EXPORT_NAME' => 'Имя',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Email-сообщения соответствующих контактов',
    'LBL_FILENAME' => 'Вложение',
    'LBL_PRIMARY_QUOTE_ID' => 'Инзначальное ценовое предложение',
    'LBL_CONTRACTS' => 'Контракты',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Контракты',
    'LBL_PRODUCTS' => 'Продукты',
    'LBL_RLI' => 'Доход по продуктам',
    'LNK_OPPORTUNITY_REPORTS' => 'Просмотр отчета по сделкам',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Коммерческие предложения',
    'LBL_TEAM_ID' => 'Команда',
    'LBL_TIMEPERIODS' => 'Временные промежутки',
    'LBL_TIMEPERIOD_ID' => 'ID временного промежутка',
    'LBL_COMMITTED' => 'Назначен',
    'LBL_FORECAST' => 'Включить в прогноз',
    'LBL_COMMIT_STAGE' => 'Стадия совершения продажи',
    'LBL_COMMIT_STAGE_FORECAST' => 'Прогноз',
    'LBL_WORKSHEET' => 'Лист прогнозов',

    'TPL_RLI_CREATE' => 'Сделка должна быть привязана к продукту.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Создать новую позицию продажи.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Продукты',
    'LBL_RLI_SUBPANEL_TITLE' => 'Доход по продуктам',

    'LBL_TOTAL_RLIS' => '# суммарного дохода по продуктам',
    'LBL_CLOSED_RLIS' => '# закрытого дохода по продуктам',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Вы не можете удалить продажи, которые содержат закрытый доход по продукту',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Одна или более выбранных записей содержат закрытый доход по продуктам и не могут быть удалены',
    'LBL_INCLUDED_RLIS' => '# учтенного дохода по продуктам',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Коммерческие предложения',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Структура сделки',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Значения Доходов по продажам высчитаны для Продаж',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Сумма воронки',

    'LBL_OPPORTUNITY_ROLE'=>'Роль сделки',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Заметки',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Нажав кнопку Подтвердить, Вы сотрете все данные Прогнозов и измените представление Ваших продаж. Если это не то, что Вы хотели, нажмите кнопку Отмена, чтобы вернуться к предыдущим настройкам.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'После нажатия кнопки "Подтвердить" ВСЕ данные прогнозов будет удалены, а обзор ваших сделок будет изменен. '
        .'Кроме того, будут отключены ВСЕ описания используемых процессов с целевым модулем доходов по продукту. '
        .'Если это не планировалось, нажмите кнопку "Отмена", чтобы вернуться к предыдущим настройкам.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Если все записи Доходов по продажам закрыты и, по крайней мере, одна запись была выиграна,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'если стадия продажи Сделки установлена в значение "Успешно закрытая"',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Если все записи Доходов по продажам находятся на стадии продажи "Потеряна",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'стадия продажи Сделки установлен в значение "Потеряна"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Если любая запись Дохода по продажам все еще открыта,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'Продажа будет отмечена с более ранней стадией продажи.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'После выполнения этого изменения в фоновом режиме будут созданы итоговые примечания для дохода по продуктам. Когда примечания станут доступными и будут содержать полную информацию, на адрес электронной почты, зарегистрированный для вашего профиля пользователя, будет отправлено уведомление. Если для вашего экземпляра настроена функция {{forecasts_module}}, Sugar также отправит уведомление после того, как записи {{module_name}} будут синхронизированы с модулем {{forecasts_module}} и станут доступными для нового {{forecasts_module}}. Обратите внимание, что для отправки уведомлений по электронной почте необходимо настроить соответствующие параметры экземпляра программы в меню Admin > Email Settings (Администратор > Настройки электронной почты).',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'После выполнения этого изменения в фоновом режиме для каждого имеющегося {{module_name}} будут созданы записи дохода по продуктам. Когда доходы по продуктам станут доступными и будут содержать полную информацию, на адрес электронной почты, зарегистрированный для вашего профиля пользователя, будет отправлено уведомление. Обратите внимание, что для отправки уведомлений по электронной почте в вашем экземпляре программы необходимо настроить соотвествующие параметры в меню Admin > Email Settings (Администратор > Настройки электронной почты).',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Модуль {{plural_module_name}} позволяет отслеживать отдельные продажи от начала и до конца. Каждая запись модуля {{module_name}} является потенциальной сделкой и содержит соответствующие данные по продаже, а также относится к другим важным записям, таким как {{quotes_module}}, {{contacts_module}} и т. д. Каждый модуль {{module_name}} будет проходить несколько стадий продаж до тех пор, пока не получит статус «Успешно закрыта» или «Проиграна». Эффективность модуля {{plural_module_name}} можно увеличить, используя модуль {{forecasts_singular_module}} системы Sugar для понимания и прогнозирования тенденций продаж и сосредоточения работы на достижении установленных квот.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Модуль {{plural_module_name}} позволяет отслеживать отдельные продажи и позиции, относящиеся к этим продажам, от начала и до конца. Каждая запись модуля {{module_name}} является потенциальной сделкой и содержит соответствующие данные по продаже, а также относится к другим важным записям, таким как {{quotes_module}}, {{contacts_module}} и т. д.

- Чтобы редактировать поля данной записи, нажмите поле или щелкните кнопку «Редактировать». 
- Чтобы просмотреть или изменить ссылки на другие записи на субпанелях, переключите левую нижнюю панель на «Просмотр данных». 
- Чтобы оставлять и просматривать пользовательские комментарии, а также журнал изменений записи в {{activitystream_singular_module}}, переключите левую нижнюю панель на «Лента активностей». 
- Чтобы подписаться или добавить в Избранное данную запись, используйте значки справа от записи. 
- Дополнительные действия доступны в выпадающем меню «Действия» справа от кнопки «Редактировать».',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Модуль {{plural_module_name}} позволяет отслеживать отдельные продажи и позиции, относящиеся к этим продажам, от начала и до конца. Каждая запись модуля {{module_name}} является потенциальной сделкой и содержит соответствующие данные по продаже, а также относится к другим важным записям, таким как {{quotes_module}}, {{contacts_module}} и т. д. 

Чтобы создать модуль {{module_name}}: 
1. Укажите необходимые значения полей. 
 - Поля, отмеченные как обязательные, необходимо заполнить перед сохранением.
 - Нажмите «Показать больше», чтобы отобразить дополнительные поля при необходимости.
2. Нажмите кнопку «Сохранить», чтобы завершить создание новой записи и вернуться на предыдущую страницу.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Синхронизироваться с Marketo®',
    'LBL_MKTO_ID' => 'Marketo Lead ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Тип 10 продаж',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Отображает топ 10 продаж в кружковой диаграмме.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Мои продажи',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Продажи моей команды",
);
