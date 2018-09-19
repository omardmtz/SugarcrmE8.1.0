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
    'LBL_MODULE_NAME' => 'Processes',
    'LBL_MODULE_TITLE' => 'Processes',
    'LBL_MODULE_NAME_SINGULAR' => 'Process',
    'LNK_LIST' => 'View Processes',
    'LNK_PMSE_INBOX_PROCESS_MANAGEMENT' => 'Process Management',
    'LNK_PMSE_INBOX_UNATTENDED_PROCESSES' => 'Unattended Processes',

    'LBL_CAS_ID' => 'Process Number',
    'LBL_PMSE_HISTORY_LOG_NOTFOUND_USER' => "Unknown (according UserId:'%s')",
    'LBL_PMSE_HISTORY_LOG_TASK_HAS_BEEN' => "task has been",
    'LBL_PMSE_HISTORY_LOG_TASK_WAS' => "task was ",
    'LBL_PMSE_HISTORY_LOG_EDITED' => "edited",
    'LBL_PMSE_HISTORY_LOG_CREATED' => "created",
    'LBL_PMSE_HISTORY_LOG_ROUTED' => "routed",
    'LBL_PMSE_HISTORY_LOG_DONE_UNKNOWN' => "Done an unknown task",
    'LBL_PMSE_HISTORY_LOG_CREATED_CASE' => "has created Process #%s ",
    'LBL_PMSE_HISTORY_LOG_DERIVATED_CASE' => "has been assigned Process #%s ",
    'LBL_PMSE_HISTORY_LOG_CURRENTLY_HAS_CASE' => "currently has Process #%s ",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_NAME' => "'%s'",
    'LBL_PMSE_HISTORY_LOG_ACTION_PERFORMED'  => ". The action performed was: <span style=\"font-weight: bold\">[%s]</span>",
    'LBL_PMSE_HISTORY_LOG_ACTION_STILL_ASSIGNED' => " The task is still assigned",
    'LBL_PMSE_HISTORY_LOG_MODULE_ACTION'  => " on the %s record %s",
    'LBL_PMSE_HISTORY_LOG_WITH_EVENT'  => " with the event %s",
    'LBL_PMSE_HISTORY_LOG_WITH_GATEWAY'  => ". The Gateway %s was evaluated and routed to the next task ",
    'LBL_PMSE_HISTORY_LOG_NOT_REGISTERED_ACTION'  => "not registered action",
    'LBL_PMSE_HISTORY_LOG_NO_YET_STARTED' => '(not yet started)',
    'LBL_PMSE_HISTORY_LOG_FLOW' => 'has been assigned to continue the task',

    'LBL_PMSE_HISTORY_LOG_START_EVENT' => "%s %s záznam, ktorý spôsobil, že nástroj Advanced Workflow spustil proces #%s",
    'LBL_PMSE_HISTORY_LOG_GATEWAY'  => "%s %s %s bolo vyhodnotené a smerované na nasledujúce úlohy",
    'LBL_PMSE_HISTORY_LOG_EVENT'  => "%s udalosť %s bola %s",
    'LBL_PMSE_HISTORY_LOG_END_EVENT'  => "Koniec",
    'LBL_PMSE_HISTORY_LOG_CREATED'  => "vytvorené",
    'LBL_PMSE_HISTORY_LOG_MODIFIED'  => "upravené",
    'LBL_PMSE_HISTORY_LOG_STARTED'  => "spustené",
    'LBL_PMSE_HISTORY_LOG_PROCESSED'  => "spracované",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_SELF_SERVICE'  => "Aktivita %s v %s zázname bola k dispozícii pre samoobslužnú službu",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY'  => "%s aktivita %s v %s zázname",
    'LBL_PMSE_HISTORY_LOG_ASSIGNED'  => "bolo priradené",
    'LBL_PMSE_HISTORY_LOG_ROUTED'  => "smerované",
    'LBL_PMSE_HISTORY_LOG_ACTION'  => "%s akcia %s bola spracovaná v %s zázname",
    'LBL_PMSE_HISTORY_LOG_ASSIGN_USER_ACTION'  => "bol priradený proces #%s %s %s záznam %s akciou %s",
    'LBL_PMSE_HISTORY_LOG_ON'  => "dňa",
    'LBL_PMSE_HISTORY_LOG_AND'  => "a",

    'LBL_PMSE_LABEL_APPROVE' => 'Approve',
    'LBL_PMSE_LABEL_REJECT' => 'Reject',
    'LBL_PMSE_LABEL_ROUTE' => 'Route',
    'LBL_PMSE_LABEL_CLAIM' => 'Claim',
    'LBL_PMSE_LABEL_STATUS' => 'Stav',
    'LBL_PMSE_LABEL_REASSIGN' => 'Select New Process User',
    'LBL_PMSE_LABEL_CHANGE_OWNER' => 'Change Assigned To User',
    'LBL_PMSE_LABEL_EXECUTE' => 'Vykonať',
    'LBL_PMSE_LABEL_CANCEL' => 'Zrušiť',
    'LBL_PMSE_LABEL_HISTORY' => 'História',
    'LBL_PMSE_LABEL_NOTES' => 'Show Notes',
    'LBL_PMSE_LABEL_ADD_NOTES' => 'Add Notes',

    'LBL_PMSE_FORM_OPTION_SELECT' => 'Vyberte ...',
    'LBL_PMSE_FORM_LABEL_USER' => 'Užívateľ',
    'LBL_PMSE_FORM_LABEL_TYPE' => 'Typ',
    'LBL_PMSE_FORM_LABEL_NOTE' => 'Poznámka',

    'LBL_PMSE_BUTTON_SAVE' => 'Uložiť',
    'LBL_PMSE_BUTTON_CLOSE' => 'Zavrieť',
    'LBL_PMSE_BUTTON_CANCEL' => 'Zrušiť',
    'LBL_PMSE_BUTTON_REFRESH' => 'Obnoviť',
    'LBL_PMSE_BUTTON_CLEAR' => 'Zmazať',
    'LBL_PMSE_WARNING_CLEAR' => 'Are you sure you want to clear log data? This cannot be undone.',

    'LBL_PMSE_FORM_TOOLTIP_SELECT_USER' => 'Assigns this process to the user.',
    'LBL_PMSE_FORM_TOOLTIP_CHANGE_USER' => 'Updates "Assigned To" field on the record to this user.',

    'LBL_PMSE_ALERT_REASSIGN_UNSAVED_FORM' => 'There are unsaved changes in the current form, they will be discarded if you reassign the current task. Do you want to proceed?',
    'LBL_PMSE_ALERT_REASSIGN_SUCCESS' => 'The process was successfully reassigned',

    'LBL_PMSE_LABEL_CURRENT_ACTIVITY' => 'Current Activity',
    'LBL_PMSE_LABEL_ACTIVITY_DELEGATE_DATE' => 'Activity Delegate Date',
    'LBL_PMSE_LABEL_ACTIVITY_START_DATE' => 'Dátum začiatku',
    'LBL_PMSE_LABEL_EXPECTED_TIME' => 'Expected Time',
    'LBL_PMSE_LABEL_DUE_DATE' => 'Do dátumu',
    'LBL_PMSE_LABEL_CURRENT' => 'Current',
    'LBL_PMSE_LABEL_OVERDUE' => 'Oneskorené',
    'LBL_PMSE_LABEL_PROCESS' => 'Process',
    'LBL_PMSE_LABEL_PROCESS_AUTHOR' => 'Advanced Workflow',
    'LBL_PMSE_LABEL_UNASSIGNED' => 'Unassigned',

    'LBL_RECORD_NAME'  => "Record Name",
    'LBL_PROCESS_NAME'  => "Process Name",
    'LBL_PROCESS_DEFINITION_NAME'  => "Process Definition Name",
    'LBL_OWNER' => 'Priradený k',
    'LBL_ACTIVITY_OWNER'=>'Process User',
    'LBL_PROCESS_OWNER'=>'Process Owner',
    'LBL_STATUS_COMPLETED' => 'Processes Completed',
    'LBL_STATUS_TERMINATED' => 'Processes Terminated',
    'LBL_STATUS_IN_PROGRESS' => 'Processes In Progress',
    'LBL_STATUS_CANCELLED' => 'Processes Cancelled',
    'LBL_STATUS_ERROR' => 'Processes Error',

    'LBL_PMSE_TITLE_PROCESSESS_LIST'  => 'Process Management',
    'LBL_PMSE_TITLE_UNATTENDED_CASES' => 'Unattended Processes',
    'LBL_PMSE_TITLE_REASSIGN' => 'Change Assigned To User',
    'LBL_PMSE_TITLE_AD_HOC' => 'Select New Process User',
    'LBL_PMSE_TITLE_ACTIVITY_TO_REASSIGN' => "Select New Process User",
    'LBL_PMSE_TITLE_HISTORY' => 'Process History',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR' => 'Process #%s: Current Status',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR_OBJ' => 'Proces #{{id}}: aktuálny stav',
    'LBL_PMSE_TITLE_LOG_VIEWER' => 'Prehliadač denníkov nástroja Advanced Workflow',
    'LBL_PMSE_TITLE_PROCESS_NOTES' => 'Process Notes',

    'LBL_PMSE_MY_PROCESSES' => 'My Processes',
    'LBL_PMSE_SELF_SERVICE_PROCESSES' => 'Self Service Processes',

    'LBL_PMSE_ACTIVITY_STREAM_APPROVE'=>"&0 on <strong>%s</strong> Approved ",
    'LBL_PMSE_ACTIVITY_STREAM_REJECT'=>"&0 on <strong>%s</strong> Rejected ",
    'LBL_PMSE_ACTIVITY_STREAM_ROUTE'=>'&0 on <strong>%s</strong> Routed ',
    'LBL_PMSE_ACTIVITY_STREAM_CLAIM'=>"&0 on <strong>%s</strong> Claimed ",
    'LBL_PMSE_ACTIVITY_STREAM_REASSIGN'=>"&0 on <strong>%s</strong> assigned to user &1 ",
    'LBL_PMSE_CANCEL_MESSAGE' => "Are you sure you want to cancel Process Number #{}?",
    'LBL_ASSIGNED_USER'=>"User Assigned",
    'LBL_PMSE_SETTING_NUMBER_CYCLES' => "Error Number of Cycles",
    'LBL_PMSE_SHOW_PROCESS' => 'Show Process',
    'LBL_PMSE_FILTER' => 'Filter',

    'LBL_PA_PROCESS_APPROVE_QUESTION' => 'Are you sure you want to approve this process?',
    'LBL_PA_PROCESS_REJECT_QUESTION' => 'Are you sure you want to reject this process?',
    'LBL_PA_PROCESS_ROUTE_QUESTION' => 'Are you sure you want to route this process?',
    'LBL_PA_PROCESS_APPROVED_SUCCESS' => 'Process has been approved successfully',
    'LBL_PA_PROCESS_REJECTED_SUCCESS' => 'Process has been rejected successfully',
    'LBL_PA_PROCESS_ROUTED_SUCCESS' => 'Process has been routed successfully',
    'LBL_PA_PROCESS_CLOSED' => 'Proces, ktorý skúšate zobraziť, je zatvorený.',
    'LBL_PA_PROCESS_UNAVAILABLE' => 'Proces, ktorý skúšate zobraziť, nie je teraz k dispozícii.',

    'LBL_PMSE_ASSIGN_USER' => 'Assign User',
    'LBL_PMSE_ASSIGN_USER_APPLIED' => 'Assign User Applied',

    'LBL_PMSE_LABEL_PREVIEW' => 'Náhľad návrhu procesu',
);

