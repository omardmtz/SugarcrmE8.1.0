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
    'LBL_NOTES_LIST_DASHBOARD' => 'Paneli i listës së shënimeve',

    'ERR_DELETE_RECORD' => 'Duhet përcaktuar numrin e regjistrimit për të fshirë llogarinë',
    'LBL_ACCOUNT_ID' => 'ID e Llogarisë',
    'LBL_CASE_ID' => 'ID e Rastit',
    'LBL_CLOSE' => 'Mbyll:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'ID e Kontaktit',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Shënimet',
    'LBL_DESCRIPTION' => 'Shënim',
    'LBL_EMAIL_ADDRESS' => 'Adresa e Emailit',
    'LBL_EMAIL_ATTACHMENT' => 'bashkëngjitje të mailit',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Bashkëngjitja me email për',
    'LBL_FILE_MIME_TYPE' => 'Lloji pantomime',
    'LBL_FILE_EXTENSION' => 'Mbaresa e skedarit',
    'LBL_FILE_SOURCE' => 'Burimi i skedarit',
    'LBL_FILE_SIZE' => 'Madhësia e skedarit',
    'LBL_FILE_URL' => 'Paraqit URL',
    'LBL_FILENAME' => 'Bashkëngjitje:',
    'LBL_LEAD_ID' => 'ID e Klientit Potensial',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Ndryshimi i fundit',
    'LBL_LIST_FILENAME' => 'Bashkëngjitje',
    'LBL_LIST_FORM_TITLE' => 'Lista e shënimeve',
    'LBL_LIST_RELATED_TO' => 'në lidhje me',
    'LBL_LIST_SUBJECT' => 'subjekti',
    'LBL_LIST_STATUS' => 'Statusi',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Shënimet',
    'LBL_MODULE_NAME_SINGULAR' => 'Shënim',
    'LBL_MODULE_TITLE' => 'Shënimet: Ballina',
    'LBL_NEW_FORM_TITLE' => 'Krijo shënim ose bashkangjit një dokument',
    'LBL_NEW_FORM_BTN' => 'Shto shënim',
    'LBL_NOTE_STATUS' => 'Shënim',
    'LBL_NOTE_SUBJECT' => 'Subjekti (Tema)',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Bashkëngjitje',
    'LBL_NOTE' => 'Shënim',
    'LBL_OPPORTUNITY_ID' => 'ID e mudëshme',
    'LBL_PARENT_ID' => 'ID mëmë',
    'LBL_PARENT_TYPE' => 'Lloji mëmë',
    'LBL_EMAIL_TYPE' => 'Lloji i emailit',
    'LBL_EMAIL_ID' => 'Identifikuesi i emailit',
    'LBL_PHONE' => 'Telefoni',
    'LBL_PORTAL_FLAG' => 'shfaq në portal',
    'LBL_EMBED_FLAG' => 'ngulitur në mail?',
    'LBL_PRODUCT_ID' => 'ID e produktit',
    'LBL_QUOTE_ID' => 'ID e kuotës',
    'LBL_RELATED_TO' => 'Në lidhje me:',
    'LBL_SEARCH_FORM_TITLE' => 'Kërkimi i shënimeve',
    'LBL_STATUS' => 'Statusi',
    'LBL_SUBJECT' => 'Subjekti',
    'LNK_IMPORT_NOTES' => 'Shënime të rëndësishme',
    'LNK_NEW_NOTE' => 'Krijo shënim ose bashkëngjitje',
    'LNK_NOTE_LIST' => 'Shiko shënimet',
    'LBL_MEMBER_OF' => 'Anëtar i:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Përdorues i caktuar',
    'LBL_OC_FILE_NOTICE' => 'Ju lutemi kyquni në Server për të shikuar dosjen',
    'LBL_REMOVING_ATTACHMENT' => 'Heqje të bashkëngjitjes',
    'ERR_REMOVING_ATTACHMENT' => 'Heqja e bashkëngjitjes ka dështuar',
    'LBL_CREATED_BY' => 'Krijuar nga',
    'LBL_MODIFIED_BY' => 'Modifikuar nga',
    'LBL_SEND_ANYWAYS' => 'Kjo letër elektronike nuk ka temë. Dërgo/ruaj gjithsesi?',
    'LBL_LIST_EDIT_BUTTON' => 'Ndrysho',
    'LBL_ACTIVITIES_REPORTS' => 'Raporti i aktiviteteve',
    'LBL_PANEL_DETAILS' => 'Detajet',
    'LBL_NOTE_INFORMATION' => 'Pasqyra',
    'LBL_MY_NOTES_DASHLETNAME' => 'Shënimet e mia',
    'LBL_EDITLAYOUT' => 'Ndrysho formatin' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Emri',
    'LBL_LAST_NAME' => 'Mbiemri',
    'LBL_EXPORT_PARENT_TYPE' => 'Në lidhje me modulin',
    'LBL_EXPORT_PARENT_ID' => 'Në lidhje me ID',
    'LBL_DATE_ENTERED' => 'Data e krijimit',
    'LBL_DATE_MODIFIED' => 'Data e modifikimit',
    'LBL_DELETED' => 'E fshirë',
    'LBL_REVENUELINEITEMS' => 'Rreshti i llojeve të të ardhurave',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} është moduli i cili gjurmon dhe menaxhon produktin apo shërbimin të lidhur me problemet e raportuara të organizatës tuaj nga {{plural_module_name}}. {{module_name}} janë tipikisht të lidhur me regjstrimin e dhe shumësi i mund të asocohen me njëjësin e {{contacts_singular_module}}. {{plural_module_name}} mund të mbajë tekstin gjenerike në lidhje me një rekord apo edhe një shtojcë në lidhje me të dhënat. Ka disa mënyra për të krijuar {{plural_module_name}} në Sugar siç është nëpërmjet modulit {{plural_module_name}}, importimi i {{plural_module_name}} ose konvertimi nga emaili. Një herë që {{module_name}} është krijuar, ju mund ta shikoni dhe editoni informacionin lidhur me {{module_name}} nëpërmjet shikimit të {{plural_module_name}}. Çdo regjistrim {{module_name}} mund të lidhet me regjistrimet e Sugar si {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} dhe shumë të tjera.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Moduli {{plural_module_name}} konsiston në {{plural_module_name}} që organizata juaj ka lidhje dhe që përgjithsisht është parë si qendër për menaxhim dhe analizim të interaksioneve të biznesit tuaj me çdo klient. -Editoni këtë fushë regjistrimi duke klikuar në fushën individuale ose butonin Edit. -Shikoni ose modifikoni linkqet e regjistrimeve tjera në subpanelet duke shtypur butonin në anën e majtë të "Shikoni të dhënat". -Bëj dhe shiko komentet e përdoruesve dhe regjistro ndryshimin e historisë në {{activitystream_singular_module}} duke shtypur butonin e majtë "Aktivitet". -Ndiq ose bëj favorit këtë regjistrim duke përdorur ikonat në të djathtë të emrit të regjistrimit. -Veprime shtesë janë në dispozicion në fundin e menus së veprimeve në të djathë të butonit Edit.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Për të krijuar një {{module_name}}: 
1. Jep vlerat për fushat sipas dëshirës. 
- Fushat e shënuara me "Patjetër" duhet të plotësohen para se të ruhen. 
- Kliko "Trego më shumë" për të paraqitur fushat shtesë nëse është e nevojshme. 
2. Kliko "Ruaj" për të finalizuar regjistrimin e ri dhe për t&#39;u kthyer në faqen e mëparshme.',
);
