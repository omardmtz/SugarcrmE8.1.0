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
  'LBL_TASKS_LIST_DASHBOARD' => 'Aufgabenlisten-Dashboard',

  'LBL_MODULE_NAME' => 'Aufgaben',
  'LBL_MODULE_NAME_SINGULAR' => 'Aufgabe',
  'LBL_TASK' => 'Aufgaben:',
  'LBL_MODULE_TITLE' => ' Aufgaben: Startseite',
  'LBL_SEARCH_FORM_TITLE' => ' Aufgaben-Suche',
  'LBL_LIST_FORM_TITLE' => ' Aufgaben-Liste',
  'LBL_NEW_FORM_TITLE' => 'Neue Aufgabe',
  'LBL_NEW_FORM_SUBJECT' => 'Betreff:',
  'LBL_NEW_FORM_DUE_DATE' => 'Fällig am:',
  'LBL_NEW_FORM_DUE_TIME' => 'Fällig um:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Schließen',
  'LBL_LIST_SUBJECT' => 'Betreff',
  'LBL_LIST_CONTACT' => 'Kontakt',
  'LBL_LIST_PRIORITY' => 'Priorität',
  'LBL_LIST_RELATED_TO' => 'Bezieht sich auf',
  'LBL_LIST_DUE_DATE' => 'Fällig am',
  'LBL_LIST_DUE_TIME' => 'Fällig um:',
  'LBL_SUBJECT' => 'Betreff:',
  'LBL_STATUS' => 'Status:',
  'LBL_DUE_DATE' => 'Fällig am:',
  'LBL_DUE_TIME' => 'Fällig um:',
  'LBL_PRIORITY' => 'Priorität:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Fälligkeitsdatum und -zeit:',
  'LBL_START_DATE_AND_TIME' => 'Startdatum und -zeit:',
  'LBL_START_DATE' => 'Startdatum:',
  'LBL_LIST_START_DATE' => 'Startdatum',
  'LBL_START_TIME' => 'Startzeit:',
  'LBL_LIST_START_TIME' => 'Startzeit',
  'DATE_FORMAT' => '(jjjj-mm-tt)',
  'LBL_NONE' => 'Kein(e)',
  'LBL_CONTACT' => 'Kontakt:',
  'LBL_EMAIL_ADDRESS' => 'E-Mail-Adresse:',
  'LBL_PHONE' => 'Telefon:',
  'LBL_EMAIL' => 'E-Mail-Adresse:',
  'LBL_DESCRIPTION_INFORMATION' => 'Beschreibungsinformation',
  'LBL_DESCRIPTION' => 'Beschreibung:',
  'LBL_NAME' => 'Name:',
  'LBL_CONTACT_NAME' => 'Kontaktname ',
  'LBL_LIST_COMPLETE' => 'Abgeschlossen:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_DATE_DUE_FLAG' => 'Kein Fälligkeitsdatum',
  'LBL_DATE_START_FLAG' => 'Kein Startdatum',
  'ERR_DELETE_RECORD' => 'Die Nummer eines Datensatzes muss angegeben werdenm um einen Kontakt zu löschen.',
  'ERR_INVALID_HOUR' => 'Bitte geben Sie eine Stunde zwischen 0 Uhr und 24 Uhr ein',
  'LBL_DEFAULT_PRIORITY' => 'Mittel',
  'LBL_LIST_MY_TASKS' => 'Meine offenen Aufgaben',
  'LNK_NEW_TASK' => 'Neue Aufgabe',
  'LNK_TASK_LIST' => 'Aufgaben anzeigen',
  'LNK_IMPORT_TASKS' => 'Aufgaben importieren',
  'LBL_CONTACT_FIRST_NAME'=>'Kontakt Vorname',
  'LBL_CONTACT_LAST_NAME'=>'Kontakt Nachname',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Zugew. Benutzer',
  'LBL_ASSIGNED_TO_NAME'=>'Zugewiesen an:',
  'LBL_LIST_DATE_MODIFIED' => 'Änderungsdatum',
  'LBL_CONTACT_ID' => 'Kontakt-ID:',
  'LBL_PARENT_ID' => 'Parent-ID:',
  'LBL_CONTACT_PHONE' => 'Telefon Kontaktperson:',
  'LBL_PARENT_NAME' => 'Parent-Typ:',
  'LBL_ACTIVITIES_REPORTS' => 'Aktivitätenbericht',
  'LBL_EDITLAYOUT' => 'Layout bearbeiten' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Überblick',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Notizen',
  'LBL_REVENUELINEITEMS' => 'Umsatzposten',
  //For export labels
  'LBL_DATE_DUE' => 'Fällig am:',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Zugewiesener Benutzer',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Zugewiesene Benutzer-ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Verändert von:',
  'LBL_EXPORT_CREATED_BY' => 'Ersteller',
  'LBL_EXPORT_PARENT_TYPE' => 'Verknüpft mit Modul',
  'LBL_EXPORT_PARENT_ID' => 'Verknüpft mit ID',
  'LBL_TASK_CLOSE_SUCCESS' => 'Aufgabe erfolgreich abgeschlossen.',
  'LBL_ASSIGNED_USER' => 'Zugewiesen an',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Notizen',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Das Modul {{plural_module_name}} enthält flexible Aktionen, To-Do-Elemente oder andere Aktivitäten, die fertiggestellt werden müssen. Die Datensätze {{module_name}} können mit einem Datensatz in den meisten Modulen oder mit einem einzigen {{contacts_singular_module}} verbunden sein. Es gibt verschiedene Arten, {{plural_module_name}} in Sugar zu erstellen; z. B. über das Modul {{plural_module_name}}, durch Importieren bzw. Duplizieren von {{plural_module_name}} etc. Nach dem Erstellen von {{module_name}} können Sie die entsprechenden Informationen zu {{module_name}} über die Datensatzansicht {{plural_module_name}} anzeigen bzw. bearbeiten. Je nach den Details von {{module_name}} können Sie die Informationen von {{module_name}} möglicherweise über das Kalendermodul anzeigen und bearbeiten. Jeder Datensatz {{module_name}} kann mit anderen Sugar-Datensätzen wie {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} etc. verbunden sein.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Das Modul {{plural_module_name}} enthält flexible Aktionen, To-Do-Elemente oder andere Aktivitäten, die fertiggestellt werden müssen. 

