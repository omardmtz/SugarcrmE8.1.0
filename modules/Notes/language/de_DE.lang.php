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
    'LBL_NOTES_LIST_DASHBOARD' => 'Notizenlisten-Dashboard',

    'ERR_DELETE_RECORD' => 'Zum Löschen der Firma muss eine Datensatznummer angegeben werden.',
    'LBL_ACCOUNT_ID' => 'Firmen-ID:',
    'LBL_CASE_ID' => 'Ticket-ID:',
    'LBL_CLOSE' => 'Beenden:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontakt-ID:',
    'LBL_CONTACT_NAME' => 'Kontakt:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Notizen',
    'LBL_DESCRIPTION' => 'Beschreibung',
    'LBL_EMAIL_ADDRESS' => 'E-Mail-Adresse:',
    'LBL_EMAIL_ATTACHMENT' => 'E-Mail-Anhang',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'E-Mail-Anhang für',
    'LBL_FILE_MIME_TYPE' => 'Mime-Typ',
    'LBL_FILE_EXTENSION' => 'Dateinamenerweiterung',
    'LBL_FILE_SOURCE' => 'Dateiquelle',
    'LBL_FILE_SIZE' => 'Dateigröße',
    'LBL_FILE_URL' => 'Datei-URL',
    'LBL_FILENAME' => 'Anlage:',
    'LBL_LEAD_ID' => 'Interessenten-ID:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakt',
    'LBL_LIST_DATE_MODIFIED' => 'Geändert am',
    'LBL_LIST_FILENAME' => 'Anlage',
    'LBL_LIST_FORM_TITLE' => 'Notizen-Liste',
    'LBL_LIST_RELATED_TO' => 'Bezieht sich auf',
    'LBL_LIST_SUBJECT' => 'Betreff',
    'LBL_LIST_STATUS' => 'Status',
    'LBL_LIST_CONTACT' => 'Kontakt',
    'LBL_MODULE_NAME' => 'Notizen',
    'LBL_MODULE_NAME_SINGULAR' => 'Notiz',
    'LBL_MODULE_TITLE' => 'Notizen: Startseite',
    'LBL_NEW_FORM_TITLE' => 'Neue Notiz oder Anlage',
    'LBL_NEW_FORM_BTN' => 'Notiz erstellen',
    'LBL_NOTE_STATUS' => 'Notiz',
    'LBL_NOTE_SUBJECT' => 'Betreff:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notizen & Anlagen',
    'LBL_NOTE' => 'Hinweis:',
    'LBL_OPPORTUNITY_ID' => 'Verkaufschance-ID:',
    'LBL_PARENT_ID' => 'Parent-ID:',
    'LBL_PARENT_TYPE' => 'Eltern-Typ',
    'LBL_EMAIL_TYPE' => 'E-Mail-Typ',
    'LBL_EMAIL_ID' => 'E-Mail',
    'LBL_PHONE' => 'Telefon:',
    'LBL_PORTAL_FLAG' => 'Im Portal anzeigen?',
    'LBL_EMBED_FLAG' => 'In E-Mail einfügen?',
    'LBL_PRODUCT_ID' => 'Produkt-ID:',
    'LBL_QUOTE_ID' => 'Angebots-ID:',
    'LBL_RELATED_TO' => 'Bezieht sich auf:',
    'LBL_SEARCH_FORM_TITLE' => 'Notizen-Suche',
    'LBL_STATUS' => 'Status',
    'LBL_SUBJECT' => 'Betreff:',
    'LNK_IMPORT_NOTES' => 'Notizen importieren',
    'LNK_NEW_NOTE' => 'Neue Notiz oder Anlage',
    'LNK_NOTE_LIST' => 'Notizen anzeigen',
    'LBL_MEMBER_OF' => 'Mitglied von:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Zugew. Benutzer',
    'LBL_OC_FILE_NOTICE' => 'Bitte melden Sie sich bei dem Server an, um die Datei anzuzeigen',
    'LBL_REMOVING_ATTACHMENT' => 'Anhang wird entfernt...',
    'ERR_REMOVING_ATTACHMENT' => 'Anhang konnte nicht entfernt werden...',
    'LBL_CREATED_BY' => 'Erstellt von:',
    'LBL_MODIFIED_BY' => 'Geändert von',
    'LBL_SEND_ANYWAYS' => 'Diese E-Mail hat kein Betreff. Dennoch senden/speichern?',
    'LBL_LIST_EDIT_BUTTON' => 'Bearbeiten',
    'LBL_ACTIVITIES_REPORTS' => 'Aktivitätenbericht',
    'LBL_PANEL_DETAILS' => 'Details',
    'LBL_NOTE_INFORMATION' => 'Überblick',
    'LBL_MY_NOTES_DASHLETNAME' => 'Meine Notizen',
    'LBL_EDITLAYOUT' => 'Layout bearbeiten' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Vorname',
    'LBL_LAST_NAME' => 'Nachname',
    'LBL_EXPORT_PARENT_TYPE' => 'Verknüpft mit Modul',
    'LBL_EXPORT_PARENT_ID' => 'Verknüpft mit ID',
    'LBL_DATE_ENTERED' => 'Erstellungsdatum',
    'LBL_DATE_MODIFIED' => 'Änderungsdatum',
    'LBL_DELETED' => 'Gelöscht',
    'LBL_REVENUELINEITEMS' => 'Umsatzposten',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Das Modul {{plural_module_name}} enthält einzelne{{plural_module_name}} mit Text oder Anlagen zu dem entsprechenden Datensatz. Die Datensätze {{module_name}} können mit einem Datensatz in den meisten Modulen oder mit einem einzigen {{contacts_singular_module}} verbunden sein. {{plural_module_name}} kann einen allgemeinen Text über einen Datensatz oder auch eine Anlage dazu enthalten. Es gibt verschiedene Arten, {{plural_module_name}} in Sugar zu erstellen; z. B. über das Modul {{plural_module_name}}, durch Importieren von {{plural_module_name}}, über die Verlaufs-Sub-Panels, etc. Nach dem Erstellen von{{module_name}} können Sie die entsprechenden Informationen zu {{module_name}} über die Datensatzansicht {{plural_module_name}} anzeigen bzw. bearbeiten. Jeder Datensatz {{module_name}} kann mit anderen Sugar-Datensätzen wie {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} etc. verbunden sein.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Das {{plural_module_name}} Modul werden individuelle {{plural_module_name}} die Text oder auch eine Anlage beeinhalten zu einem verknüpften Datensatz gespeichert.
- Bearbeiten Sie den Datensatz indem Sie auf ein Feld oder auf die Schaltfläche Bearbeiten klicken.
- Erstellen oder sehen Sie sich Benutzerkommentare und die Änderungshistorie verschiedener Datensätze im  {{activitystream_singular_module}} an indem Sie die Sicht auf "Activity Stream" im unteren linken Bereich stellen. 
- Folgen Sie favorisierten Einträgen indem Sie das Sternsymbol neben dem Namen oben anklicken. 
- Weitere Aktionen finden Sie im Ausklappmenü auf der rechten Seite der Bearbeiten Schaltfläche.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'So erstellen Sie ein {{module_name}}:
1. Erfassen Sie alle relevanten Informationen. 
- Felder, die als Pflichtfeld markiert sind, müssen vor dem Speichern ausgefüllt werden. 
- Klicken Sie auf "Mehr anzeigen", um weitere Felder zu erfassen. 
2. Klicken Sie auf "Speichern", um den Datensatz fertigzustellen und auf die letzte Seite zurück zu kommen.',
);
