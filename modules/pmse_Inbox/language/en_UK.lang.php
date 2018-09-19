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
    'LNK_PMSE_INBOX_PROCESS_MANAGEMENT' => 'Processes List',
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

    'LBL_PMSE_HISTORY_LOG_START_EVENT' => "%s a(n) %s record, which caused Advanced Workflow to trigger a Process #%s",
    'LBL_PMSE_HISTORY_LOG_GATEWAY'  => "The %s %s %s was evaluated and routed to the next task(s)",
    'LBL_PMSE_HISTORY_LOG_EVENT'  => "The %s Event %s was %s",
    'LBL_PMSE_HISTORY_LOG_END_EVENT'  => "End",
    'LBL_PMSE_HISTORY_LOG_CREATED'  => "created",
    'LBL_PMSE_HISTORY_LOG_MODIFIED'  => "modified",
    'LBL_PMSE_HISTORY_LOG_STARTED'  => "started",
    'LBL_PMSE_HISTORY_LOG_PROCESSED'  => "processed",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY_SELF_SERVICE'  => "The Activity %s on the %s record was available for Self Service",
    'LBL_PMSE_HISTORY_LOG_ACTIVITY'  => "%s the Activity %s on the %s record",
    'LBL_PMSE_HISTORY_LOG_ASSIGNED'  => "was assigned",
    'LBL_PMSE_HISTORY_LOG_ROUTED'  => "routed",
    'LBL_PMSE_HISTORY_LOG_ACTION'  => "The %s Activity %s was processed on the %s record",
    'LBL_PMSE_HISTORY_LOG_ASSIGN_USER_ACTION'  => "was assigned to Process #%s %s the %s record by the %s Activity %s",
    'LBL_PMSE_HISTORY_LOG_ON'  => "on",
    'LBL_PMSE_HISTORY_LOG_AND'  => "and",

    'LBL_PMSE_LABEL_APPROVE' => 'Approve',
    'LBL_PMSE_LABEL_REJECT' => 'Reject',
    'LBL_PMSE_LABEL_ROUTE' => 'Route',
    'LBL_PMSE_LABEL_CLAIM' => 'Claim',
    'LBL_PMSE_LABEL_STATUS' => 'Status',
    'LBL_PMSE_LABEL_REASSIGN' => 'Select New Process User',
    'LBL_PMSE_LABEL_CHANGE_OWNER' => 'Change Assigned To User',
    'LBL_PMSE_LABEL_EXECUTE' => 'Execute',
    'LBL_PMSE_LABEL_CANCEL' => 'Cancel',
    'LBL_PMSE_LABEL_HISTORY' => 'History',
    'LBL_PMSE_LABEL_NOTES' => 'Show Notes',
    'LBL_PMSE_LABEL_ADD_NOTES' => 'Add Notes',

    'LBL_PMSE_FORM_OPTION_SELECT' => 'Select...',
    'LBL_PMSE_FORM_LABEL_USER' => 'User',
    'LBL_PMSE_FORM_LABEL_TYPE' => 'Type',
    'LBL_PMSE_FORM_LABEL_NOTE' => 'Note',

    'LBL_PMSE_BUTTON_SAVE' => 'Save',
    'LBL_PMSE_BUTTON_CLOSE' => 'Close',
    'LBL_PMSE_BUTTON_CANCEL' => 'Cancel',
    'LBL_PMSE_BUTTON_REFRESH' => 'Refresh',
    'LBL_PMSE_BUTTON_CLEAR' => 'Clear',
    'LBL_PMSE_WARNING_CLEAR' => 'Are you sure you want to clear log data? This cannot be undone.',

    'LBL_PMSE_FORM_TOOLTIP_SELECT_USER' => 'Assigns this process to the user.',
    'LBL_PMSE_FORM_TOOLTIP_CHANGE_USER' => 'Updates "Assigned To" field on the record to this user.',

    'LBL_PMSE_ALERT_REASSIGN_UNSAVED_FORM' => 'There are unsaved changes in the current form, they will be discarded if you reassign the current task. Do you want to proceed?',
    'LBL_PMSE_ALERT_REASSIGN_SUCCESS' => 'The process was successfully reassigned',

    'LBL_PMSE_LABEL_CURRENT_ACTIVITY' => 'Current Activity',
    'LBL_PMSE_LABEL_ACTIVITY_DELEGATE_DATE' => 'Activity Delegate Date',
    'LBL_PMSE_LABEL_ACTIVITY_START_DATE' => 'Start Date',
    'LBL_PMSE_LABEL_EXPECTED_TIME' => 'Expected Time',
    'LBL_PMSE_LABEL_DUE_DATE' => 'Due Date',
    'LBL_PMSE_LABEL_CURRENT' => 'Current',
    'LBL_PMSE_LABEL_OVERDUE' => 'Overdue',
    'LBL_PMSE_LABEL_PROCESS' => 'Process',
    'LBL_PMSE_LABEL_PROCESS_AUTHOR' => 'Advanced Workflow',
    'LBL_PMSE_LABEL_UNASSIGNED' => 'Unassigned',

    'LBL_RECORD_NAME'  => "Record Name",
    'LBL_PROCESS_NAME'  => "Process Name",
    'LBL_PROCESS_DEFINITION_NAME'  => "Process Definition Name",
    'LBL_OWNER' => 'Assigned To',
    'LBL_ACTIVITY_OWNER'=>'Process User',
    'LBL_PROCESS_OWNER'=>'Process Owner',
    'LBL_STATUS_COMPLETED' => 'Processes Completed',
    'LBL_STATUS_TERMINATED' => 'Processes Terminated',
    'LBL_STATUS_IN_PROGRESS' => 'Processes In Progress',
    'LBL_STATUS_CANCELLED' => 'Processes Cancelled',
    'LBL_STATUS_ERROR' => 'Processes Error',

    'LBL_PMSE_TITLE_PROCESSESS_LIST'  => 'Processes List',
    'LBL_PMSE_TITLE_UNATTENDED_CASES' => 'Unattended Processes',
    'LBL_PMSE_TITLE_REASSIGN' => 'Change Assigned To User',
    'LBL_PMSE_TITLE_AD_HOC' => 'Select New Process User',
    'LBL_PMSE_TITLE_ACTIVITY_TO_REASSIGN' => "Select New Process User",
    'LBL_PMSE_TITLE_HISTORY' => 'Process History',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR' => 'Process #%s: Current Status',
    'LBL_PMSE_TITLE_IMAGE_GENERATOR_OBJ' => 'Process #{{id}}: Current Status',
    'LBL_PMSE_TITLE_LOG_VIEWER' => 'Advanced Workflow Log Viewer',
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
    'LBL_PA_PROCESS_CLOSED' => 'The process you are trying to view is closed.',
    'LBL_PA_PROCESS_UNAVAILABLE' => 'The process you are trying view is not available at this moment.',

    'LBL_PMSE_ASSIGN_USER' => 'Assign User',
    'LBL_PMSE_ASSIGN_USER_APPLIED' => 'Assign User Applied',

    'LBL_PMSE_LABEL_PREVIEW' => 'Process Design Preview',
);

