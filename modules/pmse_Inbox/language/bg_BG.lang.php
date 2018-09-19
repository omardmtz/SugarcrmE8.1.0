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
    'LBL_MODULE_NAME' => 'Процеси',
    'LBL_MODULE_TITLE' => 'Процеси',
    'LBL_MODULE_NAME_SINGULAR' => 'Процес',
    'LNK_LIST' => 'Списък с процеси',
    'LNK_PMSE_INBOX_PROCESS_MANAGEMENT' => 'Управление на процеси',
    'LNK_PMSE_INBOX_UNATTENDED_PROCESSES' => 'Процеси оставени без надзор',

    'LBL_CAS_ID' => 'Номер на процеса',
    'LBL_PMSE_HISTORY_LOG_NOTFOUND_USER' => "Неизвестна (според потребителски идентификатор:&#39;%s&#39;)",
    'LBL_PMSE_HISTORY_LOG_TASK_HAS_BEEN' => "задачата е била",
    'LBL_PMSE_HISTORY_LOG_TASK_WAS' => "задачата беше",
    'LBL_PMSE_HISTORY_LOG_EDITED' => "редактирана",
    'LBL_PMSE_HISTORY_LOG_CREATED' => "създадено",
    'LBL_PMSE_HISTORY_LOG_ROUTED' => "маршрутизиран",
    'LBL_PMSE_HISTORY_LOG_DONE_UNKNOWN' => "Изпълнена неизвестна задача",
    'LBL_PMSE_HISTORY_LOG_CREATED_CASE' => "е създал Процесът #%s ",
    'LBL_PMSE_HISTORY_LOG_DERIVATED_CASE' => "е присвоен Процесът #%s ",
    'LBL_PMSE_HISTORY_LOG_CURRENTLY_HAS_CASE' => "в момента протича Процес #%s ",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME' => "\"%s\"",
    'LBL_PMSE_HISTORY_LOG_ACTION_PERFORMED'  => ". Изпълненото действие беше: <span style=\"font-weight: bold\">[%s]</span>",
    'LBL_PMSE_HISTORY_LOG_ACTION_STILL_ASSIGNED' => " Задачата продължава да бъде разпределена",
    'LBL_PMSE_HISTORY_LOG_MODULE_ACTION'  => " в %s записа %s",
    'LBL_PMSE_HISTORY_LOG_WITH_EVENT'  => " със Събитието %s",
    'LBL_PMSE_HISTORY_LOG_WITH_GATEWAY'  => ". Входът %s беше оценен и маршрутизиран към следващата задача ",
    'LBL_PMSE_HISTORY_LOG_NOT_REGISTERED_ACTION'  => "нерегистрирано действие",
    'LBL_PMSE_HISTORY_LOG_NO_YET_STARTED' => '(не е стартирало)',
    'LBL_PMSE_HISTORY_LOG_FLOW' => 'е разпределена за продължаване на задачата',

    'LBL_PMSE_HISTORY_LOG_START_EVENT' => "%s %s запис, който е накарал Advanced Workflow да задейства Процес #%s",
    'LBL_PMSE_HISTORY_LOG_GATEWAY'  => "%s %s %s беше оценен и маршрутизиран към следващата(ите) задача(и)",
    'LBL_PMSE_HISTORY_LOG_EVENT'  => "%s Събитието %s беше %s",
    'LBL_PMSE_HISTORY_LOG_END_EVENT'  => "Край",
    'LBL_PMSE_HISTORY_LOG_CREATED'  => "създадено",
    'LBL_PMSE_HISTORY_LOG_MODIFIED'  => "модифицирано",
    'LBL_PMSE_HISTORY_LOG_STARTED'  => "започнато",
    'LBL_PMSE_HISTORY_LOG_PROCESSED'  => "обработено",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_SELF_SERVICE'  => "Дейността %s в записа %s беше налична за Самообслужване",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY'  => "%s Дейността %s в записа %s",
    'LBL_PMSE_HISTORY_LOG_ASSIGNED'  => "беше разпределена",
    'LBL_PMSE_HISTORY_LOG_ROUTED'  => "маршрутизирана",
    'LBL_PMSE_HISTORY_LOG_ACTION'  => "%s Действието %s беше обработено в записа %s",
    'LBL_PMSE_HISTORY_LOG_ASSIGN_USER_ACTION'  => "му беше зададен Процеса #%s %s %s записа от %s Действието %s",
    'LBL_PMSE_HISTORY_LOG_ON'  => "на",
    'LBL_PMSE_HISTORY_LOG_AND'  => "и",

    'LBL_PMSE_LABEL_APPROVE' => 'Одобри',
    'LBL_PMSE_LABEL_REJECT' => 'Отхвърли',
    'LBL_PMSE_LABEL_ROUTE' => 'Маршрутизирай',
    'LBL_PMSE_LABEL_CLAIM' => 'Предяване на претенция',
    'LBL_PMSE_LABEL_STATUS' => 'Статус',
    'LBL_PMSE_LABEL_REASSIGN' => 'Преразпредели дейността',
    'LBL_PMSE_LABEL_CHANGE_OWNER' => 'Проемни отговорника на записа',
    'LBL_PMSE_LABEL_EXECUTE' => 'Изпълни',
    'LBL_PMSE_LABEL_CANCEL' => 'Отмени',
    'LBL_PMSE_LABEL_HISTORY' => 'История',
    'LBL_PMSE_LABEL_NOTES' => 'Покажи бележките',
    'LBL_PMSE_LABEL_ADD_NOTES' => 'Добави бележки',

    'LBL_PMSE_FORM_OPTION_SELECT' => 'Избор...',
    'LBL_PMSE_FORM_LABEL_USER' => 'Потребител',
    'LBL_PMSE_FORM_LABEL_TYPE' => 'Тип',
    'LBL_PMSE_FORM_LABEL_NOTE' => 'Бележка',

    'LBL_PMSE_BUTTON_SAVE' => 'Съхрани',
    'LBL_PMSE_BUTTON_CLOSE' => 'Затвори',
    'LBL_PMSE_BUTTON_CANCEL' => 'Отмени',
    'LBL_PMSE_BUTTON_REFRESH' => 'Обнови',
    'LBL_PMSE_BUTTON_CLEAR' => 'Изчисти',
    'LBL_PMSE_WARNING_CLEAR' => 'Сигурни ли сте, че желаете да изтриете данните в журнала? Данните не могат да бъдат възстановени в последствие.',

    'LBL_PMSE_FORM_TOOLTIP_SELECT_USER' => 'Присвоява този процес на потребителя.',
    'LBL_PMSE_FORM_TOOLTIP_CHANGE_USER' => 'Актуализира полето "Присвоен на" в записа на този потребител.',

    'LBL_PMSE_ALERT_REASSIGN_UNSAVED_FORM' => 'Има незаписани промени в този формуляр, те ще бъдат отхвърлени ако присвоите отново текущата задача. Искате ли да продължите?',
    'LBL_PMSE_ALERT_REASSIGN_SUCCESS' => 'Процесът е успешно присвоен отново',

    'LBL_PMSE_LABEL_CURRENT_ACTIVITY' => 'Текуща дейност',
    'LBL_PMSE_LABEL_ACTIVITY_DELEGATE_DATE' => 'Дата на делегиране на дейността',
    'LBL_PMSE_LABEL_ACTIVITY_START_DATE' => 'Начална дата',
    'LBL_PMSE_LABEL_EXPECTED_TIME' => 'Очаквано време',
    'LBL_PMSE_LABEL_DUE_DATE' => 'Падежна дата',
    'LBL_PMSE_LABEL_CURRENT' => 'Текущ',
    'LBL_PMSE_LABEL_OVERDUE' => 'Просрочени',
    'LBL_PMSE_LABEL_PROCESS' => 'Процес',
    'LBL_PMSE_LABEL_PROCESS_AUTHOR' => 'Advanced Workflow',
    'LBL_PMSE_LABEL_UNASSIGNED' => 'Неприсвоена',

    'LBL_RECORD_NAME'  => "Име на записа",
    'LBL_PROCESS_NAME'  => "Име на процеса",
    'LBL_PROCESS_DEFINITION_NAME'  => "Име на дефиницията на процеса",
    'LBL_OWNER' => 'Отговорник:',
    'LBL_ACTIVITY_OWNER'=>'Отговорник на процеса',
    'LBL_PROCESS_OWNER'=>'Собственик на процеса',
    'LBL_STATUS_COMPLETED' => 'Приключени процеси',
    'LBL_STATUS_TERMINATED' => 'Прекратени процеси',
    'LBL_STATUS_IN_PROGRESS' => 'Процеси в процес на изпълнение',
    'LBL_STATUS_CANCELLED' => 'Отказани процеси',
    'LBL_STATUS_ERROR' => 'Грешки при изпълнение на процеси',

    'LBL_PMSE_TITLE_PROCESSESS_LIST'  => 'Управление на процеси',
    'LBL_PMSE_TITLE_UNATTENDED_CASES' => 'Процеси оставени без надзор',
    'LBL_PMSE_TITLE_REASSIGN' => 'Проемни отговорника на записа',
    'LBL_PMSE_TITLE_AD_HOC' => 'Преразпредели дейността',
    'LBL_PMSE_TITLE_ACTIVITY_TO_REASSIGN' => "Преразпредели дейността",
    'LBL_PMSE_TITLE_HISTORY' => 'История на процеса',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR' => 'Процес #%s: Текущ статус',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR_OBJ' => 'Процес #{{id}}: Текущ статус',
    'LBL_PMSE_TITLE_LOG_VIEWER' => 'Разглеждане на журнали на Advanced Workflow',
    'LBL_PMSE_TITLE_PROCESS_NOTES' => 'Бележки към процеса',

    'LBL_PMSE_MY_PROCESSES' => 'Моите процеси',
    'LBL_PMSE_SELF_SERVICE_PROCESSES' => 'Процеси на самооблужване',

    'LBL_PMSE_ACTIVITY_STREAM_APPROVE'=>"&0 от <strong>%s</strong> Одобрен",
    'LBL_PMSE_ACTIVITY_STREAM_REJECT'=>"&0 от <strong>%s</strong> Отхвърлен",
    'LBL_PMSE_ACTIVITY_STREAM_ROUTE'=>'&0 в <strong>%s</strong> Маршрутизирани',
    'LBL_PMSE_ACTIVITY_STREAM_CLAIM'=>"&0 в <strong>%s</strong> с предявени претенции",
    'LBL_PMSE_ACTIVITY_STREAM_REASSIGN'=>"&0 в  <strong>%s</strong> разпределени на потребител &1",
    'LBL_PMSE_CANCEL_MESSAGE' => "Сигурни ли сте, че желаете да откажете Процес номер #{}?",
    'LBL_ASSIGNED_USER'=>"Отговорник",
    'LBL_PMSE_SETTING_NUMBER_CYCLES' => "Брой грешни цикли",
    'LBL_PMSE_SHOW_PROCESS' => 'Покажи процес',
    'LBL_PMSE_FILTER' => 'Филтър',

    'LBL_PA_PROCESS_APPROVE_QUESTION' => 'Сигурни ли сте, че искате да одобрите този процес?',
    'LBL_PA_PROCESS_REJECT_QUESTION' => 'Сигурни ли сте, че искате да отхвърлите този процес?',
    'LBL_PA_PROCESS_ROUTE_QUESTION' => 'Сигурни ли сте, че искате да маршрутизирате този процес?',
    'LBL_PA_PROCESS_APPROVED_SUCCESS' => 'Процесът е одобрен успешно',
    'LBL_PA_PROCESS_REJECTED_SUCCESS' => 'Процесът е отхвърлен успешно',
    'LBL_PA_PROCESS_ROUTED_SUCCESS' => 'Процесът е маршрутизиран успешно',
    'LBL_PA_PROCESS_CLOSED' => 'Процесът, който се опитвате да видите, е приключил.',
    'LBL_PA_PROCESS_UNAVAILABLE' => 'Процесът, който се опитвате да видите, не е наличен в момента.',

    'LBL_PMSE_ASSIGN_USER' => 'Отговорник',
    'LBL_PMSE_ASSIGN_USER_APPLIED' => 'Приложено е присвояване на потребител',

    'LBL_PMSE_LABEL_PREVIEW' => 'Визуализация на дизайна на процеса',
);

