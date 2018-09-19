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
  'LBL_MODULE_NAME' => 'Prozess-Geschäftsregeln',
  'LBL_MODULE_TITLE' => 'Prozess-Geschäftsregeln',
  'LBL_MODULE_NAME_SINGULAR' => 'Prozess-Geschäftsregel',

  'LBL_RST_UID' => 'Geschäftsregel-ID',
  'LBL_RST_TYPE' => 'Geschäftsregel-Typ',
  'LBL_RST_DEFINITION' => 'Geschäftsregel-Definition',
  'LBL_RST_EDITABLE' => 'Geschäftsregel bearbeitbar',
  'LBL_RST_SOURCE' => 'Geschäftsregel-Quelle',
  'LBL_RST_SOURCE_DEFINITION' => 'Geschäftsregel-Quellendefinition',
  'LBL_RST_MODULE' => 'Zielmodul:',
  'LBL_RST_FILENAME' => 'Geschäftsregel-Dateiname',
  'LBL_RST_CREATE_DATE' => 'Geschäftsregel-Erstellungsdatum',
  'LBL_RST_UPDATE_DATE' => 'Geschäftsregel-Aktualisierungsdatum',

    'LNK_LIST' => 'Prozess-Geschäftsregeln anzeigen',
    'LNK_NEW_PMSE_BUSINESS_RULES' => 'Prozess-Geschäftsregel erstellen',
    'LNK_IMPORT_PMSE_BUSINESS_RULES' => 'Prozess-Geschäftsregeln importieren',

    'LBL_PMSE_TITLE_BUSINESS_RULES_BUILDER' => 'Geschäftsregel-Ersteller',

    'LBL_PMSE_LABEL_DESIGN' => 'Designen',
    'LBL_PMSE_LABEL_EXPORT' => 'Exportieren',
    'LBL_PMSE_LABEL_DELETE' => 'Löschen',

    'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL' => 'Speichern und Beenden',
    'LBL_PMSE_SAVE_DESIGN_BUTTON_LABEL' => 'Speichern und Designen',
    'LBL_PMSE_IMPORT_BUTTON_LABEL' => 'Import',

    'LBL_PMSE_MY_BUSINESS_RULES' => 'Meine Prozess-Geschäftsregeln',
    'LBL_PMSE_ALL_BUSINESS_RULES' => 'Alle Prozess-Geschäftsregeln',

    'LBL_PMSE_BUSINESS_RULES_SINGLE_HIT' => 'Prozess-Geschäftsregel Einzelner Treffer',

    'LBL_PMSE_BUSINESS_RULES_IMPORT_TEXT' => 'Automatisch neuen Eintrag für Prozess-Geschäftsregel erstellen, wenn eine *.pbr-Datei aus Ihrem Dateisystem importiert wird.',
    'LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS' => 'Prozess-E-Mail-Vorlage wurde erfolgreich in das System importiert.',
    'LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING' => 'Bitte wählen Sie eine gültige *.pbr-Datei.',

    'LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE' => 'Nicht unterstützter Datentyp.',
    'LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE' => 'Bitte definieren Sie zuerst den Spaltentyp.',
    'LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE' => 'Die Schlussfolgerung "Return" ist leer.',
    'LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR' => 'fehlender Ausdruck oder Operator',
    'LBL_PMSE_MESSAGE_LABEL_DELETE_ROW' => 'Möchten Sie diesen Regelsatz wirklich löschen?',
    'LBL_PMSE_MESSAGE_LABEL_MIN_ROWS' => 'Die Entscheidungstabelle muss mindestens 1 Zeile haben',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS' => 'Die Entscheidungstabelle muss mindestens 1 Bedingungsspalte haben',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS' => 'Die Entscheidungstabelle muss mindestens 1 Schlussfolgerungsspalte haben',
    'LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE' => 'Die Werte für diese Variablen werden entfernt. Möchten Sie fortfahren?',
    'LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE' => 'Möchten Sie diese Variable wirklich entfernen?',

    'LBL_PMSE_LABEL_CONDITIONS' => 'Bedingungen',
    'LBL_PMSE_LABEL_RETURN' => 'Zurück',
    'LBL_PMSE_LABEL_CONCLUSIONS' => 'Schlussfolgerungen',
    'LBL_PMSE_LABEL_CHANGE_FIELD' => 'Feld ändern',
    'LBL_PMSE_LABEL_RETURN_VALUE' => 'Rückgabewert',

    'LBL_PMSE_TOOLTIP_ADD_CONDITION' => 'Bedingung hinzufügen',
    'LBL_PMSE_TOOLTIP_ADD_CONCLUSION' => 'Schlussfolgerung hinzufügen',
    'LBL_PMSE_TOOLTIP_ADD_ROW' => 'Zeile hinzufügen',
    'LBL_PMSE_TOOLTIP_REMOVE_COLUMN' => 'Spalte entfernen',
    'LBL_PMSE_TOOLTIP_REMOVE_CONDITION' => 'Bedingung entfernen',
    'LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION' => 'Schlussfolgerung entfernen',
    'LBL_PMSE_TOOLTIP_REMOVE_COL_DATA' => 'Spaltendaten entfernen',

    'LBL_PMSE_DROP_DOWN_CHECKED' => 'Ja',
    'LBL_PMSE_DROP_DOWN_UNCHECKED' => 'Nein',
    'LBL_PMSE_IMPORT_BUSINESS_RULES_FAILURE' => 'Erstellen der Prozess-Geschäftsregel aus Datei fehlgeschlagen',

    'LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES' => 'Diese Geschäftsregel ist ungültig, da sie ungültige Felder oder Felder verwendet, die nicht in Ihrer Instanz von SugarCRM vorhanden sind. Bitte beheben Sie die nachfolgenden Fehler und speichern Sie die Geschäftsregel.',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT' => 'Diese Geschäftsregel wird derzeit in einer Prozess-Definition verwendet. Möchten Sie diese Geschäftsregel trotzdem bearbeiten?',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE' => "Sie können diese Geschäftsregel nicht löschen, da sie derzeit in einer Prozess-Definition verwendet wird.",
);
