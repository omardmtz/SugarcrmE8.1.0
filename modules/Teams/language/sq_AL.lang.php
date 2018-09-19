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
    'ERR_ADD_RECORD' => 'Duhet përcaktuar numrin e regjistrimit për të shtuar një përdorues në këtë grup.',
    'ERR_DUP_NAME' => 'Emri i grupit tashme ekziston, ju lutemi zgjidhni një emër tjetër.',
    'ERR_DELETE_RECORD' => 'Duhet përcaktuar numrin e regjistrimit për të fshirë këtë grup.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Gabim. Grupi i selektuar ({0}) është grup që keni zgjedhur të fshini. Ju lutemi selektoni një grup tjetër.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Gabim. Mund të mos fshini përdorues, grupi privat i të cilit nuk është i fshirë.',
    'LBL_DESCRIPTION' => 'Përshkrim',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalisht i dukshëm',
    'LBL_INVITEE' => 'Anëtarët e grupit',
    'LBL_LIST_DEPARTMENT' => 'Departamentet',
    'LBL_LIST_DESCRIPTION' => 'Përshkrim',
    'LBL_LIST_FORM_TITLE' => 'Lista e grupit',
    'LBL_LIST_NAME' => 'Emri',
    'LBL_FIRST_NAME' => 'Emri',
    'LBL_LAST_NAME' => 'Mbiemri',
    'LBL_LIST_REPORTS_TO' => 'I reporton',
    'LBL_LIST_TITLE' => 'Titulli',
    'LBL_MODULE_NAME' => 'Grupet',
    'LBL_MODULE_NAME_SINGULAR' => 'Grup',
    'LBL_MODULE_TITLE' => 'Grupet: Ballina',
    'LBL_NAME' => 'Emri i grupit',
    'LBL_NAME_2' => 'Emri i grupit (2)',
    'LBL_PRIMARY_TEAM_NAME' => 'Emri primar i grupit',
    'LBL_NEW_FORM_TITLE' => 'Grup i ri',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Grup privat për',
    'LBL_SEARCH_FORM_TITLE' => 'Kërkimi grupor',
    'LBL_TEAM_MEMBERS' => 'Anëtarët e grupit',
    'LBL_TEAM' => 'Grupet',
    'LBL_USERS_SUBPANEL_TITLE' => 'përdoruesit',
    'LBL_USERS' => 'përdoruesit',
    'LBL_REASSIGN_TEAM_TITLE' => 'Ekzistojnë regjjistrime drejtuar grupit/eve në vijim: {0}<br />Para se të fshini grupin/et, duhet fillimisht të ridrejtoni këto regjistrime në grup të ri. Selekto grup që do të përdoret si zëvendësues.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Ridrejtim',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Ridrejtim [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Vazhdo të azhornosh regjistrimet e ndikuara që do ti shfrytëzojë grupi i ri?',
    'LBL_REASSIGN_TABLE_INFO' => 'Tabela e azhornuar',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Veprimi ka mbaruar me sukses.',
    'LNK_LIST_TEAM' => 'Grupet',
    'LNK_LIST_TEAMNOTICE' => 'Shënimet grupore',
    'LNK_NEW_TEAM' => 'Krijo grup',
    'LNK_NEW_TEAM_NOTICE' => 'Krijo shënim grupor',
    'NTC_DELETE_CONFIRMATION' => 'A jeni të sigurtë që dëshironi që të fshini këtë regjistrim?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'A jeni të sigurt që dëshironi të fshini këtë përdorues/anëtar?',
    'LBL_EDITLAYOUT' => 'Ndrysho formatin' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Lejet me bazë grupi',
    'LBL_TBA_CONFIGURATION_DESC' => 'Mundëso aksesin e grupit dhe menaxho aksesin sipas modulit.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Aktivizo lejet me bazë grupi',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Zgjidh modulet për të mundësuar',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Aktivizimi i lejeve me bazë grupi do të mundësojë t&#39;u caktosh të drejta specifike aksesi grupeve dhe përdoruesve për module individuale nëpërmjet menaxhimit të roleve.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Çaktivizimi i lejeve me bazë grupi për një modul do të prapakthejë të dhënat që lidhen me lejet me bazë grupi për atë modul, duke përfshirë përcaktimet e proceseve ose proceset që përdorin funksionin. Kjo përfshin rolet që përdorin opsionin "Pronari dhe grupi i zgjedhur" për atë modul dhe të dhënat e lejeve me bazë grupi për regjistrimet në atë modul. Gjithashtu, rekomandojmë që të përdorësh mjetin "Riparim i shpejtë dhe rindërtim" për të spastruar memorien specifike të sistemit pas çaktivizimit të lejeve me bazë grupi për modulet.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Paralajmërim:</strong> Çaktivizimi i lejeve me bazë grupi për një modul do të prapakthejë të dhënat që lidhen me lejet me bazë grupi për atë modul, duke përfshirë përcaktimet e proceseve ose proceset që përdorin funksionin. Kjo përfshin rolet që përdorin opsionin "Pronari dhe grupi i zgjedhur" për atë modul dhe të dhënat e lejeve me bazë grupi për regjistrimet në atë modul. Gjithashtu, rekomandojmë që të përdorësh mjetin "Riparim i shpejtë dhe rindërtim" për të spastruar memorien specifike të sistemit pas çaktivizimit të lejeve me bazë grupi për modulet.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Çaktivizimi i lejeve me bazë grupi për një modul do të prapakthejë të dhënat që lidhen me lejet me bazë grupi për atë modul, duke përfshirë përcaktimet e proceseve ose proceset që përdorin funksionin. Kjo përfshin rolet që përdorin opsionin "Pronari dhe grupi i zgjedhur" për atë modul dhe të dhënat e lejeve me bazë grupi për regjistrimet në atë modul. Gjithashtu, rekomandojmë që të përdorësh mjetin "Riparim i shpejtë dhe rindërtim" për të spastruar memorien specifike të sistemit pas çaktivizimit të lejeve me bazë grupi për modulet. Nëse nuk ke akses për të përdorur "Riparim i shpejtë dhe rindërtim", kontakto administratorin që ka akses në menynë e riparimit.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Paralajmërim:</strong> Çaktivizimi i lejeve me bazë grupi për një modul do të prapakthejë të dhënat që lidhen me lejet me bazë grupi për atë modul, duke përfshirë përcaktimet e proceseve ose proceset që përdorin funksionin. Kjo përfshin rolet që përdorin opsionin "Pronari dhe grupi i zgjedhur" për atë modul dhe të dhënat e lejeve me bazë grupi për regjistrimet në atë modul. Gjithashtu, rekomandojmë që të përdorësh mjetin "Riparim i shpejtë dhe rindërtim" për të spastruar memorien specifike të sistemit pas çaktivizimit të lejeve me bazë grupi për modulet. Nëse nuk ke akses për të përdorur "Riparim i shpejtë dhe rindërtim", kontakto administratorin që ka akses në menynë e riparimit.
STR
,
);
