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
    'LBL_NOTES_LIST_DASHBOARD' => 'Užrašų sąrašo ataskaitų sritis',

    'ERR_DELETE_RECORD' => 'Jūs turite nurodyti įrašo numerį, kad galėtumėte ištrinti klientą.',
    'LBL_ACCOUNT_ID' => 'Kliento ID:',
    'LBL_CASE_ID' => 'Aptarnavimo ID:',
    'LBL_CLOSE' => 'Uždaryti:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontakto ID:',
    'LBL_CONTACT_NAME' => 'Kontaktas:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Užrašai',
    'LBL_DESCRIPTION' => 'Aprašymas',
    'LBL_EMAIL_ADDRESS' => 'El. paštas:',
    'LBL_EMAIL_ATTACHMENT' => 'Prisegtukas',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'El. pašto priedas, skirtas',
    'LBL_FILE_MIME_TYPE' => 'Mime tipas',
    'LBL_FILE_EXTENSION' => 'Failo prievardis',
    'LBL_FILE_SOURCE' => 'Failo kilmė',
    'LBL_FILE_SIZE' => 'Failo dydis',
    'LBL_FILE_URL' => 'Failo nuoroda',
    'LBL_FILENAME' => 'Priedas',
    'LBL_LEAD_ID' => 'Potencialaus kontakto ID:',
    'LBL_LIST_CONTACT_NAME' => 'Kontaktas',
    'LBL_LIST_DATE_MODIFIED' => 'Redagavimo data',
    'LBL_LIST_FILENAME' => 'Prisegtukas',
    'LBL_LIST_FORM_TITLE' => 'Užrašų sąrašas',
    'LBL_LIST_RELATED_TO' => 'Susijęs su',
    'LBL_LIST_SUBJECT' => 'Tema',
    'LBL_LIST_STATUS' => 'Statusas',
    'LBL_LIST_CONTACT' => 'Kontaktas',
    'LBL_MODULE_NAME' => 'Užrašai',
    'LBL_MODULE_NAME_SINGULAR' => 'Užrašas',
    'LBL_MODULE_TITLE' => 'Užrašai: Pradžia',
    'LBL_NEW_FORM_TITLE' => 'Sukurti užrašą',
    'LBL_NEW_FORM_BTN' => 'Sukurti užrašą',
    'LBL_NOTE_STATUS' => 'Užrašas',
    'LBL_NOTE_SUBJECT' => 'Užrašo tema:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Prisegtukai',
    'LBL_NOTE' => 'Pastaba:',
    'LBL_OPPORTUNITY_ID' => 'Pardavimo ID:',
    'LBL_PARENT_ID' => 'Pagrindinis ID:',
    'LBL_PARENT_TYPE' => 'Pagrindio tipas',
    'LBL_EMAIL_TYPE' => 'El. pašto tipas',
    'LBL_EMAIL_ID' => 'El. pašto ID',
    'LBL_PHONE' => 'Telefonas:',
    'LBL_PORTAL_FLAG' => 'Rodyti portale?',
    'LBL_EMBED_FLAG' => 'Įterpti į laišką?',
    'LBL_PRODUCT_ID' => 'Produkto ID:',
    'LBL_QUOTE_ID' => 'Pasiūlymo ID:',
    'LBL_RELATED_TO' => 'Susijęs su:',
    'LBL_SEARCH_FORM_TITLE' => 'Užrašų paieška',
    'LBL_STATUS' => 'Statusas',
    'LBL_SUBJECT' => 'Tema:',
    'LNK_IMPORT_NOTES' => 'Importuoti užrašus',
    'LNK_NEW_NOTE' => 'Sukurti užrašą',
    'LNK_NOTE_LIST' => 'Užrašai',
    'LBL_MEMBER_OF' => 'Narys:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Atsakingas',
    'LBL_OC_FILE_NOTICE' => 'Prašome prisijungti prie serverio, kad pamatyti failą',
    'LBL_REMOVING_ATTACHMENT' => 'Išimamas prisegtukas...',
    'ERR_REMOVING_ATTACHMENT' => 'Nepavyko išimti prisegtuko...',
    'LBL_CREATED_BY' => 'Sukūrė',
    'LBL_MODIFIED_BY' => 'Redagavo',
    'LBL_SEND_ANYWAYS' => 'Šis laiškas neturi temos. Vis tiek siųsti/saugoti?',
    'LBL_LIST_EDIT_BUTTON' => 'Redaguoti',
    'LBL_ACTIVITIES_REPORTS' => 'Priminimų ataskaita',
    'LBL_PANEL_DETAILS' => 'Išsamiau',
    'LBL_NOTE_INFORMATION' => 'Užrašo informacija',
    'LBL_MY_NOTES_DASHLETNAME' => 'Mano užrašai',
    'LBL_EDITLAYOUT' => 'Redaguoti išdėstymą' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Vardas',
    'LBL_LAST_NAME' => 'Pavardė:',
    'LBL_EXPORT_PARENT_TYPE' => 'Susijęs su moduliu',
    'LBL_EXPORT_PARENT_ID' => 'Susijęs su ID',
    'LBL_DATE_ENTERED' => 'Sukurta',
    'LBL_DATE_MODIFIED' => 'Redaguota',
    'LBL_DELETED' => 'Ištrintas',
    'LBL_REVENUELINEITEMS' => 'Revenue Line Items',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'The {{plural_module_name}} module consists of individual {{plural_module_name}} that contain text or an attachment pertinent to the related record. {{module_name}} records can be related to one record in most modules via the flex relate field and can also be related to a single {{contacts_singular_module}}. {{plural_module_name}} can hold generic text about a record or even an attachment related to the record. There are various ways you can create {{plural_module_name}} in Sugar such as via the {{plural_module_name}} module, importing {{plural_module_name}}, via History subpanels, etc. Once the {{module_name}} record is created, you can view and edit information pertaining to the {{module_name}} via the {{plural_module_name}} record view. Each {{module_name}} record may then relate to other Sugar records such as {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, and many others.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'The {{plural_module_name}} module consists of individual {{plural_module_name}} that contain text or an attachment pertinent to the related record.

- Edit this record&#39;s fields by clicking an individual field or the Edit button.
- View or modify links to other records in the subpanels by toggling the bottom left pane to "Data View".
- Make and view user comments and record change history in the {{activitystream_singular_module}} by toggling the bottom left pane to "Activity Stream".
- Follow or favorite this record using the icons to the right of the record name.
- Additional actions are available in the dropdown Actions menu to the right of the Edit button.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'To create a {{module_name}}:
1. Provide values for the fields as desired.
 - Fields marked "Required" must be completed prior to saving.
 - Click "Show More" to expose additional fields if necessary.
2. Click "Save" to finalize the new record and return to the previous page.',
);
