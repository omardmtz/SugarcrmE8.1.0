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
  'LBL_MODULE_NAME' => 'Pravidla obchodních procesů',
  'LBL_MODULE_TITLE' => 'Pravidla obchodních procesů.',
  'LBL_MODULE_NAME_SINGULAR' => 'Pravidla obchodních procesů.',

  'LBL_RST_UID' => 'ID obchodního pravidla',
  'LBL_RST_TYPE' => 'Typ obchodního pravidla',
  'LBL_RST_DEFINITION' => 'Definice obchodního pravidla',
  'LBL_RST_EDITABLE' => 'Obchodní pravidlo je možné evidovat',
  'LBL_RST_SOURCE' => 'Zdroj obchodního pravidla',
  'LBL_RST_SOURCE_DEFINITION' => 'Definice zdroje obchodního pravidla',
  'LBL_RST_MODULE' => 'Cílový Modul:',
  'LBL_RST_FILENAME' => 'Souborový název obchodního pravidla',
  'LBL_RST_CREATE_DATE' => 'Datum vytvoření obchodního pravidla',
  'LBL_RST_UPDATE_DATE' => 'Datum aktualizace obchodního pravidla',

    'LNK_LIST' => 'Zobrazit proces obchodních pravidel',
    'LNK_NEW_PMSE_BUSINESS_RULES' => 'Vytvořit procesové obchodní pravidlo',
    'LNK_IMPORT_PMSE_BUSINESS_RULES' => 'Importovat procesové obchodní pravidla',

    'LBL_PMSE_TITLE_BUSINESS_RULES_BUILDER' => 'builder obchodních pravidel',

    'LBL_PMSE_LABEL_DESIGN' => 'Design',
    'LBL_PMSE_LABEL_EXPORT' => 'Exportovat',
    'LBL_PMSE_LABEL_DELETE' => 'Smazat',

    'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL' => 'Uložit a zavřít',
    'LBL_PMSE_SAVE_DESIGN_BUTTON_LABEL' => 'Uložit a sestavit',
    'LBL_PMSE_IMPORT_BUTTON_LABEL' => 'Importovat',

    'LBL_PMSE_MY_BUSINESS_RULES' => 'Můj proces obchodních pravidel',
    'LBL_PMSE_ALL_BUSINESS_RULES' => 'Celý proces obchodních pravidel',

    'LBL_PMSE_BUSINESS_RULES_SINGLE_HIT' => 'Single hit proces obchodních pravidel',

    'LBL_PMSE_BUSINESS_RULES_IMPORT_TEXT' => 'Automaticky vytvořit nové Pravidlo obchodního procesu importováním *.pbr souboru z vašeho souborového systému.',
    'LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS' => 'Proces emailové šablony byl úspěšně importován do systému.',
    'LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING' => 'Prosím vyberte platný *.pbr soubor.',

    'LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE' => 'Nepodporovaný typ dat.',
    'LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE' => 'Prosím definujte první sloupcový tvar',
    'LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE' => '"Return" konkluze je prázdná',
    'LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR' => 'Chybějící výraz nebo operátor',
    'LBL_PMSE_MESSAGE_LABEL_DELETE_ROW' => 'Opravdu chcete smazat tento set pravidel?',
    'LBL_PMSE_MESSAGE_LABEL_MIN_ROWS' => 'Tabulka rozhodnutí musí mít alespoň jednu řádku',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS' => 'Tabulka rozhodnutí musí mít alespoň jednu podmínkový řádek',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS' => 'Tabulka rozhodnutí musí mít alespoň jednu závěrečný řádek',
    'LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE' => 'Hodnoty přiřazené k této proměnné budou odebrány. Chcete pokračovat?',
    'LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE' => 'Opravdu si přejete odebrat tuto proměnnou?',

    'LBL_PMSE_LABEL_CONDITIONS' => 'Podmínky',
    'LBL_PMSE_LABEL_RETURN' => 'Zpátky',
    'LBL_PMSE_LABEL_CONCLUSIONS' => 'Závěr',
    'LBL_PMSE_LABEL_CHANGE_FIELD' => 'Změnit pole',
    'LBL_PMSE_LABEL_RETURN_VALUE' => 'Vrátit hodnotu',

    'LBL_PMSE_TOOLTIP_ADD_CONDITION' => 'přidat podmínku',
    'LBL_PMSE_TOOLTIP_ADD_CONCLUSION' => 'Přidat závěr',
    'LBL_PMSE_TOOLTIP_ADD_ROW' => 'přidat řádek',
    'LBL_PMSE_TOOLTIP_REMOVE_COLUMN' => 'Odebrat sloupec',
    'LBL_PMSE_TOOLTIP_REMOVE_CONDITION' => 'Odebrat podmínku',
    'LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION' => 'Odebrat závěr',
    'LBL_PMSE_TOOLTIP_REMOVE_COL_DATA' => 'Odebrat data ve sloupci',

    'LBL_PMSE_DROP_DOWN_CHECKED' => 'Ano',
    'LBL_PMSE_DROP_DOWN_UNCHECKED' => 'Ne',
    'LBL_PMSE_IMPORT_BUSINESS_RULES_FAILURE' => 'Nepodařilo se vytvořit obchodní pravidlo procesu ze souboru',

    'LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES' => 'Toto obchodní pravidlo je neplatné, protože používá neplatná pole nebo pole, která nejsou k dispozici v této instanci SugarCRM. Opravte chyby a uložte obchodní pravidlo.',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT' => 'Obchodní pravidlo se aktuálně používá v definici procesu. Opravdu chcete upravit obchodní pravidlo?',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE' => "Toto obchodní pravidlo nelze odstranit, protože se aktuálně používá v definici procesu.",
);
