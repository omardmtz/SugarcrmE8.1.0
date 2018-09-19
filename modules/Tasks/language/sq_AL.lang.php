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
  'LBL_TASKS_LIST_DASHBOARD' => 'Paneli i listës së detyrave',

  'LBL_MODULE_NAME' => 'Detyrat',
  'LBL_MODULE_NAME_SINGULAR' => 'Detyrë',
  'LBL_TASK' => 'Detyrat',
  'LBL_MODULE_TITLE' => 'Detyrat: Ballina',
  'LBL_SEARCH_FORM_TITLE' => 'Kërkimi i detyrave',
  'LBL_LIST_FORM_TITLE' => 'Lista e detyrave',
  'LBL_NEW_FORM_TITLE' => 'Krijo detyrë',
  'LBL_NEW_FORM_SUBJECT' => 'Subjekti:',
  'LBL_NEW_FORM_DUE_DATE' => 'Data e caktuar',
  'LBL_NEW_FORM_DUE_TIME' => 'ora e caktuar',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Mbyll:',
  'LBL_LIST_SUBJECT' => 'subjekti',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'prioriteti',
  'LBL_LIST_RELATED_TO' => 'në lidhje me',
  'LBL_LIST_DUE_DATE' => 'Data e caktuar',
  'LBL_LIST_DUE_TIME' => 'Ora e caktuar',
  'LBL_SUBJECT' => 'Subjekti',
  'LBL_STATUS' => 'Statusi',
  'LBL_DUE_DATE' => 'Data e caktuar',
  'LBL_DUE_TIME' => 'Ora e caktuar',
  'LBL_PRIORITY' => 'Priorieti:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Data dhe ora e caktuar',
  'LBL_START_DATE_AND_TIME' => 'Data dhe ora e nisjes',
  'LBL_START_DATE' => 'Data e nisjes',
  'LBL_LIST_START_DATE' => 'Data e nisjes',
  'LBL_START_TIME' => 'Ora e nisjes',
  'LBL_LIST_START_TIME' => 'Ora e nisjes',
  'DATE_FORMAT' => '(vvvvv-mm-dd)',
  'LBL_NONE' => 'Asnjëra',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'Email Adresa',
  'LBL_PHONE' => 'Telefoni',
  'LBL_EMAIL' => 'email adresa',
  'LBL_DESCRIPTION_INFORMATION' => 'Informacioni përshkrues',
  'LBL_DESCRIPTION' => 'Përshkrim',
  'LBL_NAME' => 'Emri',
  'LBL_CONTACT_NAME' => 'Emri i kontaktit',
  'LBL_LIST_COMPLETE' => 'Kompleto',
  'LBL_LIST_STATUS' => 'Statusi',
  'LBL_DATE_DUE_FLAG' => 'Nuk ka datë të caktuar',
  'LBL_DATE_START_FLAG' => 'Nuk ka datë të nisjes',
  'ERR_DELETE_RECORD' => 'Duhet përcaktuar numrin e regjistrimit për të fshirë konaktin',
  'ERR_INVALID_HOUR' => 'Ju lutemi shkruani një orë mes 0 dhe 24',
  'LBL_DEFAULT_PRIORITY' => 'Mesatar',
  'LBL_LIST_MY_TASKS' => 'Detyrat e mia të hapura',
  'LNK_NEW_TASK' => 'Krijo detyrë',
  'LNK_TASK_LIST' => 'Shiko detyrat',
  'LNK_IMPORT_TASKS' => 'Importo detyra',
  'LBL_CONTACT_FIRST_NAME'=>'Emri i kontaktit',
  'LBL_CONTACT_LAST_NAME'=>'Mbiemri i kontaktit',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Përdorues i caktuar',
  'LBL_ASSIGNED_TO_NAME'=>'Drejtuar:',
  'LBL_LIST_DATE_MODIFIED' => 'Të dhënat e modifikuara',
  'LBL_CONTACT_ID' => 'ID e Kontaktit',
  'LBL_PARENT_ID' => 'ID mëmë',
  'LBL_CONTACT_PHONE' => 'telekoni për kontakt',
  'LBL_PARENT_NAME' => 'Kategoria mëmë',
  'LBL_ACTIVITIES_REPORTS' => 'Raporti i aktiviteteve',
  'LBL_EDITLAYOUT' => 'Ndrysho formatin' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Pasqyra',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Shënimet',
  'LBL_REVENUELINEITEMS' => 'Rreshti i llojeve të të ardhurave',
  //For export labels
  'LBL_DATE_DUE' => 'data e caktuar',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Emri i përdoruesit të caktuar',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID e përdoruesit të caktuar',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Modifikuar për ID',
  'LBL_EXPORT_CREATED_BY' => 'Krijuar Nga ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Në lidhje me modulin',
  'LBL_EXPORT_PARENT_ID' => 'Në lidhje me ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Detyra është mbyllur me sukses.',
  'LBL_ASSIGNED_USER' => 'Drejtuar për',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Shënime',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} është moduli i cili gjurmon dhe menaxhon produktin apo shërbimin të lidhur me problemet e raportuara të organizatës tuaj nga klientët. {{plural_module_name}} janë tipikisht të lidhur me regjstrimin e {{plural_module_name}} dhe shumësi i {{plural_module_name}} mund të asocohen me njëjësin e {{accounts_singular_module}}. Ka disa mënyra për të krijuar  {{plural_module_name}} në Sugar siç është nëpërmjet modulit {{plural_module_name}}, importimi i  {{plural_module_name}} ose konvertimi nga emaili. Një herë që {{module_name}} është krijuar, ju mund ta shikoni dhe editoni informacionin lidhur me {{module_name}} nëpërmjet shikimit të {{module_name}}. Çdo regjistrim {{module_name}} mund të lidhet me regjistrimet e Sugar si {{calls_module}}, {{contacts_module}}, {{bugs_module}} dhe shumë të tjera.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moduli {{plural_module_name}} konsiston në kompanitë që organizata juaj ka lidhje dhe që përgjithsisht është parë si qendër për menaxhim dhe analizim të interaksioneve të biznesit tuaj me çdo klient. -Editoni këtë fushë regjistrimi duke klikuar në fushën individuale ose butonin Edit. -Shikoni ose modifikoni linkqet e regjistrimeve tjera në subpanelet duke shtypur butonin në anën e majtë të "Shikoni të dhënat". -Bëj dhe shiko komentet e përdoruesve dhe regjistro ndryshimin e historisë në  {{activitystream_singular_module}} duke shtypur butonin e majtë "Aktivitet". -Ndiq ose bëj favorit këtë regjistrim duke përdorur ikonat në të djathtë  të emrit të regjistrimit. -Veprime shtesë janë në dispozicion në fundin e menus së veprimeve në të djathë të butonit Edit.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Moduli {{plural_module_name}} konsiston në veprime fleksible, artikuj për t&#39;u punuar ose lloj tjetër aktiviteti që duhet kryer. 

Për të krijuar një {{module_name}}: 
1. Jep vlerat për fushat sipas dëshirës. 
- Fushat e shënuara me "Patjetër" duhet të plotësohen para se të ruhen. 
- Kliko "Trego më shumë" për të paraqitur fushat shtesë nëse është e nevojshme. 
2. Kliko "Ruaj" për të finalizuar regjistrimin e ri dhe për t&#39;u kthyer në faqen e mëparshme.',

);
