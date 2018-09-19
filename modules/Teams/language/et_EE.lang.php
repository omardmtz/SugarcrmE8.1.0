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
    'ERR_ADD_RECORD' => 'Kasutaja lisamiseks sellesse meeskonda täpsustage kirje numbrit.',
    'ERR_DUP_NAME' => 'Selline meeskonna nimi on juba olemas, valige midagi muud.',
    'ERR_DELETE_RECORD' => 'Selle meeskonna kustutamiseks täpsustage kirje numbrit.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Tõrge. Valitud meeskond <b>({0})</b> on kustutamiseks valitud meeskond. Valige teine meeskond.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Tõrge. Te ei saa kustutada kasutajat, kelle privaatne meeskond pole kustutatud.',
    'LBL_DESCRIPTION' => 'Kirjeldus:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globaalselt nähtav',
    'LBL_INVITEE' => 'Meeskonna liikmed',
    'LBL_LIST_DEPARTMENT' => 'Osakond',
    'LBL_LIST_DESCRIPTION' => 'Kirjeldus',
    'LBL_LIST_FORM_TITLE' => 'Meeskonna loend',
    'LBL_LIST_NAME' => 'Nimi',
    'LBL_FIRST_NAME' => 'Eesnimi:',
    'LBL_LAST_NAME' => 'Perekonnanimi:',
    'LBL_LIST_REPORTS_TO' => 'Juhataja',
    'LBL_LIST_TITLE' => 'Tiitel',
    'LBL_MODULE_NAME' => 'Meeskonnad',
    'LBL_MODULE_NAME_SINGULAR' => 'Meeskond',
    'LBL_MODULE_TITLE' => 'Meeskonnad: avaleht',
    'LBL_NAME' => 'Meeskonna nimi:',
    'LBL_NAME_2' => 'Meeskonna nimi (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Esmane meeskonna nimi',
    'LBL_NEW_FORM_TITLE' => 'Uus meeskond',
    'LBL_PRIVATE' => 'Privaatne',
    'LBL_PRIVATE_TEAM_FOR' => 'Privaatne meeskond:',
    'LBL_SEARCH_FORM_TITLE' => 'Meeskonna otsing',
    'LBL_TEAM_MEMBERS' => 'Meeskonna liikmed',
    'LBL_TEAM' => 'Meeskonnad:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Kasutajad',
    'LBL_USERS' => 'Kasutajad',
    'LBL_REASSIGN_TEAM_TITLE' => 'Kirjed on määratud järgmistele meeskondadele: <b>{0}</b><br>Enne meeskonna/meeskondade kustutamist peate need kirjed esmalt uuele meeskonnale ümber määrama. Valige asendusmeeskond.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Määra ümber',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Määra ümber',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Kas soovite jätkata uue meeskonna kasutamiseks mõjutatud kirjete värskendamist?',
    'LBL_REASSIGN_TABLE_INFO' => 'Tabeli {0} värskendamine',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Toiming on edukalt lõpetatud.',
    'LNK_LIST_TEAM' => 'Meeskonnad',
    'LNK_LIST_TEAMNOTICE' => 'Meeskonna teated',
    'LNK_NEW_TEAM' => 'Loo meeskond',
    'LNK_NEW_TEAM_NOTICE' => 'Loo meeskonna teade',
    'NTC_DELETE_CONFIRMATION' => 'Kas olete kindel, et soovite selle kirje kustutada?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Kas olete kindel, et soovite selle kasutaja liikmelisuse eemaldada?',
    'LBL_EDITLAYOUT' => 'Muuda paigutust' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Meeskonnapõhised õigused',
    'LBL_TBA_CONFIGURATION_DESC' => 'Meeskonna juurdepääsu haldamine ja juurdepääsu haldamine mooduli kaupa.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Luba meeskonnapõhised õigused',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Valige moodulid, mida lubada',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Meeskonnapõhiste õiguste lubamine võimaldab teil määrata asjakohased pääsuõigused meeskondadele ja kasutajatele individuaalsete moodulite puhul rollihalduse kaudu.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Meeskonnapõhiste õiguste keelamine mooduli puhul ennistab kõik andmed, mis on seotud selle mooduli meeskonnapõhiste õigustega, sh mis tahes protsessi määratlused või protsessid, mis kasutavad seda funktsiooni. See hõlmab mis tahes rolle, mis kasutavad selle mooduli puhul suvandit „Omanik ja valitud meeskond” ning mis tahes meeskonnapõhiste õiguste andmeid selle mooduli kirjete puhul.
Soovitame kasutada kiirparanduse ja taasehitamise tööriistu, et tühjendada süsteemi vahemälu pärast meeskonnapõhiste õiguste keelamist mis tahes mooduli puhul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Hoiatus:</strong> meeskonnapõhiste õiguste keelamine mooduli puhul ennistab kõik andmed, mis on seotud selle mooduli meeskonnapõhiste õigustega, sh mis tahes protsessi määratlused või protsessid, mis kasutavad seda funktsiooni. See hõlmab mis tahes rolle, mis kasutavad selle mooduli puhul suvandit „Omanik ja valitud meeskond” ning mis tahes meeskonnapõhiste õiguste andmeid selle mooduli kirjete puhul. Soovitame kasutada kiirparanduse ja taasehitamise tööriistu, et tühjendada süsteemi vahemälu pärast meeskonnapõhiste õiguste keelamist mis tahes mooduli puhul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Meeskonnapõhiste õiguste keelamine mooduli puhul ennistab kõik andmed, mis on seotud selle mooduli meeskonnapõhiste õigustega, sh mis tahes protsessi määratlused või protsessid, mis kasutavad seda funktsiooni. See hõlmab mis tahes rolle, mis kasutavad selle mooduli puhul suvandit „Omanik ja valitud meeskond” ning mis tahes meeskonnapõhiste õiguste andmeid selle mooduli kirjete puhul.
Soovitame kasutada kiirparanduse ja taasehitamise tööriistu, et tühjendada süsteemi vahemälu pärast meeskonnapõhiste õiguste keelamist mis tahes mooduli puhul. Kui teil pole kiirparanduse ja taasehitamise tööriistadele juurdepääsu, võtke ühendust administraatooriga, kellel on juurdepääs menüüle Parandamine.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Hoiatus:</strong> meeskonnapõhiste õiguste keelamine mooduli puhul ennistab kõik andmed, mis on seotud selle mooduli meeskonnapõhiste õigustega, sh mis tahes protsessi määratlused või protsessid, mis kasutavad seda funktsiooni. See hõlmab mis tahes rolle, mis kasutavad selle mooduli puhul suvandit „Omanik ja valitud meeskond” ning mis tahes meeskonnapõhiste õiguste andmeid selle mooduli kirjete puhul. Soovitame kasutada kiirparanduse ja taasehitamise tööriistu, et tühjendada süsteemi vahemälu pärast meeskonnapõhiste õiguste keelamist mis tahes mooduli puhul. Kui teil pole kiirparanduse ja taasehitamise tööriistadele juurdepääsu, võtke ühendust administraatooriga, kellel on juurdepääs menüüle Parandamine.
STR
,
);
