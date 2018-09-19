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
  'LBL_MODULE_NAME' => 'Pravila procesnog poslovanja',
  'LBL_MODULE_TITLE' => 'Pravila procesnog poslovanja',
  'LBL_MODULE_NAME_SINGULAR' => 'Pravila procesnog poslovanja',

  'LBL_RST_UID' => 'ID poslovnog pravila',
  'LBL_RST_TYPE' => 'Tip poslovnog pravila',
  'LBL_RST_DEFINITION' => 'Definicija poslovnog pravila',
  'LBL_RST_EDITABLE' => 'Izmenjivo poslovno pravilo',
  'LBL_RST_SOURCE' => 'Izvor poslovnog pravila',
  'LBL_RST_SOURCE_DEFINITION' => 'Izvorna definicija poslovnog pravila',
  'LBL_RST_MODULE' => 'Ciljani modul',
  'LBL_RST_FILENAME' => 'Naziv datoteke poslovnog pravila',
  'LBL_RST_CREATE_DATE' => 'Datum kreiranja poslovnog pravila',
  'LBL_RST_UPDATE_DATE' => 'Datum ažuriranja poslovnog pravila',

    'LNK_LIST' => 'Prikazati pravila poslovnih procesa',
    'LNK_NEW_PMSE_BUSINESS_RULES' => 'Kreirati Pravila Procesnog Poslovanja',
    'LNK_IMPORT_PMSE_BUSINESS_RULES' => 'Uvesti Pravila Procesnog Poslovanja',

    'LBL_PMSE_TITLE_BUSINESS_RULES_BUILDER' => 'Izgradnja poslovnih procesa',

    'LBL_PMSE_LABEL_DESIGN' => 'Dizajn',
    'LBL_PMSE_LABEL_EXPORT' => 'Izvoz',
    'LBL_PMSE_LABEL_DELETE' => 'Obriši',

    'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL' => 'Sačuvati i izaći',
    'LBL_PMSE_SAVE_DESIGN_BUTTON_LABEL' => 'Sačuvati i dizajnirati',
    'LBL_PMSE_IMPORT_BUTTON_LABEL' => 'Uvoz',

    'LBL_PMSE_MY_BUSINESS_RULES' => 'Moja pravila poslovnih procesa',
    'LBL_PMSE_ALL_BUSINESS_RULES' => 'Sva pravila poslovnih procesa',

    'LBL_PMSE_BUSINESS_RULES_SINGLE_HIT' => 'Pojedinačna pravila poslovnih procesa',

    'LBL_PMSE_BUSINESS_RULES_IMPORT_TEXT' => 'Automatski kreiraj novu evidenciju pravila poslovnih procesa pomoću uvoza  *.pet dokumenta iz sopstvenog sistema datoteka.',
    'LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS' => 'Procesno poslovno pravilo je uspešno uvezeno u sistem.',
    'LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING' => 'Molim odabrati važeći *.pbr dokument.',

    'LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE' => 'Nepodržavajući tip podataka.',
    'LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE' => 'Molimo da se prvo definiše tip kolone.',
    'LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE' => 'Zaključak ,,Povratak" je prazan',
    'LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR' => 'nedostaje izraz ili operater',
    'LBL_PMSE_MESSAGE_LABEL_DELETE_ROW' => 'Da li zaista želite da izbrišete ovu grupu pravila?',
    'LBL_PMSE_MESSAGE_LABEL_MIN_ROWS' => 'Tabela sa odlukama mora da sadrži makar 1 red',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS' => 'Tabela sa odlukama mora da sadrži makar 1 uslovnu kolonu',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS' => 'Tabela sa odlukama mora sadržati makar 1 zaključnu kolonu',
    'LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE' => 'Vrednosti koje su povezane sa ovom promenljivom će biti uklonjene. Da li želite da nastavite?',
    'LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE' => 'Da li zaista želite sa uklonite ovu promenljivu?',

    'LBL_PMSE_LABEL_CONDITIONS' => 'Uslovi',
    'LBL_PMSE_LABEL_RETURN' => 'Povratak',
    'LBL_PMSE_LABEL_CONCLUSIONS' => 'Zaključci',
    'LBL_PMSE_LABEL_CHANGE_FIELD' => 'Promeni polje',
    'LBL_PMSE_LABEL_RETURN_VALUE' => 'Povratna vrednost',

    'LBL_PMSE_TOOLTIP_ADD_CONDITION' => 'Dodati uslov',
    'LBL_PMSE_TOOLTIP_ADD_CONCLUSION' => 'Dodati zaključak',
    'LBL_PMSE_TOOLTIP_ADD_ROW' => 'Dodati red',
    'LBL_PMSE_TOOLTIP_REMOVE_COLUMN' => 'Uklonite stubac',
    'LBL_PMSE_TOOLTIP_REMOVE_CONDITION' => 'Ukloni uslov',
    'LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION' => 'Ukloni zaključak',
    'LBL_PMSE_TOOLTIP_REMOVE_COL_DATA' => 'Ukloni podatak kolone',

    'LBL_PMSE_DROP_DOWN_CHECKED' => 'Da',
    'LBL_PMSE_DROP_DOWN_UNCHECKED' => 'Ne',
    'LBL_PMSE_IMPORT_BUSINESS_RULES_FAILURE' => 'Neuspešno kreiranje Pravila Procesnog Poslovanja iz dokumenta',

    'LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES' => 'Poslovno pravilo je nevažeće jer koristi nevažeća polja ili polja koja nisu pronađena u vašoj instanci SugarCRM-a. Ispravite greške ispod i snimite Poslovno pravilo.',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT' => 'Ovo Poslovno pravilo se trenutno koristi u Procesnoj definiciji. Da li i dalje želite da izmenite ovo Poslovno pravilo?',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE' => "Ne možete da obrišete ovo Poslovno pravilo jer se trenutno koristi u Procesnoj definiciji.",
);
