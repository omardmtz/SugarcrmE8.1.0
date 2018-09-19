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
    'LBL_MODULE_NAME' => 'Tietosuoja',
    'LBL_MODULE_NAME_SINGULAR' => 'Tietosuoja',
    'LBL_NUMBER' => 'Numero',
    'LBL_TYPE' => 'Tyyppi',
    'LBL_SOURCE' => 'Lähde',
    'LBL_REQUESTED_BY' => 'Pyytänyt',
    'LBL_DATE_OPENED' => 'Avaamispäivämäärä',
    'LBL_DATE_DUE' => 'Eräpäivä',
    'LBL_DATE_CLOSED' => 'Sulkemispäivämäärä',
    'LBL_BUSINESS_PURPOSE' => 'Suostumus liiketoimintatarkoituksiin kohteelle',
    'LBL_LIST_NUMBER' => 'Numero',
    'LBL_LIST_SUBJECT' => 'Aihe',
    'LBL_LIST_PRIORITY' => 'Prioriteetti',
    'LBL_LIST_STATUS' => 'Tila',
    'LBL_LIST_TYPE' => 'Tyyppi',
    'LBL_LIST_SOURCE' => 'Lähde',
    'LBL_LIST_REQUESTED_BY' => 'Pyytänyt',
    'LBL_LIST_DATE_DUE' => 'Eräpäivä',
    'LBL_LIST_DATE_CLOSED' => 'Sulkemispäivämäärä',
    'LBL_LIST_DATE_MODIFIED' => 'Muokkauspäivämäärä',
    'LBL_LIST_MODIFIED_BY_NAME' => 'Muokkaaja',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Vastuuhenkilö',
    'LBL_SHOW_MORE' => 'Näytä lisää tietosuojatoimia',
    'LNK_DATAPRIVACY_LIST' => 'Näytä tietosuojatoimet',
    'LNK_NEW_DATAPRIVACY' => 'Luo tietosuojatoimi',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Liidit',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Yhteystiedot',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => 'Tavoitteet',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Asiakkaat',
    'LBL_LISTVIEW_FILTER_ALL' => 'Kaikki tietosuojatoimet',
    'LBL_ASSIGNED_TO_ME' => 'Omat tietosuojatoimet',
    'LBL_SEARCH_AND_SELECT' => 'Hae ja valitse tietosuojatoimet',
    'TPL_SEARCH_AND_ADD' => 'Hae ja lisää tietosuojatoimet',
    'LBL_WARNING_ERASE_CONFIRM' => 'Olet poistamassa pysyvästi {0} kentän/kenttää. Näitä tietoja ei voi palauttaa poiston jälkeen. Haluatko varmasti jatkaa?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => 'Olet merkinnyt {0} kentän/kenttää poistettavaksi. Vahvistaminen keskeyttää poiston, säilyttää kaikki tiedot ja merkitsee pyynnön hylätyksi. Haluatko varmasti jatkaa?',
    'LBL_WARNING_COMPLETE_CONFIRM' => 'Olet merkitsemässä tätä pyyntöä suoritetuksi. Tämä muuttaa pysyvästi tilaksi Valmis eikä pyyntöä voi avata uudelleen. Haluatko varmasti jatkaa?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => 'Olet merkitsemässä tätä pyyntöä hylätyksi. Tämä muuttaa pysyvästi tilaksi Hylätty eikä pyyntöä voi avata uudelleen. Haluatko varmasti jatkaa?',
    'LBL_RECORD_SAVED_SUCCESS' => 'Luotiin tietosuojatoimi <a href="#{{buildRoute model=this}}">{{name}}</a>.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => 'Hylkää',
    'LBL_COMPLETE_BUTTON_LABEL' => 'Valmis',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => 'Poista ja suorita loppuun',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => 'Poista alipaneelien kautta valitut kentät',
    'LBL_COUNT_FIELDS_MARKED' => 'Poistettavaksi merkityt kentät',
    'LBL_NO_RECORDS_MARKED' => 'Ei poistettaviksi merkittyjä kenttiä tai tietueita.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => 'Tietosuojatietueiden työpöytä',

    // list view
    'LBL_HELP_RECORDS' => 'Tietosuojamoduuli tukee organisaation tietosuojatoimenpiteitä jäljittämällä tietosuojatoimet, mukaan lukien suostumus- ja aihepyynnöt. Luo henkilön tietueeseen liittyvät tietosuojatietueet (esim. yhteystieto) jotta voit jäljittää suostumuksen tai toimia tietosuojapyynnön mukaan.',
    // record view
    'LBL_HELP_RECORD' => 'Tietosuojamoduuli tukee organisaation tietosuojatoimenpiteitä jäljittämällä tietosuojatoimet, mukaan lukien suostumus- ja aihepyynnöt. Luo henkilön tietueeseen liittyvät tietosuojatietueet (esim. yhteystieto) jotta voit jäljittää suostumuksen tai toimia tietosuojapyynnön mukaan. Kun tarvittava toimenpide on valmis, käyttäjät, joilla on tietosuojavalvojan rooli, voivat päivittää tilaa napsauttamalla "Valmis" "tai Hylkää".

Jos kyseessä on poistopyyntö, valitse "Merkitse poistettavaksi" henkilön kaikkiin tietueisiin, jotka on lueteltu alla olevissa alipaneeleissa. Kun kaikki tarvittavat kentät on valittu, "Poista ja suorita loppuun" -kohdan napsauttaminen poistaa pysyvästi kentän arvot ja merkitsee tietosuojatietueen valmiiksi.',
);
