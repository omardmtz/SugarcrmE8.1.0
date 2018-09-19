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
    'ERR_ADD_RECORD' => 'Tietuenumero pitää määritellä, jotta käyttäjä voidaan lisätä tähän tiimiin.',
    'ERR_DUP_NAME' => 'Tiimin nimi on jo olemassa. Syötä uusi nimi.',
    'ERR_DELETE_RECORD' => 'Tietuenumero tulee määritellä, jotta voit poistaa tiimin.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Virhe: Valittu tiimi <b>({0})</b> on tiimi, jonka olet valinnut poistettavaksi. Valitse toinen tiimi.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Virhe: Et voi poistaa käyttäjää, jonka yksityistä tiimiä ei ole poistettu.',
    'LBL_DESCRIPTION' => 'Kuvaus:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globaalisti näkyvä',
    'LBL_INVITEE' => 'Tiimin jäsenet',
    'LBL_LIST_DEPARTMENT' => 'Osasto',
    'LBL_LIST_DESCRIPTION' => 'Kuvaus',
    'LBL_LIST_FORM_TITLE' => 'Tiimilista',
    'LBL_LIST_NAME' => 'Nimi',
    'LBL_FIRST_NAME' => 'Etunimi:',
    'LBL_LAST_NAME' => 'Sukunimi:',
    'LBL_LIST_REPORTS_TO' => 'Raportoi henkilölle',
    'LBL_LIST_TITLE' => 'Otsikko',
    'LBL_MODULE_NAME' => 'Tiimit',
    'LBL_MODULE_NAME_SINGULAR' => 'Tiimi',
    'LBL_MODULE_TITLE' => 'Tiimit: Etusivu',
    'LBL_NAME' => 'Tiimin nimi:',
    'LBL_NAME_2' => 'Tiimin nimi (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Ensisijainen tiimin nimi',
    'LBL_NEW_FORM_TITLE' => 'Uusi tiimi',
    'LBL_PRIVATE' => 'Yksityinen',
    'LBL_PRIVATE_TEAM_FOR' => 'Yksityistiimi henkilölle:',
    'LBL_SEARCH_FORM_TITLE' => 'Tiimihaku',
    'LBL_TEAM_MEMBERS' => 'Tiimin jäsenet',
    'LBL_TEAM' => 'Tiimit:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Käyttäjät',
    'LBL_USERS' => 'Käyttäjät',
    'LBL_REASSIGN_TEAM_TITLE' => 'Seuraaville tiimeille on määritelty tietueita: <b>{0}</b><br />Ennen tiimien poistamista, tietueet pitää siirtää toiselle tiimille. Valitse korvaava tiimi.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Siirrä',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Siirrä',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Jatketaanko kyseisten tietueiden tiimin päivitystä?',
    'LBL_REASSIGN_TABLE_INFO' => 'Päivitetään taulukkoa {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Toiminto suoritettiin onnistuneesti.',
    'LNK_LIST_TEAM' => 'Tiimit',
    'LNK_LIST_TEAMNOTICE' => 'Tiimimuistutukset',
    'LNK_NEW_TEAM' => 'Luo tiimi',
    'LNK_NEW_TEAM_NOTICE' => 'Luo tiimimuistutus',
    'NTC_DELETE_CONFIRMATION' => 'Haluatko varmasti poistaa tämän tietueen?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Haluatko varmasti poistaa tämän käyttäjän jäsenyyden?',
    'LBL_EDITLAYOUT' => 'Muokkaa asettelua' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Tiimipohjaiset oikeudet',
    'LBL_TBA_CONFIGURATION_DESC' => 'Käytä pääsyä tiimille ja hallinnoi käyttöä moduuleittain.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Käytä tiimipohjaisia oikeuksia',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Valitse käyttöön otettavat moduulit',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Tiimipohjaisia oikeuksia käyttämällä voit määrittää erityisiä käyttöoikeuksia tiimeille ja käyttäjille yksittäisiin moduuleihin Roolien hallinnan kautta.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Tiimipohjaisten oikeuksien poistaminen moduulista kumoaa tiimipohjaisiin oikeuksiin liittyvät tiedot kyseisessä moduulissa mukaan lukien prosessimääritykset tai toimintoa käyttävät prosessit. Näihin sisältyvät roolit, jotka käyttävät "Omistaja ja valittu tiimi" -vaihtoehtoa kyseisessä moduulissa, sekä tiimipohjaiset oikeudet kyseisen moduulin tietueisiin.
Suosittelemme myös, että moduulin tiimipohjaisten oikeuksien käytöstä poistamisen jälkeen tyhjennät järjestelmän välimuistin käyttämällä Pikakorjausta ja Uudelleenrakentaja-työkalua.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Varoitus:</strong>Tiimipohjaisten oikeuksien poistaminen moduulista kumoaa tiimipohjaisiin oikeuksiin liittyvät tiedot kyseisessä moduulissa mukaan lukien prosessimääritykset tai toimintoa käyttävät prosessit. Näihin sisältyvät roolit, jotka käyttävät "Omistaja ja valittu tiimi" -vaihtoehtoa kyseisessä moduulissa, sekä tiimipohjaiset oikeudet kyseisen moduulin tietueisiin.
Suosittelemme myös, että moduulin tiimipohjaisten oikeuksien käytöstä poistamisen jälkeen tyhjennät järjestelmän välimuistin käyttämällä Pikakorjausta ja Uudelleenrakentaja-työkalua.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Tiimipohjaisten oikeuksien poistaminen moduulista kumoaa tiimipohjaisiin oikeuksiin liittyvät tiedot kyseisessä moduulissa mukaan lukien prosessimääritykset tai toimintoa käyttävät prosessit. Näihin sisältyvät roolit, jotka käyttävät "Omistaja ja valittu tiimi" -vaihtoehtoa kyseisessä moduulissa, sekä tiimipohjaiset oikeudet kyseisen moduulin tietueisiin.
Suosittelemme myös, että moduulin tiimipohjaisten oikeuksien käytöstä poistamisen jälkeen tyhjennät järjestelmän välimuistin käyttämällä Pikakorjausta ja Uudelleenrakentaja-työkalua. Jos sinulla ei ole Pikakorjaus- ja Uudelleenrakentaja-toimintojen käyttöoikeuksia, ota yhteyttä järjestelmänvalvojaan pääsyn myöntämiseksi Korjaa-valikkoon.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Varoitus:</strong>Tiimipohjaisten oikeuksien poistaminen moduulista kumoaa tiimipohjaisiin oikeuksiin liittyvät tiedot kyseisessä moduulissa mukaan lukien prosessimääritykset tai toimintoa käyttävät prosessit. Näihin sisältyvät roolit, jotka käyttävät "Omistaja ja valittu tiimi" -vaihtoehtoa kyseisessä moduulissa, sekä tiimipohjaiset oikeudet kyseisen moduulin tietueisiin.
Suosittelemme myös, että moduulin tiimipohjaisten oikeuksien käytöstä poistamisen jälkeen tyhjennät järjestelmän välimuistin käyttämällä Pikakorjausta ja Uudelleenrakentaja-työkalua. Jos sinulla ei ole Pikakorjaus- ja Uudelleenrakentaja-toimintojen käyttöoikeuksia, ota yhteyttä järjestelmänvalvojaan pääsyn myöntämiseksi Korjaa-valikkoon.
STR
,
);
