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
  'LBL_MODULE_NAME' => 'Prosessiyrityssäännöt',
  'LBL_MODULE_TITLE' => 'Prosessiyrityssäännöt',
  'LBL_MODULE_NAME_SINGULAR' => 'Prosessiyrityssääntö',

  'LBL_RST_UID' => 'Yrityssäännön ID',
  'LBL_RST_TYPE' => 'Yrityssäännön tyyppi',
  'LBL_RST_DEFINITION' => 'Yrityssäännön määritelmä',
  'LBL_RST_EDITABLE' => 'Yrityssäännön muokkausstatus',
  'LBL_RST_SOURCE' => 'Yrityssäännön lähde',
  'LBL_RST_SOURCE_DEFINITION' => 'Yrityssäännön lähteen määritelmän',
  'LBL_RST_MODULE' => 'Kantamoduuli',
  'LBL_RST_FILENAME' => 'Yrityssäännön tiedostonimi',
  'LBL_RST_CREATE_DATE' => 'Yrityssäännön luontipäivämäärä',
  'LBL_RST_UPDATE_DATE' => 'Yrityssäännön päivityspäivämäärä',

    'LNK_LIST' => 'Näytä prosessiyrityssäännöt',
    'LNK_NEW_PMSE_BUSINESS_RULES' => 'Luo prosessiyrityssääntö',
    'LNK_IMPORT_PMSE_BUSINESS_RULES' => 'Tuo prosessiyrityssääntöjä',

    'LBL_PMSE_TITLE_BUSINESS_RULES_BUILDER' => 'Yrityssääntöjen rakentaja',

    'LBL_PMSE_LABEL_DESIGN' => 'Suunnittele',
    'LBL_PMSE_LABEL_EXPORT' => 'Vie',
    'LBL_PMSE_LABEL_DELETE' => 'Poista',

    'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL' => 'Tallenna ja poistu',
    'LBL_PMSE_SAVE_DESIGN_BUTTON_LABEL' => 'Tallenna ja suunnittele',
    'LBL_PMSE_IMPORT_BUTTON_LABEL' => 'Tuonti',

    'LBL_PMSE_MY_BUSINESS_RULES' => 'Minun prosessiyrityssääntöni',
    'LBL_PMSE_ALL_BUSINESS_RULES' => 'Kaikki prosessiyrityssäännöt',

    'LBL_PMSE_BUSINESS_RULES_SINGLE_HIT' => 'Prosessiyrityssäännöt single hit',

    'LBL_PMSE_BUSINESS_RULES_IMPORT_TEXT' => 'Automaattisesti luo uusi prosessiyrityssääntötietue luomalla *.pbr-tiedosto tietokoneeltasi.',
    'LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS' => 'Prosessisähköpostimalli tuotiin järjestelmään.',
    'LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING' => 'Valitse validi *.pbr-tiedosto.',

    'LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE' => 'Tietotyyppiä ei tueta.',
    'LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE' => 'Määritä ensiksi sarakkeen tyyppi.',
    'LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE' => '"Return"-yhteenveto on tyhjä.',
    'LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR' => 'puuttuva ilmaisu tai operaattori',
    'LBL_PMSE_MESSAGE_LABEL_DELETE_ROW' => 'Poistetaanko varmasti tämä sääntöryhmä?',
    'LBL_PMSE_MESSAGE_LABEL_MIN_ROWS' => 'Päätöstaulussa on oltava vähintään yksi rivi',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS' => 'Päätöstaulussa on oltava vähintään yksi ehtosarake',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS' => 'Päätöstaulussa on oltava vähintään yksi konkluusiosarake',
    'LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE' => 'Tähän muuttujaan liittyvät arvot poistetaan. Haluatko jatkaa?',
    'LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE' => 'Haluatko varmasti poistaa muuttujan?',

    'LBL_PMSE_LABEL_CONDITIONS' => 'Ehdot',
    'LBL_PMSE_LABEL_RETURN' => 'Palaa',
    'LBL_PMSE_LABEL_CONCLUSIONS' => 'Konkluusio',
    'LBL_PMSE_LABEL_CHANGE_FIELD' => 'Muuta kenttä',
    'LBL_PMSE_LABEL_RETURN_VALUE' => 'Palauta arvo',

    'LBL_PMSE_TOOLTIP_ADD_CONDITION' => 'Lisää ehto',
    'LBL_PMSE_TOOLTIP_ADD_CONCLUSION' => 'Lisää konkluusio',
    'LBL_PMSE_TOOLTIP_ADD_ROW' => 'Lisää rivi',
    'LBL_PMSE_TOOLTIP_REMOVE_COLUMN' => 'Poista sarake',
    'LBL_PMSE_TOOLTIP_REMOVE_CONDITION' => 'Poista ehto',
    'LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION' => 'Poista päätös',
    'LBL_PMSE_TOOLTIP_REMOVE_COL_DATA' => 'Poista sarakkeen tiedot',

    'LBL_PMSE_DROP_DOWN_CHECKED' => 'Kyllä',
    'LBL_PMSE_DROP_DOWN_UNCHECKED' => 'Ei',
    'LBL_PMSE_IMPORT_BUSINESS_RULES_FAILURE' => 'Prosessiyrityssäännön luonti tiedostosta epäonnistui.',

    'LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES' => 'Tämä yrityssääntö ei kelpaa, koska siinä käytetään kelpaamattomia kenttiä tai kenttiä, joita ei ole omassa SugarCRM-instanssissasi. Korjaa alla mainitut virheet ja tallenna yrityssääntö.',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT' => 'Tätä yrityssääntöä käytetään tällä hetkellä prosessimääritelmässä. Haluatko silti muokata tätä yrityssääntöä?',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE' => "Et voi poistaa tätä yrityssääntöä, koska sitä käytetään tällä hetkellä prosessimääritelmässä.",
);
