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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Verkaufschancenlisten-Dashboard',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Verkaufschancenbericht-Dashboard',

    'LBL_MODULE_NAME' => 'Verkaufschancen',
    'LBL_MODULE_NAME_SINGULAR' => 'Verkaufschance',
    'LBL_MODULE_TITLE' => 'Verkaufschancen: Startseite',
    'LBL_SEARCH_FORM_TITLE' => 'Verkaufschancen-Suche',
    'LBL_VIEW_FORM_TITLE' => 'Verkaufschancen-Ansicht',
    'LBL_LIST_FORM_TITLE' => 'Verkaufschancen-Liste',
    'LBL_OPPORTUNITY_NAME' => 'Verkaufschance-Name:',
    'LBL_OPPORTUNITY' => 'Verkaufschance:',
    'LBL_NAME' => 'Verkaufschance-Name',
    'LBL_INVITEE' => 'Kontakte',
    'LBL_CURRENCIES' => 'Währungen',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Name',
    'LBL_LIST_ACCOUNT_NAME' => 'Firmenname',
    'LBL_LIST_DATE_CLOSED' => 'Schließen',
    'LBL_LIST_AMOUNT' => 'Wahrscheinlich',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Konvertierter Betrag',
    'LBL_ACCOUNT_ID' => 'Firmen-ID',
    'LBL_CURRENCY_RATE' => 'Wechselkurs',
    'LBL_CURRENCY_ID' => 'Währungs-ID',
    'LBL_CURRENCY_NAME' => 'Währungsname',
    'LBL_CURRENCY_SYMBOL' => 'Währungssymbol',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Währung aktualisieren',
    'UPDATE_DOLLARAMOUNTS' => 'Euro-Beträge aktualisieren',
    'UPDATE_VERIFY' => 'Beträge überprüfen',
    'UPDATE_VERIFY_TXT' => 'Überprüft, ob alle angegebenen Werte gültige Dezimalwerte sind (bestehend aus den Zahlen 0 - 9 und dem Dezimaltrennzeichen)',
    'UPDATE_FIX' => 'Beträge korrigieren',
    'UPDATE_FIX_TXT' => 'Versucht, ungültige Beträge über das Setzen korrekter Dezimalzeichen zu korrigieren. Für jeden geänderten Betrag existiert eine Sicherungskopie im Datenbankfeld "amount_backup". Falls Sie diese Funktion aufrufen und Fehler feststellen, müssen Sie vor einem erneuten Versuch erst die alten Beträge, die sich im Backup befinden, wieder herstellen, da ansonsten Ihre ursprünglichen Einträge in der Datenbank mit den fehlerhaften Beträgen überschrieben werden.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Hier werden die Beträge der Angebote basierend auf dem angegebenen Wechselkurs neu berechnet. Diese Werte werden für Grafiken und Währungstabellen herangezogen.',
    'UPDATE_CREATE_CURRENCY' => 'Neue Währung:',
    'UPDATE_VERIFY_FAIL' => 'Der Datensatz konnte nicht verifiziert werden:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Aktueller Betrag:',
    'UPDATE_VERIFY_FIX' => 'Durch Reparation berichtigter Betrag wäre',
    'UPDATE_INCLUDE_CLOSE' => 'Auch abgeschlossenen Angebote überprüfen',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Neuer Betrag:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Neue Währung:',
    'UPDATE_DONE' => 'Fertig',
    'UPDATE_BUG_COUNT' => 'Gefundene Fehler, deren Behebung versucht wurde:',
    'UPDATE_BUGFOUND_COUNT' => 'Gefundene Fehler:',
    'UPDATE_COUNT' => 'Bearbeitete Einträge:',
    'UPDATE_RESTORE_COUNT' => 'Wiederhergestellte Beträge:',
    'UPDATE_RESTORE' => 'Betrag wiederherstellen',
    'UPDATE_RESTORE_TXT' => 'Stellt die Beträge wieder her, die während der Reparatur gesichert wurden.',
    'UPDATE_FAIL' => 'Update konnte nicht durchgeführt werden -',
    'UPDATE_NULL_VALUE' => 'Betragsfeld ist leer und wird deshalb auf 0 gesetzt -',
    'UPDATE_MERGE' => 'Währungen zusammenführen',
    'UPDATE_MERGE_TXT' => 'Zusammenführen mehrerer Währungen. Falls Sie feststellen, dass mehrere Einträge mit der gleichen Währung vorhanden sind, können Sie diese zusammenführen. Dies gilt analog für alle anderen Module.',
    'LBL_ACCOUNT_NAME' => 'Firmenname:',
    'LBL_CURRENCY' => 'Währung',
    'LBL_DATE_CLOSED' => 'Abschluss geplant:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Zeitstempel des erwarteten Abschlusses',
    'LBL_TYPE' => 'Typ:',
    'LBL_CAMPAIGN' => 'Kampagne:',
    'LBL_NEXT_STEP' => 'Nächster Schritt:',
    'LBL_LEAD_SOURCE' => 'Quelle:',
    'LBL_SALES_STAGE' => 'Verkaufsphase:',
    'LBL_SALES_STATUS' => 'Status',
    'LBL_PROBABILITY' => 'Wahrscheinlichkeit (%):',
    'LBL_DESCRIPTION' => 'Beschreibung:',
    'LBL_DUPLICATE' => 'Mögliches Verkaufschancen-Duplikat',
    'MSG_DUPLICATE' => 'Die Verkaufschance, die Sie gerade erstellen, könnte ein Duplikat einer bereits bestehenden Verkaufschance sein. Verkaufschancen mit ähnlichen Namen sind unten aufgeführt.<br>Klicken Sie auf "Speichern", um den Vorgang fortzusetzen, oder auf "Abbrechen", um zum Modul zurückzukehren, ohne die Verkaufschance zu erstellen.',
    'LBL_NEW_FORM_TITLE' => 'Neue Verkaufschance',
    'LNK_NEW_OPPORTUNITY' => 'Neue Verkaufschance',
    'LNK_CREATE' => 'Neues Ticket',
    'LNK_OPPORTUNITY_LIST' => 'Verkaufschancen anzeigen',
    'ERR_DELETE_RECORD' => 'Die Datensatznummer muss angegeben werden, sonst kann der Datensatz nicht gelöscht werden.',
    'LBL_TOP_OPPORTUNITIES' => 'Liste der Top-Verkaufschancen',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Möchten Sie diesen Kontakt wirklich aus dieser Verkaufschance entfernen?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Möchten Sie dieses Verkaufschance wirklich von diesem Projekt entfernen?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Verkaufschancen',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Aktivitäten',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Verlauf',
    'LBL_RAW_AMOUNT' => 'Ges. Summe',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Interessenten',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontakte',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Dokumente',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekte',
    'LBL_ASSIGNED_TO_NAME' => 'Zugewiesen an:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Zugew. Benutzer',
    'LBL_LIST_SALES_STAGE' => 'Verkaufsphase',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Meine geschlossenen Verkaufschancen',
    'LBL_TOTAL_OPPORTUNITIES' => 'Summe Verkaufschancen',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Gewonnene Verkaufschancen',
    'LBL_ASSIGNED_TO_ID' => 'Bearbeiter',
    'LBL_CREATED_ID' => 'Ersteller',
    'LBL_MODIFIED_ID' => 'Geändert von ID',
    'LBL_MODIFIED_NAME' => 'Geändert vonnamen',
    'LBL_CREATED_USER' => 'Erstellt von',
    'LBL_MODIFIED_USER' => 'Geändert von',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Kampagnen',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projekte',
    'LABEL_PANEL_ASSIGNMENT' => 'Aufgabe',
    'LNK_IMPORT_OPPORTUNITIES' => 'Verkaufschancen importieren',
    'LBL_EDITLAYOUT' => 'Layout bearbeiten' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Kampagnen-ID',
    'LBL_OPPORTUNITY_TYPE' => 'Verkaufschancentyp',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Zugewiesener Benutzer',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'Zugewiesene Benutzer-ID',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Bearbeiter:',
    'LBL_EXPORT_CREATED_BY' => 'Erstellt von ID',
    'LBL_EXPORT_NAME' => 'Name',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Verknüpfte Kontakt-E-Mails',
    'LBL_FILENAME' => 'Anlage',
    'LBL_PRIMARY_QUOTE_ID' => 'Primäres Angebot',
    'LBL_CONTRACTS' => 'Verträge',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Verträge',
    'LBL_PRODUCTS' => 'Produkte',
    'LBL_RLI' => 'Umsatzposten',
    'LNK_OPPORTUNITY_REPORTS' => 'Verkaufschancen-Berichte',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Angebote',
    'LBL_TEAM_ID' => 'Team-ID',
    'LBL_TIMEPERIODS' => 'Zeitspannen',
    'LBL_TIMEPERIOD_ID' => 'Zeitspannen-ID',
    'LBL_COMMITTED' => 'Übergeben',
    'LBL_FORECAST' => 'In Prognose inkludieren',
    'LBL_COMMIT_STAGE' => 'Bestätigungsstatus',
    'LBL_COMMIT_STAGE_FORECAST' => 'Prognose',
    'LBL_WORKSHEET' => 'Arbeitsblatt',

    'TPL_RLI_CREATE' => 'Eine Verkaufschance muss einen Umsatzposten zugewiesen haben.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Einen Umsatzposten erstellen.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Produkte',
    'LBL_RLI_SUBPANEL_TITLE' => 'Umsatzposten',

    'LBL_TOTAL_RLIS' => '# von allen Umsatzposten',
    'LBL_CLOSED_RLIS' => '# von abgeschlossenen Umsatzposten',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'Sie können keine Verkaufschancen löschen, die abgeschlossene Umsatzposten beinhalten',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Einer oder mehrere Eintrage beinhalten abgeschlossene Umsatzposten und können nicht gelöscht werden.',
    'LBL_INCLUDED_RLIS' => '# der enthaltenen Umsatz-Einzelposten',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Angebote',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Verkaufschancen-Hierarchie',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Das Feld "Erwartetes Abschlussdatum" in den resultierenden Verkaufschancen-Berichten auf die frühesten oder spätesten Abschlussdaten der bestehenden Umsatzposten setzen',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Pipeline-Summe beträgt ',

    'LBL_OPPORTUNITY_ROLE'=>'Verkaufschancen-Rolle',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notizen',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Durch Klicken von "Bestätigen" löschen Sie ALLE Prognosedaten und ändern Ihre Verkaufschancen-Ansicht. Falls Sie dies nicht wünschen, klicken Sie auf Abbrechen, um zu den vorherigen Einstellungen zurückzukehren.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Durch Klicken von "Bestätigen" löschen Sie ALLE Prognosedaten und ändern Ihre Verkaufschancen-Ansicht. '
        .'Auch ALLE Prozessdefinitionen mit einem Zielmodul von Umsatzposten werden deaktiviert. '
        .'Wenn Sie dies nicht wünschen, klicken Sie auf "Abbrechen", um zu den vorherigen Einstellungen zurúckzukehren.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Falls alle Umsatzposten geschlossen sind und mindestens einer gewonnen wurde,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'wird die Verkaufsphase "Verkaufschancen" auf "Gewonnen" gesetzt.',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Falls alle Umsatzposten sich in der Verkaufsphase "Verloren" befinden,',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'wird die Verkaufsphase "Verkaufschancen" auf "Verloren" gesetzt.',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Falls noch Umsatzposten offen sind,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'wird die Verkaufschance mit der niedrigsten Verkaufsphase markiert.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Nachdem Sie diese Änderung veranlasst haben, werden die Summierungsbemerkungen der Umsatzposten im Hintergrund erstellt. Wenn die Bemerkungen vollständig und verfügbar sind, wird eine Benachrichtigung an die E-Mail-Adresse in Ihrem Benutzerprofil gesendet. Falls Ihre Instanz für {{forecasts_module}} eingerichtet ist, sendet Sugar auch eine Benachrichtigung, wenn Ihre Berichte {{module_name}} mit dem Modul {{forecasts_module}} synchronisiert sind und für ein neues {{forecasts_module}} verfügbar sind. Bitte beachten Sie, dass Ihre Instanz auf das Senden von E-Mails über Admin > E-Mail-Einstellungen konfiguriert sein muss, damit die Benachrichtigungen gesendet werden können.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Nachdem Sie diese Änderung initialisiert haben, werden für jedes bestehende {{module_name}} im Hintergrund Umsatzposten-Berichte erstellt. Wenn die Umsatzposten vollständig und verfügbar sind, wird eine Benachrichtigung an die E-Mail-Adresse in Ihrem Benutzerprofil gesendet. Bitte beachten Sie, dass Ihre Instanz so konfiguriert sein muss, dass E-Mails über Admin > E-Mail-Einstellungen gesendet werden, damit die Benachrichtigung versendet werden kann.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Mit dem Modul {{plural_module_name}} können Sie einzelne Verkäufe von Anfang bis Ende nachverfolgen. Jeder Eintrag {{module_name}} stellt einen potentiellen Verkauf dar und schließt relevante Verkaufsdaten sowie Bezüge zu anderen wichtigen Einträgen, wie z. B. {{quotes_module}}, {{contacts_module}} usw. ein. Ein Eintrag {{module_name}} durchläuft normalerweise verschiedene Verkaufsphasen, bis er entweder die Markierung „Gewonnen“ oder „Verloren“ erhält. {{plural_module_name}} kann mithilfe des Moduls {{forecasts_singular_module}} von Sugar desweiteren auch noch dazu genutzt werden, Trends bei Verkäufen besser zu verstehen und vorherzusagen sowie für eine stärkere Konzentration auf das Erreichen der Verkaufsquoten.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Mit dem Modul {{plural_module_name}} können Sie einzelne Verkäufe, samt dazugehöriger Umsatzposten, von Anfang bis Ende nachverfolgen. Jeder Eintrag {{module_name}} stellt einen potentiellen Verkauf dar und schließt relevante Verkaufsdaten sowie Bezüge zu anderen wichtigen Einträgen, wie z. B. {{quotes_module}}, {{contacts_module}} usw. ein.

