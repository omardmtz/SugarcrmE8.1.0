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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Workflow-Definitionen',
  'LBL_MODULE_NAME_SINGULAR' => 'Workflow-Definition',
  'LBL_MODULE_ID' => 'Workflow',  
  'LBL_MODULE_TITLE' => 'Workflow: Startseite',
  'LBL_SEARCH_FORM_TITLE' => 'Workflow-Suche',
  'LBL_LIST_FORM_TITLE' => 'Workflow-Liste',
  'LBL_NEW_FORM_TITLE' => 'Workflow-Definition erstellen',
  'LBL_LIST_NAME' => 'Name',
  'LBL_LIST_TYPE' => 'Durchführung findet statt:',
  'LBL_LIST_BASE_MODULE' => 'Zielmodul:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_NAME' => 'Name:',
  'LBL_DESCRIPTION' => 'Beschreibung:',
  'LBL_TYPE' => 'Durchführung findet statt:',
  'LBL_STATUS' => 'Status:',
  'LBL_BASE_MODULE' => 'Zielmodul:',
  'LBL_LIST_ORDER' => 'Auftrag verarbeiten:',
  'LBL_FROM_NAME' => 'Von Name:',
  'LBL_FROM_ADDRESS' => 'Von Adresse:',  
  'LNK_NEW_WORKFLOW' => 'Workflow-Definition erstellen',
  'LNK_WORKFLOW' => 'Liste Workflow-Definitionen', 
  
  
  'LBL_ALERT_TEMPLATES' => 'Meldungsvorlagen',
  'LBL_CREATE_ALERT_TEMPLATE' => 'Neue Meldungsvorlage',
  'LBL_SUBJECT' => 'Betreff:',
  
  'LBL_RECORD_TYPE' => 'Anwenden auf:',
 'LBL_RELATED_MODULE'=> 'Verknüpftes Modul:',
  
  
  'LBL_PROCESS_LIST' => 'Workflow-Reihenfolge',
	'LNK_ALERT_TEMPLATES' => 'Meldungs-E-Mail-Vorlagen',
	'LNK_PROCESS_VIEW' => 'Workflow-Reihenfolge',
  'LBL_PROCESS_SELECT' => 'Wählen Sie ein Modul aus:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> 'Bemerkung: Sie müssen einen Auslöser erstellen, damit dieses Workflow-Objekt funktioniert',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> 'Bemerkung: Um Meldungen zu senden, müssen die SMTP-Serverinformationen unter "Admin > E-Mail-Einstellungen" eingegeben werden.',
  'LBL_FIRE_ORDER' => 'Auftrag wird verarbeitet:',
  'LBL_RECIPIENTS' => 'Empfänger',
  'LBL_INVITEES' => 'Teilnehmer',
  'LBL_INVITEE_NOTICE' => 'Beachten Sie, dass mindestens ein Teilnehmer ausgewählt werden muss. ',
  'NTC_REMOVE_ALERT' => 'Sind Sie sicher, dass Sie diesen Workflow entfernen möchten?',
  'LBL_EDIT_ALT_TEXT' => 'Alternativer Text',
  'LBL_INSERT' => 'Einfügen',
  'LBL_SELECT_OPTION' => 'Bitte wählen Sie eine Option.',
  'LBL_SELECT_VALUE' => 'Sie müssen einen Wert auswählen.',
  'LBL_SELECT_MODULE' => 'Wählen Sie ein verknüpftes Modul aus.',
  'LBL_SELECT_FILTER' => 'Sie müssen ein Feld auswählen, mit dem Sie das verknüpfte Modul filtern.',
  'LBL_LIST_UP' => 'nach oben',
  'LBL_LIST_DN' => 'nach unten',
  'LBL_SET' => 'Setze',
  'LBL_AS' => 'als',
  'LBL_SHOW' => 'Anzeigen',
  'LBL_HIDE' => 'Ausblenden',
  'LBL_SPECIFIC_FIELD' => 'spezielles Feld',
  'LBL_ANY_FIELD' => 'irgendein Feld',
  'LBL_LINK_RECORD'=>'Verknüpfung zu Eintrag',
  'LBL_INVITE_LINK'=>'Einladungslink Meeting/Anruf',
  'LBL_PLEASE_SELECT'=>'Bitte auswählen',
  'LBL_BODY'=>'Text:',
  'LBL__S'=>'s',
  'LBL_ALERT_SUBJECT'=>'WORKFLOW-ALARM',
  'LBL_ACTION_ERROR'=>'Die Aktion kann nicht durchgeführt werden. Bearbeiten Sie die Aktion und überprüfen Sie alle Felder und Feldwerte.',
  'LBL_ACTION_ERRORS'=>'Hinweis: Eine oder mehrere Aktionen sind fehlerhaft.',
  'LBL_ALERT_ERROR'=>'Diese Meldung kann nicht durchgeführt werden. Bearbeiten Sie die Meldung und überprüfen Sie die Einstellungen.',
  'LBL_ALERT_ERRORS'=>'Hinweis: Eine oder mehrere Meldungen enthalten Fehler.',
  'LBL_TRIGGER_ERROR'=>'Hinweis: Dieser Auslöser beinhaltet ungültige Werte und ist nicht funktionsfähig.',
  'LBL_TRIGGER_ERRORS'=>'Hinweis: Ein oder mehrere Auslöser enthalten Fehler.',
  'LBL_UP' => 'nach oben' /*for 508 compliance fix*/,
  'LBL_DOWN' => 'nach unten' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'Layout bearbeiten' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'Workflow'),
  'LBL_EMAILTEMPLATES_TYPE' => 'Typ',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Hinweis:</strong> Die Funktionen Sugar Workflow und Workflow Management werden in einer zukünftigen Version von Sugar entfernt. Kunden, die die Sugar Enterprise Edition verwenden, sollten beginnen, die erweiterten Workflow-Funktionen von Sugar Advanced Workflow zu verwenden. Klicken Sie für weitere Informationen <a href="http://www.sugarcrm.com/wf-eol/" target="_blank">hier</a>.',
);

