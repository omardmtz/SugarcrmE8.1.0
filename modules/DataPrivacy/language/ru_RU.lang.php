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
    'LBL_MODULE_NAME' => 'Защита данных',
    'LBL_MODULE_NAME_SINGULAR' => 'Защита данных',
    'LBL_NUMBER' => 'Номер',
    'LBL_TYPE' => 'Тип',
    'LBL_SOURCE' => 'Источник',
    'LBL_REQUESTED_BY' => 'Запрос пользователя',
    'LBL_DATE_OPENED' => 'Дата открытия',
    'LBL_DATE_DUE' => 'Дата выполнения',
    'LBL_DATE_CLOSED' => 'Дата закрытия',
    'LBL_BUSINESS_PURPOSE' => 'Согласованные бизнес-цели',
    'LBL_LIST_NUMBER' => 'Номер',
    'LBL_LIST_SUBJECT' => 'Название',
    'LBL_LIST_PRIORITY' => 'Приоритет',
    'LBL_LIST_STATUS' => 'Статус',
    'LBL_LIST_TYPE' => 'Тип',
    'LBL_LIST_SOURCE' => 'Источник',
    'LBL_LIST_REQUESTED_BY' => 'Запрос пользователя',
    'LBL_LIST_DATE_DUE' => 'Дата завершения',
    'LBL_LIST_DATE_CLOSED' => 'Дата закрытия',
    'LBL_LIST_DATE_MODIFIED' => 'Дата изменения',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Изменено пользователем',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Ответственный пользователь',
    'LBL_SHOW_MORE' => 'Показать больше действий для защиты данных',
    'LNK_DATAPRIVACY_LIST' => 'Просмотр действий для защиты данных',
    'LNK_NEW_DATAPRIVACY' => 'Создать действие для защиты данных',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Предварительные контакты',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Контакты',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Потенциальные клиенты',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Контрагенты',
    'LBL_LISTVIEW_FILTER_ALL' => 'Все действия для защиты данных',
    'LBL_ASSIGNED_TO_ME' => 'Мои действия для защиты данных',
    'LBL_SEARCH_AND_SELECT' => 'Поиск и выбор действий для защиты данных',
    'TPL_SEARCH_AND_ADD' => 'Поиск и добавление действий для защиты данных',
    'LBL_WARNING_ERASE_CONFIRM' => 'Вы полностью удаляете {0} поле (-я). Вы не сможете возобновить эти данные после удаления. Действительно продолжить?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Вы отметили {0} поле (-я) для удаления. После подтверждений удаление будет отменено, все данные – сохранены, а запрос будет отмечен как "Отклонен". Действительно продолжить?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Вы собираетесь отметить этот запрос как "Завершен". Статус будет изменен на "Завершен", а сам запрос будет невозможно открыть снова. Действительно продолжить?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Вы собираетесь отметить этот запрос как "Отклонен". Статус будет изменен на "Отклонен", а сам запрос будет невозможно открыть снова. Действительно продолжить?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Вы успешно создали действие для защиты данных <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Отклонить',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Завершить',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Удалить и завершить',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Удалить поля, выбранные на субпанелях',
    'LBL_COUNT_FIELDS_MARKED' => 'Поля, помеченные для удаления',
    'LBL_NO_RECORDS_MARKED' => 'Для удаления нет ни одного отмеченного поля или записи.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Панель записи защиты данных',

    // list view
    'LBL_HELP_RECORDS' => 'Модуль защиты данных отслеживает использование конфиденциальной информации, в том числе запросы на предоставление согласия или запросы о наличии информации о субъекте персональных данных, для выполнения правил конфиденциальности компании. Для отслеживания предоставления согласия или для выполнения политики конфиденциальности можно создать записи защиты данных, связанные с конкретной записью (например, контактом).',
    // record view
    'LBL_HELP_RECORD' => 'Модуль защиты данных отслеживает использование конфиденциальной информации, в том числе запросы на предоставление согласия или запросы о наличии информации о субъекте персональных данных, для выполнения правил конфиденциальности компании. Для отслеживания предоставления согласия или для выполнения политики конфиденциальности можно создать записи защиты данных, связанные с конкретной записью (например, контактом). После выполнения нужного действия пользователи в роли руководителя отдела защиты данных могут нажать кнопку "Завершить" или "Отклонить" для обновления статуса.

Для удаления выберите "Отметить для удаления" для каждой отдельной записи, указанной в списках на субпанелях ниже. После выбора нужных полей и нажатия кнопки "Удалить и завершить" значения полей будут полностью удалены, а запись защиты данных будет завершена.',
);
