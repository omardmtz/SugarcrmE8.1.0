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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Zielkontaktlisten-Dashboard',

  'LBL_MODULE_NAME' => 'Kontaktlisten',
  'LBL_MODULE_NAME_SINGULAR' => 'Zielkontaktliste',
  'LBL_MODULE_ID'   => 'Kontaktlisten',
  'LBL_MODULE_TITLE' => 'Kontaktlisten: Startseite',
  'LBL_SEARCH_FORM_TITLE' => 'Kontaktlisten-Suche',
  'LBL_LIST_FORM_TITLE' => 'Kontaktlisten',
  'LBL_PROSPECT_LIST_NAME' => 'Kontaktliste:',
  'LBL_NAME' => 'Name',
  'LBL_ENTRIES' => 'Einträge gesamt',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Zielkontaktliste',
  'LBL_LIST_ENTRIES' => 'Ziele in der Liste',
  'LBL_LIST_DESCRIPTION' => 'Beschreibung',
  'LBL_LIST_TYPE_NO' => 'Typ',
  'LBL_LIST_END_DATE' => 'Enddatum',
  'LBL_DATE_ENTERED' => 'Erstellungsdatum',
  'LBL_MARKETING_ID' => 'Marketing-ID',
  'LBL_DATE_MODIFIED' => 'Änderungsdatum',
  'LBL_MODIFIED' => 'Geändert von',
  'LBL_CREATED' => 'Erstellt von',
  'LBL_TEAM' => 'Team',
  'LBL_ASSIGNED_TO' => 'Zugewiesen an',
  'LBL_DESCRIPTION' => 'Beschreibung',
  'LNK_NEW_CAMPAIGN' => 'Neue Kampagne',
  'LNK_CAMPAIGN_LIST' => 'Kampagnen',
  'LNK_NEW_PROSPECT_LIST' => 'Kontaktliste erstellen',
  'LNK_PROSPECT_LIST_LIST' => 'Zielkontaktlisten anzeigen',
  'LBL_MODIFIED_BY' => 'Geändert von',
  'LBL_CREATED_BY' => 'Erstellt von',
  'LBL_DATE_CREATED' => 'Erstellungsdatum:',
  'LBL_DATE_LAST_MODIFIED' => 'Geändert am',
  'LNK_NEW_PROSPECT' => 'Neuer Zielkontakt',
  'LNK_PROSPECT_LIST' => 'Zielkontakte',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Kontaktlisten',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakte',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Interessenten',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Zielkontakte',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Firmen',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Kampagnen',
  'LBL_COPY_PREFIX' =>'Kopie von',
  'LBL_USERS_SUBPANEL_TITLE' =>'Benutzer',
  'LBL_TYPE' => 'Typ',
  'LBL_LIST_TYPE' => 'Typ',
  'LBL_LIST_TYPE_LIST_NAME'=>'Typ',
  'LBL_NEW_FORM_TITLE'=>'Neue Kontaktliste',
  'LBL_MARKETING_NAME'=>'Marketing-Name',
  'LBL_MARKETING_MESSAGE'=>'E-Mail-Marketing-Nachricht',
  'LBL_DOMAIN_NAME'=>'Domänenname',
  'LBL_DOMAIN'=>'Keine E-Mails an diese Domäne',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Name',
	'LBL_MORE_DETAIL' => 'Mehr Details' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Eine {{module_name}} ist eine Sammlung von Personen oder Firmen, die in an einer {{campaigns_singular_module}} angeschrieben bzw. nicht angeschrieben werden sollen.
Die {{plural_module_name}} können eine beliebige Anzahl und beliebige Kombinationen verschiedener Empfänger, wie {{contacts_module}}, {{leads_module}}, Benutzer, and {{accounts_module}} beeinhalten. Empfänger können zum Beispiel anhand von Kriterien wie Alter, geografischer Lage oder Kaufgewohnheiten in einer {{module_name}} gruppiert werden. {{plural_module_name}} werden in Massen-Marketing-Aktionen verwendet, welche im {{campaigns_module}} erstellt werden können.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Eine {{module_name}} ist eine Sammlung von Personen oder Firmen, die in an einer {{campaigns_singular_module}} angeschrieben bzw. nicht angeschrieben werden sollen.

- Bearbeiten Sie den Datensatz, indem Sie auf ein Feld oder auf die Schaltfläche "Bearbeiten" klicken.
- Zeigen Sie Links zu anderen Datensätzen in den Sub-Panels an bzw. ändern Sie diese, inkl. Empfänger von {{campaigns_singular_module}}, indem Sie den linken unteren Bereich auf "Datenansicht" stellen.
- Machen Sie Benutzerkommentare oder zeigen Sie diese an bzw. zeigen Sie die Änderungshistorie verschiedener Datensätze im {{activitystream_singular_module}} an, indem Sie den linken unteren Bereich auf "Aktivitäten-Stream" stellen. 
- Folgen Sie Einträgen oder markieren Sie diese als Favoriten, indem Sie die Symbole rechts neben dem Namen verwenden. 
- Weitere Aktionen finden Sie im Dropdown-Menü auf der rechten Seite der Schaltfläche "Bearbeiten".',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Eine {{module_name}} ist eine Sammlung von Personen oder Firmen, die in an einer {{campaigns_singular_module}} angeschrieben bzw. nicht angeschrieben werden sollen.

So erstellen Sie ein {{module_name}}:
1. Erfassen Sie alle relevanten Informationen. 
- Felder, die als Pflichtfeld markiert sind, müssen vor dem Speichern ausgefüllt werden. 
- Klicken Sie auf "Mehr anzeigen", um weitere Felder zu erfassen. 
2. Klicken Sie auf "Speichern", um den Datensatz fertigzustellen und auf die letzte Seite zurück zu kommen.
3. Verwenden Sie nach dem Speichern die Sub-Panels im Datensatz des Kontakts, um die Empfänger von {{campaigns_singular_module}} anzuzeigen.',
);
