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
    'ERR_ADD_RECORD' => 'Sie müssen eine Datensatznummer angeben, um einen Benutzer zu diesem Team hinzuzufügen.',
    'ERR_DUP_NAME' => 'Der Teamname ist vergeben. Bitte wählen Sie einen anderen.',
    'ERR_DELETE_RECORD' => 'Sie müssen eine Datensatznummer angeben, um dieses Team zu löschen.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Fehler. Das ausgewählte Team <b>({0})</b> ist ein Team, das Sie gelöscht haben. Bitte wählen Sie ein anderen Team aus.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Fehler. Sie können keinen Benutzer löschen, dessen privates Team noch nicht gelöscht worden ist.',
    'LBL_DESCRIPTION' => 'Beschreibung:',
    'LBL_GLOBAL_TEAM_DESC' => 'Global sichtbar',
    'LBL_INVITEE' => 'Teammitglieder',
    'LBL_LIST_DEPARTMENT' => 'Abteilung',
    'LBL_LIST_DESCRIPTION' => 'Beschreibung',
    'LBL_LIST_FORM_TITLE' => 'Teamliste',
    'LBL_LIST_NAME' => 'Name',
    'LBL_FIRST_NAME' => 'Vorname:',
    'LBL_LAST_NAME' => 'Nachname:',
    'LBL_LIST_REPORTS_TO' => 'Berichtet an',
    'LBL_LIST_TITLE' => 'Name',
    'LBL_MODULE_NAME' => 'Teams',
    'LBL_MODULE_NAME_SINGULAR' => 'Team',
    'LBL_MODULE_TITLE' => 'Teams: Startseite',
    'LBL_NAME' => 'Teamname:',
    'LBL_NAME_2' => 'Teamname(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Primärer Teamname',
    'LBL_NEW_FORM_TITLE' => 'Neues Team',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Privates Team für:',
    'LBL_SEARCH_FORM_TITLE' => 'Team-Suche',
    'LBL_TEAM_MEMBERS' => 'Teammitglieder',
    'LBL_TEAM' => 'Teams:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Benutzer',
    'LBL_USERS' => 'Benutzer',
    'LBL_REASSIGN_TEAM_TITLE' => 'Es gibt Datensätze, die folgendem(n) Team(s) zugewiesen wurden: <b>{0}</b> <br>Bevor Sie Teams löschen, müssen Sie diese Datensätze zuerst einem neuen Team zuweisen.  Wählen Sie ein Team aus, dem Sie die Datensätze zuweisen möchten.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Neu Zuteilen',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Neu Zuteilen [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Fortfahren mit der Aktualisierung der betroffenen Datensätze, damit sie von dem neuen Team verwendet werden?',
    'LBL_REASSIGN_TABLE_INFO' => 'Tabelle {0} wird aktualisiert',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Die Operation wurde erfolgreich abgeschlossen.',
    'LNK_LIST_TEAM' => 'Teams',
    'LNK_LIST_TEAMNOTICE' => 'Team-Notizen',
    'LNK_NEW_TEAM' => 'Neues Team',
    'LNK_NEW_TEAM_NOTICE' => 'Neue Team-Notiz',
    'NTC_DELETE_CONFIRMATION' => 'Sind Sie sicher, dass Sie diesen Eintrag löschen möchten?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Sind Sie sicher, dass Sie die Mitgliedschaft für diese(n) Benutzer entfernen möchten?',
    'LBL_EDITLAYOUT' => 'Layout bearbeiten' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Team-basierte Berechtigungen',
    'LBL_TBA_CONFIGURATION_DESC' => 'Aktivieren Sie Teamzugriff und verwalten Sie den Zugriff durch das Modul.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Team-basierte Berechtigungen aktivieren',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Wählen Sie Module zum Aktivieren aus',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Durch die Aktivierung von Team-basierten Berechtigungen können Sie bestimmte Zugriffsrechte auf Teams und Benutzer für einzelne Module mittels der Verwaltung von Rollen zuweisen.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Die Deaktivierung der Team-basierten Berechtigungen für ein Modul hat zur Folge, dass alle den Team-basierten Berechtigungen für dieses Modul zugeordneten Daten, einschließlich Prozessdefinitionen oder Prozesse mit dieser Funktion, rückgängig gemacht werden. Hierunter fallen alle Rollen mit der "Besitzer & Ausgewähltes Team"-Option für dieses Modul sowie auch alle Team-basierten Berechtigungsdaten für Datensätze in diesem Modul. Wir empfehlen auch, die Schnellreparatur (Quick Repair) und das Neuaufbau-Tool zu verwenden, um den Systemcache nach der Desaktivierung der Team-basierten Berechtigungen für jedes Modul zu leeren.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Warning:</strong> Die Deaktivierung der Team-basierten Berechtigungen für ein Modul hat zur Folge, dass alle den Team-basierten Berechtigungen für dieses Modul zugeordneten Daten, einschließlich Prozessdefinitionen oder Prozesse mit dieser Funktion, rückgängig gemacht werden. Hierunter fallen alle Rollen mit der "Besitzer & Ausgewähltes Team"-Option für dieses Modul sowie auch alle Team-basierten Berechtigungsdaten für Datensätze in diesem Modul. Wir empfehlen auch, die Schnellreparatur (Quick Repair) und das Neuaufbau-Tool zu verwenden, um den Systemcache nach der Desaktivierung der Team-basierten Berechtigungen für jedes Modul zu leeren.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Die Deaktivierung der Team-basierten Berechtigungen für ein Modul hat zur Folge, dass alle den Team-basierten Berechtigungen für dieses Modul zugeordneten Daten, einschließlich Prozessdefinitionen oder Prozesse mit dieser Funktion, rückgängig gemacht werden. Hierunter fallen alle Rollen mit der "Besitzer & Ausgewähltes Team"-Option für dieses Modul sowie auch alle Team-basierten Berechtigungsdaten für Datensätze in diesem Modul. Wir empfehlen auch, die Schnellreparatur (Quick Repair) und das Neuaufbau-Tool zu verwenden, um den Systemcache nach der Desaktivierung der Team-basierten Berechtigungen für jedes Modul zu leeren. Wenn Sie keinen Zugriff auf die Schnellreparatur (Quick Repair) und das Neuaufbau-Tool haben, kontaktieren Sie bitte einen Administrator, der auf das Reparatur-Menü Zugriff hat.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Warning:</strong> Die Deaktivierung der Team-basierten Berechtigungen für ein Modul hat zur Folge, dass alle den Team-basierten Berechtigungen für dieses Modul zugeordneten Daten, einschließlich Prozessdefinitionen oder Prozesse mit dieser Funktion, rückgängig gemacht werden. Hierunter fallen alle Rollen mit der "Besitzer & Ausgewähltes Team"-Option für dieses Modul sowie auch alle Team-basierten Berechtigungsdaten für Datensätze in diesem Modul. Wir empfehlen auch, die Schnellreparatur (Quick Repair) und das Neuaufbau-Tool zu verwenden, um den Systemcache nach der Desaktivierung der Team-basierten Berechtigungen für jedes Modul zu leeren. Wenn Sie keinen Zugriff auf die Schnellreparatur (Quick Repair) und das Neuaufbau-Tool haben, kontaktieren Sie bitte einen Administrator, der auf das Reparatur-Menü Zugriff hat.
STR
,
);