- Bearbeiten Sie die einzelnen Felder, indem Sie in diese hinein klicken oder die Schaltfläche "Bearbeiten" verwenden.
- Sehen Sie sich Links zu anderen Datensätzen an oder bearbeiten Sie diese, indem Sie den linken unteren Bericht auf "Datenansicht" stellen.
- Machen Sie Benutzerkommentare oder zeigen Sie diese an bzw. zeichnen Sie die Änderungshistorie verschiedener Datensätze im Modul {{activitystream_singular_module}} an, indem Sie den linken unteren Bereich auf "Aktivitäten-Stream" stellen. 
- Folgen Sie Einträgen oder markieren Sie diese als Favoriten, indem Sie die Symbole rechts neben dem Namen verwenden. 
- Weitere Aktionen finden Sie im Dropdown-Menü auf der rechten Seite der Schaltfläche "Bearbeiten".',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Das Modul {{plural_module_name}} enthält flexible Aktionen, Aufgaben oder andere Aktivitäten, die fertiggestellt werden müssen. 

So erstellen Sie ein {{module_name}}:
1. Erfassen Sie alle relevanten Informationen. 
- Felder, die als Pflichtfeld markiert sind, müssen vor dem Speichern ausgefüllt werden. 
- Klicken Sie auf "Mehr anzeigen", um weitere Felder zu erfassen. 
2. Klicken Sie auf "Speichern", um den Datensatz fertigzustellen und auf die letzte Seite zurück zu kommen.',

);
