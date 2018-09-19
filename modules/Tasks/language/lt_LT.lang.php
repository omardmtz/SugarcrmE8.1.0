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
  'LBL_TASKS_LIST_DASHBOARD' => 'Užduočių sąrašo ataskaitų sritis',

  'LBL_MODULE_NAME' => 'Užduotys',
  'LBL_MODULE_NAME_SINGULAR' => 'Užduotis',
  'LBL_TASK' => 'Užduotys:',
  'LBL_MODULE_TITLE' => 'Užduotys: Pradžia',
  'LBL_SEARCH_FORM_TITLE' => 'Užduoties paieška',
  'LBL_LIST_FORM_TITLE' => 'Užduočių sąrašas',
  'LBL_NEW_FORM_TITLE' => 'Sukurti užduotį',
  'LBL_NEW_FORM_SUBJECT' => 'Tema:',
  'LBL_NEW_FORM_DUE_DATE' => 'Atlikimo data:',
  'LBL_NEW_FORM_DUE_TIME' => 'Atlikimo laikas:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Užbaigti',
  'LBL_LIST_SUBJECT' => 'Tema',
  'LBL_LIST_CONTACT' => 'Kontaktas',
  'LBL_LIST_PRIORITY' => 'Svarba',
  'LBL_LIST_RELATED_TO' => 'Susiję su',
  'LBL_LIST_DUE_DATE' => 'Atlikimo data',
  'LBL_LIST_DUE_TIME' => 'Atlikimo laikas',
  'LBL_SUBJECT' => 'Tema:',
  'LBL_STATUS' => 'Statusas:',
  'LBL_DUE_DATE' => 'Atlikimo data:',
  'LBL_DUE_TIME' => 'Atlikimo laikas:',
  'LBL_PRIORITY' => 'Svarba:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Atlikimo data ir laikas:',
  'LBL_START_DATE_AND_TIME' => 'Pradžios data ir laikas:',
  'LBL_START_DATE' => 'Pradžios data:',
  'LBL_LIST_START_DATE' => 'Pradžios data',
  'LBL_START_TIME' => 'Pradžios laikas:',
  'LBL_LIST_START_TIME' => 'Pradžios laikas',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => 'Nėra',
  'LBL_CONTACT' => 'Kontaktas:',
  'LBL_EMAIL_ADDRESS' => 'El. paštas:',
  'LBL_PHONE' => 'Telefonas:',
  'LBL_EMAIL' => 'El. paštas:',
  'LBL_DESCRIPTION_INFORMATION' => 'Aprašymo informacija',
  'LBL_DESCRIPTION' => 'Aprašymas:',
  'LBL_NAME' => 'Vardas:',
  'LBL_CONTACT_NAME' => 'Kontakto vardas:',
  'LBL_LIST_COMPLETE' => 'Užbaigtas:',
  'LBL_LIST_STATUS' => 'Statusas',
  'LBL_DATE_DUE_FLAG' => 'Nėra atlikimo datos',
  'LBL_DATE_START_FLAG' => 'Nėra atlikimo laiko',
  'ERR_DELETE_RECORD' => 'Jūs turite nurodyti įrašą, kad galėtumėte ištrinti kontaktą.',
  'ERR_INVALID_HOUR' => 'Prašome įvesti valandas nuo 0 iki 24',
  'LBL_DEFAULT_PRIORITY' => 'Vidutinė',
  'LBL_LIST_MY_TASKS' => 'Mano užduotys',
  'LNK_NEW_TASK' => 'Sukurti užduotį',
  'LNK_TASK_LIST' => 'Užduotys',
  'LNK_IMPORT_TASKS' => 'Importuoti užduotis',
  'LBL_CONTACT_FIRST_NAME'=>'Kontakto vardas',
  'LBL_CONTACT_LAST_NAME'=>'Kontakto pavardė',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Atsakingas',
  'LBL_ASSIGNED_TO_NAME'=>'Atsakingas',
  'LBL_LIST_DATE_MODIFIED' => 'Redaguota',
  'LBL_CONTACT_ID' => 'Kontakto ID:',
  'LBL_PARENT_ID' => 'Pagrindinis ID:',
  'LBL_CONTACT_PHONE' => 'Kontakto telefonas:',
  'LBL_PARENT_NAME' => 'Tėvo tipas:',
  'LBL_ACTIVITIES_REPORTS' => 'Priminimų ataskaita',
  'LBL_EDITLAYOUT' => 'Redaguoti išdėstymą' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Užduoties informacija',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Užrašai',
  'LBL_REVENUELINEITEMS' => 'Revenue Line Items',
  //For export labels
  'LBL_DATE_DUE' => 'Užbaigimo data',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Atsakingas',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Atsakingo ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Redaguotojo ID',
  'LBL_EXPORT_CREATED_BY' => 'Sukūrėjo ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Susijęs su moduliu',
  'LBL_EXPORT_PARENT_ID' => 'Susijęs su ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Task closed successfully.',
  'LBL_ASSIGNED_USER' => 'Atsakingas',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Užrašai',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion. {{module_name}} records can be related to one record in most modules via the flex relate field and can also be related to a single {{contacts_singular_module}}. There are various ways you can create {{plural_module_name}} in Sugar such as via the {{plural_module_name}} module, duplication, importing {{plural_module_name}}, etc. Once the {{module_name}} record is created, you can view and edit information pertaining to the {{module_name}} via the {{plural_module_name}} record view. Depending on the details on the {{module_name}}, you may also be able to view and edit the {{module_name}} information via the Calendar module. Each {{module_name}} record may then relate to other Sugar records such as {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, and many others.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

- Edit this record&#39;s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'The {{plural_module_name}} module consists of flexible actions, to-do items, or other type of activity which requires completion.

To create a {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalize the new record and return to the previous page.',

);