- Bearbeiten Sie die Felder, indem Sie auf ein bestimmtes Feld oder auf die Schaltfläche "Bearbeiten" klicken.
- Zeigen Sie Links zu anderen Einträgen in den Subpanels an oder bearbeiten Sie diese, indem Sie das Fenster links unten auf "Datenansicht" einstellen.
- Erstellen Sie Benutzerkommentare, zeigen Sie diese an oder zeigen Sie die Datensatz-Änderungshistorie in {{activitystream_singular_module}} an, indem Sie das Fenster links unten auf "Aktivitäts-Stream" einstellen.
- Folgen Sie diesem Datensatz oder markieren Sie ihn als Favoriten, indem Sie die Symbole rechts neben dem Datensatznamen verwenden.
- Weitere Aktionen stehen Ihnen im Dropdown-Menü rechts neben der Schaltfläche "Bearbeiten" zur Verfügung.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Mit dem Modul {{plural_module_name}} können Sie einzelne Verkäufe, samt dazugehöriger Umsatzposten, von Anfang bis Ende nachverfolgen. Jeder Eintrag {{module_name}} stellt einen potentiellen Verkauf dar und schließt relevante Verkaufsdaten sowie Bezüge zu anderen wichtigen Einträgen, wie z. B. {{quotes_module}}, {{contacts_module}} usw. ein.

So erstellen Sie ein {{module_name}}: 
1. Geben Sie die Werte nach Wunsch in die Felder ein.
- Felder, die als Pflichtfeld markiert sind, müssen vor dem Speichern ausgefüllt werden. 
- Klicken Sie auf "Mehr anzeigen", um bei Bedarf weitere Felder anzuzeigen
2. Klicken Sie auf "Speichern", um den neuen Datensatz fertigzustellen und zur vorhergehenden Seite zurückzukehren.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Mit Marketo® synchronisieren;',
    'LBL_MKTO_ID' => 'Marketo Interessenten-ID',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'Top 10 der Verkaufschancen',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Zeigt Top 10 der Verkaufschancen in einem Kugeldiagramm an.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Meine Verkaufschancen',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Verkaufschancen in meinem Team",
);
